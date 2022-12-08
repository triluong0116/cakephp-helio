<?php

namespace App\Controller\Editor;

use App\Controller\AppController;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\Hotels $Hotels
 * @property \App\Model\Table\UserTransactionsTable $UserTransactions
 *
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookingsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 12
        ];
        $paginate = $this->Bookings->find()->contain(['Users', 'Combos', 'Vouchers', 'LandTours', 'Hotels'])->where(
            ['OR' => ['Users.id' => $this->Auth->user('id'),
                'Users.parent_id' => $this->Auth->user('id')]])->order(['Bookings.created' => 'DESC']);
//        dd($paginate->toArray());
        $bookings = $this->paginate($paginate);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_bookings = $this->Bookings->find()->where([
                'OR' => [
                    'Bookings.full_name LIKE' => '%' . $data . '%',
                ]
            ]);

            $number = $list_object_bookings->count();
            $bookings = $this->paginate($list_object_bookings);
            $this->set(compact('bookings', 'number', 'data'));
        } else {
            $this->set(compact('bookings'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Combos']
        ]);

        $this->set('booking', $booking);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $booking = $this->Bookings->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $date_array = explode(' - ', $data['reservation']);
            $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
            $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');
            switch ($data['type']) {
                case COMBO:
                    $dataBooking = $this->_buildSaveCombo($data);
                    break;
                case VOUCHER:
                    $dataBooking = $this->_buildSaveVoucher($data);
                    break;
                case LANDTOUR:
                    $dataBooking = $this->_buildSaveLandtour($data);
                    break;
                case HOTEL:
                    $dataBooking = $this->_buildSaveHotel($data);
                    break;
            }
            if ($dataBooking['success']) {
                $dataBooking['data']['user_id'] = $this->Auth->user('id');
                $booking = $this->Bookings->patchEntity($booking, $dataBooking['data']);
                if ($this->Bookings->save($booking)) {
                    $this->Flash->success(__('The booking has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The booking could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__($dataBooking['message']));
            }
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $object_types = [
            COMBO => 'Combo',
            VOUCHER => 'Voucher',
            LANDTOUR => 'Land Tour',
            HOTEL => 'Hotel'
        ];
        $this->set(compact('booking', 'users', 'combos', 'object_types'));
    }

    private function _buildSaveCombo($data)
    {
        $this->loadModel('Combos');
        $response = ['success' => false, 'data' => [], 'message' => ''];
        $priceArray = [];
        $end_date = date('d-m-Y', strtotime($data['end_date'] . ' - 1 day'));
        $bookingDateArray = $this->Util->_dateRange($data['start_date'], $end_date);
        $combo = $this->Combos->get($data['item_id'], ['contain' => ['Hotels', 'Hotels.PriceHotels']]);
        $comboDay = 0;
        foreach ($combo->hotels as $hotel) {
            $comboDay += $hotel->_joinData->days_attended;
        }
        if ($comboDay == count($bookingDateArray)) {
            $hotelDates = [];
            $priceCombo = 0;
            $revenue = 0;
            $index = 0;
            foreach ($combo->hotels as $hotel) {
                $comboHotelDates = $hotelDates = [];
                $hotelPrices = [];
                for ($i = 0; $i < $hotel->_joinData->days_attended; $i++) {
                    $comboHotelDates[] = $bookingDateArray[$index];
                    $revenue += $hotel->price_customer;
                    $index++;
                }
                foreach ($hotel->price_hotels as $price) {
                    $price_start_date = date('Y-m-d', strtotime($price->start_date));
                    $price_end_date = date('Y-m-d', strtotime($price->end_date));
                    $tmpPriceHotel = $this->Util->_createDateRangePriceArray($price_start_date, $price_end_date, $price->price);
                    $hotelPrices = array_merge($hotelPrices, $tmpPriceHotel);
                }
                foreach ($comboHotelDates as $date) {
                    if (isset($hotelPrices[$date])) {
                        $priceCombo = $priceCombo + $hotelPrices[$date] + $hotel->price_agency + $hotel->price_customer;
                    } else {
                        $rsPrice = reset($tmpPriceHotel);
                        $priceCombo = $priceCombo + $rsPrice + $hotel->price_agency + $hotel->price_customer;
                    }
                }
            }
            $data['price'] = $priceCombo * $data['amount'];
            $data['revenue'] = $revenue * $data['amount'];
            $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'Y-m-d');
            $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
            $response['success'] = true;
            $response['data'] = $data;
        } else {
            $response['message'] = 'Phải chọn đúng khoảng thời gian Combo cung cấp!';
        }
        return $response;
    }

    private function _buildSaveVoucher($data)
    {
        $this->loadModel('Vouchers');
        $response = ['success' => false, 'data' => [], 'message' => ''];
        $priceArray = [];
        $bookingDateArray = $this->Util->_dateRange($data['start_date'], $data['end_date']);
        $voucher = $this->Vouchers->get($data['item_id']);
        if ($voucher->days_attended == count($bookingDateArray)) {
            if ($this->Util->checkBetweenDate($data['start_date'], $voucher->start_date, $voucher->end_date) && $this->Util->checkBetweenDate($data['end_date'], $voucher->start_date, $voucher->end_date)) {
                $priceCombo = 0;
                $revenue = $voucher->customer_price * $data['amount'];
                $priceCombo = $voucher->price + $voucher->agency_price + $voucher->customer_price;
                $data['price'] = $priceCombo * $data['amount'];
//                dd($data['price']);
                $data['revenue'] = $revenue;
                $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'Y-m-d');
                $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
                $response['success'] = true;
                $response['data'] = $data;
            } else {
                $response['message'] = 'Phải chọn đúng khoảng thời gian Voucher có hiệu lực';
            }
        } else {
            $response['message'] = 'Phải chọn đúng khoảng thời gian Voucher cung cấp!';
        }
        return $response;
    }

    private function _buildSaveLandtour($data)
    {
        $this->loadModel('LandTours');
        $response = ['success' => false, 'data' => [], 'message' => ''];
        $voucher = $this->LandTours->get($data['item_id']);
        if ($this->Util->checkBetweenDate($data['start_date'], $voucher->start_date, $voucher->end_date) && $this->Util->checkBetweenDate($data['end_date'], $voucher->start_date, $voucher->end_date)) {
            $priceCombo = 0;
            $revenue = $voucher->customer_price * $data['amount'];
            $priceCombo = $voucher->price + $voucher->agency_price + $voucher->customer_price;
            $data['price'] = $priceCombo * $data['amount'];
//                dd($data['price']);
            $data['revenue'] = $revenue;
            $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'Y-m-d');
            $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
            $response['success'] = true;
            $response['data'] = $data;
        } else {
            $response['message'] = 'Phải chọn đúng khoảng thời gian Landtour có hiệu lực';
        }

        return $response;
    }

    private function _buildSaveHotel($data)
    {
        $this->loadModel('Hotels');
        $response = ['success' => false, 'data' => [], 'message' => ''];
        $priceArray = [];
//        dd($data);
        $end_date = date('d-m-Y', strtotime($data['end_date'] . ' - 1 day'));
        $bookingDateArray = $this->Util->_dateRange($data['start_date'], $end_date);
        $hotel = $this->Hotels->get($data['item_id'], ['contain' => 'PriceHotels']);
        $tmpPriceHotel = [];
        $hotelPrices = [];
        foreach ($hotel->price_hotels as $price) {
//                        debug($price);
            $price_start_date = date('Y-m-d', strtotime($price->start_date));
            $price_end_date = date('Y-m-d', strtotime($price->end_date));
            $tmpPriceHotel = $this->_createDateRangePriceArray($price_start_date, $price_end_date, $price->price);
            $hotelPrices = array_merge($hotelPrices, $tmpPriceHotel);
        }
        $totalPrice = 0;
        foreach ($bookingDateArray as $date) {
            if (isset($hotelPrices[$date])) {
                $totalPrice += ($hotelPrices[$date] + $hotel->price_agency + $hotel->price_customer);
            } else {
                $totalPrice += ($hotel->price_agency + $hotel->price_customer);
            }
        }
        $data['price'] = $totalPrice * $data['amount'];
        $data['revenue'] = ($hotel->price_customer) * count($bookingDateArray) * $data['amount'];
        $data['price'] = $totalPrice * $data['amount'];
        $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'Y-m-d');
        $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
        $response['success'] = true;
        $response['data'] = $data;
        return $response;
    }

    /**
     * Edit method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $date_array = explode(' - ', $data['reservation']);
            $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
            $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');
            switch ($data['type']) {
                case COMBO:
                    $dataBooking = $this->_buildSaveCombo($data);
                    break;
                case VOUCHER:
                    $dataBooking = $this->_buildSaveVoucher($data);
                    break;
                case LANDTOUR:
                    $dataBooking = $this->_buildSaveLandtour($data);
                    break;
                case HOTEL:
                    $dataBooking = $this->_buildSaveHotel($data);
                    break;
            }
            if ($dataBooking['success']) {
                $booking = $this->Bookings->patchEntity($booking, $dataBooking['data']);
                if ($this->Bookings->save($booking)) {
                    $this->Flash->success(__('The booking has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The booking could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__($dataBooking['message']));
            }
        }
        $object_types = [
            COMBO => 'Combo',
            VOUCHER => 'Voucher',
            LANDTOUR => 'Land Tour',
            HOTEL => 'Hotel'
        ];
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $this->set(compact('booking', 'users', 'object_types'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function getListObjectByType()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Combos');
        $this->loadModel('Vouchers');
        $this->loadModel('LandTours');
        $this->loadModel('Hotels');
        $data = $this->request->getQuery();
        $item_id = $data['object_id'];
        $label = '';
        switch ($data['object_type']) {
            case COMBO:
                $query = $this->Combos;
                $label = 'Chọn Combo *';
                break;
            case VOUCHER:
                $query = $this->Vouchers;
                $label = 'Chọn Voucher *';
                break;
            case LANDTOUR:
                $query = $this->LandTours;
                $label = 'Chọn LandTour *';
                break;
            case HOTEL:
                $query = $this->Hotels;
                $label = 'Chọn Hotel *';
                break;
        }
        $objects = $query->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ]);
        $this->set(compact('objects', 'label', 'item_id'));
    }

    public function bookingSendEmail($booking_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Users');
        $this->loadComponent('Email');
        $response = ['success' => false, 'message' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $booking = $this->Bookings->get($booking_id, ['contain' => ['Users', 'Combos', 'Hotels', 'Vouchers', 'Landtours', 'Combos.Hotels', 'Vouchers.Hotels']]);
//            dd($data);
            switch ($data['type']) {
                case E_PAY_AGENCY:
                    $response = $this->_sendPaymentToAgency($booking);
                    break;
                case E_BOOK_HOTEL:
                    $response = $this->_sendRequestBookingToHotel($booking);
                    break;
                case E_BOOK_AGENCY:
                    $response = $this->_sendBookingInfoToAgency($booking);
                    break;
            }
        }

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    private function _sendPaymentToAgency($booking)
    {
        $user = $this->Users->get($booking->user_id);
        $bodyEmail = 'Đơn hàng thanh toán cho booking: ' . $booking->id;
//        dd($booking);
        if ($booking->vouchers) {
            $subject = $subject = "Mustgo.vn - Yêu cầu thanh toán - " . $booking->vouchers->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->hotels) {
            $subject = "Mustgo.vn - Yêu cầu thanh toán - " . $booking->hotels->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->combos) {
//            dd($booking->combos->name);
            $subject = "Mustgo.vn - Yêu cầu thanh toán - " . $booking->combos->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->LandTours['name']) {
            $subject = "Mustgo.vn - Yêu cầu thanh toán - " . $booking->LandTours['name'] . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        $data_sendEmail = [
            'to' => $user->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_PAY_AGENCY);
//        if ($response['success']) {
//            $booking = $this->Bookings->patchEntity($booking);
//            $this->Bookings->save($booking);
//        }
        return $response;
    }

    private function _sendRequestBookingToHotel($booking)
    {
        $res = ['success' => true, 'request_booking' => false, 'data' => []];
        switch ($booking->type) {
            case COMBO:
                $this->loadModel('Combos');
                $combo = $this->Combos->get($booking->item_id, ['contain' => ['Hotels']]);
                $checkEmail = true;
                $currentDay = date_format($booking->start_date, "Y-m-d");
                foreach ($combo->hotels as $hotel) {
                    $booking->hotel = $hotel;
                    $booking->start_date_hotel = $currentDay;
                    $booking->end_date_hotel = date('Y-m-d', strtotime($booking->start_date_hotel . ' + ' . ($hotel->_joinData->days_attended) . ' days'));
                    $bodyEmail = 'Book phòng!';
                    $data_sendEmail = [
                        'to' => json_decode($hotel->email),
                        'subject' => 'Mustgo.vn - Đặt phòng - ' . $hotel->name . ' - ' . $booking->full_name . ' - ' . date("d/m/Y", strtotime($booking->start_date_hotel)) . ' - ' . date("d/m/Y", strtotime($booking->end_date_hotel)),
                        'title' => 'Mustgo.vn - Đặt phòng - ' . $hotel->name . ' - ' . $booking->full_name . ' - ' . date("d/m/Y", strtotime($booking->start_date_hotel)) . ' - ' . date("d/m/Y", strtotime($booking->end_date_hotel)),
                        'body' => $bodyEmail,
                        'data' => $booking,
                    ];
                    $currentDay = $booking->end_date_hotel;
                    $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                    if ($response['success'] == false) {
                        $checkEmail = false;
                    }
                    $response['hotel_name'] = $hotel->name;
                    $res['data'][] = $response;
                }
//                if ($checkEmail) {
//                    $booking = $this->Bookings->patchEntity($booking);
//                    $this->Bookings->save($booking);
//                }
                $res['request_booking'] = true;
                break;
            case VOUCHER:
                $this->loadModel('Vouchers');
                $voucher = $this->Vouchers->get($booking->item_id, ['contain' => ['Hotels']]);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->vouchers->hotel->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $data_sendEmail = [
                    'to' => json_decode($voucher->hotel->email),
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);

//                if ($response['success']) {
//                    $booking = $this->Bookings->patchEntity($booking);
//                    $this->Bookings->save($booking);
//                }
                break;
            case LANDTOUR:
                $this->loadModel('LandTours');
                $landTour = $this->LandTours->get($booking->item_id);
                $bodyEmail = 'Book LandTour: ' . $booking->id;
                $data_sendEmail = [
                    'to' => $landTour->email,
                    'subject' => 'Book LandTour!',
                    'title' => 'Book LandTour!',
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);

//                if ($response['success']) {
//                    $booking = $this->Bookings->patchEntity($booking);
//                    $this->Bookings->save($booking);
//                }
                break;
            case HOTEL:
                $this->loadModel('Hotels');
                $hotel = $this->Hotels->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->hotels->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $data_sendEmail = [
                    'to' => json_decode($hotel->email),
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);

//                if ($response['success']) {
//                    $booking = $this->Bookings->patchEntity($booking);
//                    $this->Bookings->save($booking);
//                }
                break;
        }
        return $res;
    }

    private function _sendBookingInfoToAgency($booking)
    {
        $user = $this->Users->get($booking->user_id);
        $bodyEmail = 'Xác nhận đặt phòng thành công cho Booking: ' . $booking->code;
        if ($booking->vouchers) {
            $subject = $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->vouchers->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->hotels) {
            $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->hotels->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->combos) {
            $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->combos->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->LandTours['name']) {
            $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->LandTours['name'] . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        $data_sendEmail = [
            'to' => $user->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_AGENCY);
        if ($response['success']) {
            if ($booking->status != 3) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 2]);
                $this->Bookings->save($booking);
            }

        }
        return $response;
    }

    private function _createDateRangePriceArray($fromDate, $toDate, $price)
    {
        $current = strtotime($fromDate);
        $last = strtotime($toDate);

        while ($current <= $last) {
            $priceArray[date('Y-m-d', $current)] = $price;
            $current = strtotime('+1 day', $current);
        }
        return $priceArray;
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 4)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }

}
