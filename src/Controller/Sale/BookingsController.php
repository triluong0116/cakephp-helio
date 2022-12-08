<?php

namespace App\Controller\Sale;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\HomeStaysTable $HomeStays
 * @property \App\Model\Table\RoomsTable $Rooms
 * @property \App\Model\Table\UserTransactionsTable $UserTransactions
 * @property \App\Model\Table\BookingSurchargesTable $BookingSurcharges
 * @property \App\Model\Table\BookingRoomsTable $BookingRooms
 * @property \App\Model\Table\BookingLandtoursTable $BookingLandtours
 * @property \App\Model\Table\BookingLandtourAccessoriesTable $BookingLandtourAccessories
 * @property \App\Model\Table\PaymentsTable $Payments
 * @property \App\Model\Table\LandTourAccessoriesTable $LandTourAccessories
 * @property \App\Model\Table\LandTourDrivesurchagesTable $LandTourDrivesurchages
 * @property \App\Model\Table\LandTourUserPricesTable $LandTourUserPrices
 * @property \App\Model\Table\VinhmsbookingsTable $Vinhmsbookings
 * @property \App\Model\Table\VinpaymentsTable $Vinpayments
 * @property \App\Model\Table\VinhmsbookingRoomsTable $VinhmsbookingRooms
 *
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
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['bookingSendEmailV2']);
    }

    public function index()
    {
        $this->loadModel('Payments');
        $this->paginate = [
            'limit' => 12
        ];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $keyword = '';
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $condition = [];
        if ($this->Auth->user('role_id') == 2) {
            $condition['Bookings.type IN'] = [HOTEL, HOMESTAY, VOUCHER];
        } else {
            $condition['Bookings.type'] = LANDTOUR;
        }
        $condition[]['OR'] = [
            'Bookings.sale_id' => $this->Auth->user('id'),
            [
                'Bookings.sale_id' => 0,
                'Bookings.user_id' => 0,
            ]
        ];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        $condition['Bookings.status IN'] = [0, 1, 2, 3, 4, 5];
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Bookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Bookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition['OR'] = [
                'code LIKE' => '%' . $keyword . '%',
                'Users.screen_name LIKE' => '%' . $keyword . '%',
                'Users.username LIKE' => '%' . $keyword . '%',
                'Hotels.name LIKE' => '%' . $keyword . '%',
                'LandTours.name LIKE' => '%' . $keyword . '%',
                'Vouchers.name LIKE' => '%' . $keyword . '%',
                'HomeStays.name LIKE' => '%' . $keyword . '%',
                'full_name LIKE' => '%' . $keyword . '%',
                'hotel_code LIKE' => '%' . $keyword . '%'
            ];
        }
        $paginate = $this->Bookings->find()->contain(['Users', 'HomeStays', 'Vouchers', 'LandTours', 'Hotels'])->where($condition)->order(
            [
                'Bookings.created' => 'DESC'
            ]);
        $bookings = $this->paginate($paginate);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            $booking->payment = $payment;
        }
        $this->set(compact('bookings', 'data', 'agencyPay', 'payHotel', 'keyword', 'sDate', 'eDate'));
    }

    public function indexVin()
    {
        $this->loadModel('Vinhmsbookings');
        $this->paginate = [
            'limit' => 12
        ];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $keyword = '';
        $phone = '';
        $code = '';
        $email = '';
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $cDate = date('d/m/Y');
        $condition = [];
//        $condition[]['OR'] = [
//            'Vinhmsbookings.sale_id' => $this->Auth->user('id'),
//            [
//                'Vinhmsbookings.sale_id' => 0,
//                'Vinhmsbookings.user_id' => 0,
//            ]
//        ];
        $condition['Vinhmsbookings.status IN'] = [0, 1, 2, 3, 4, 5];
        $condition['Vinhmsbookings.sale_id'] = $this->Auth->user('id');
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Vinhmsbookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Vinhmsbookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        if (isset($data['create_date']) && $data['create_date'] != null) {
            $condition['DATE(Vinhmsbookings.created) '] = $this->Util->formatSQLDate($data['create_date'], 'd/m/Y');
            $cDate = $data['create_date'];
        }
        if (isset($data['email'])) {
            $condition['Vinhmsbookings.email LIKE'] = '%' . $data['email'] . '%';
            $email = $data['email'];
        }
        if (isset($data['phone'])) {
            $condition['Vinhmsbookings.phone LIKE'] = '%' . $data['phone'] . '%';
            $phone = $data['phone'];
        }

        $paginate = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments', 'Users'])->where($condition)
            ->where(function (QueryExpression $exp, Query $query) use ($data) {
                // Use add() to add multiple conditions for the same field.
                if (isset($data['code'])) {
                    $code = $query->newExpr()->or(['Vinhmsbookings.code LIKE' => '%' . $data['code'] . '%'])
                        ->add(['Vinhmsbookings.reservation_id LIKE' => '%' . $data['code'] . '%']);
                } else {
                    $code = null;
                }
                if (isset($data['search']) && $data['search']) {
                    $search = $query->newExpr()->or(['Users.screen_name LIKE' => '%' . $data['search'] . '%'])
                        ->add(['Hotels.name LIKE' => '%' . $data['search'] . '%'])
                        ->add(['Vinhmsbookings.sur_name LIKE' => '%' . $data['search'] . '%'])
                        ->add(['Vinhmsbookings.first_name LIKE' => '%' . $data['search'] . '%']);
                } else {
                    $search = null;
                }

                if ($code || $search) {
                    return $exp->or([
                        $query->newExpr()->and([$code, $search])
                    ]);
                } else {
                    return $exp;
                }
            })->order(
                [
                    'Vinhmsbookings.created' => 'DESC'
                ]);
        if (isset($data['code'])) {
            $code = $data['code'];
        }
        if (isset($data['search']) && $data['search']) {
            $keyword = $data['search'];
        }
        $bookings = $this->paginate($paginate);

        $this->set(compact('bookings', 'data', 'agencyPay', 'payHotel', 'keyword', 'sDate', 'eDate', 'cDate', 'code', 'email', 'phone'));
    }

    public function indexTestMail()
    {
        $this->paginate = [
            'limit' => 12
        ];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $keyword = '';
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $condition = [];
        $condition[]['OR'] = [
            'Bookings.sale_id' => $this->Auth->user('id'),
            [
                'Bookings.sale_id' => 0,
                'Bookings.user_id' => 0,
            ]
        ];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
//        if(isset($data['start_date']) && $data['start_date'] != null){
//            $condition['Bookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
//            $sDate = $data['start_date'];
//        }
//        if(isset($data['end_date']) && $data['end_date'] != null){
//            $condition['Bookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
//            $eDate = $data['end_date'];
//        }
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition['OR'] = [
                'code LIKE' => '%' . $keyword . '%',
                'Users.screen_name LIKE' => '%' . $keyword . '%',
                'Users.username LIKE' => '%' . $keyword . '%',
                'Hotels.name LIKE' => '%' . $keyword . '%',
                'LandTours.name LIKE' => '%' . $keyword . '%',
                'Vouchers.name LIKE' => '%' . $keyword . '%',
                'HomeStays.name LIKE' => '%' . $keyword . '%',
                'full_name LIKE' => '%' . $keyword . '%',
                'hotel_code LIKE' => '%' . $keyword . '%'
            ];
        }
        $paginate = $this->Bookings->find()->contain(['Users', 'HomeStays', 'Vouchers', 'LandTours', 'Hotels'])->where($condition)->order(['Bookings.created' => 'DESC']);
        $bookings = $this->paginate($paginate);
        $this->set(compact('bookings', 'data', 'agencyPay', 'payHotel', 'keyword', 'sDate', 'eDate'));
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
        $this->loadModel('Payments');
        $this->loadModel('BookingLogs');
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Hotels', 'Vouchers', 'HomeStays', 'LandTours', 'Hotels.Locations', 'Vouchers.Hotels', 'Vouchers.Hotels.Locations', 'LandTours.Destinations', 'HomeStays.Locations', 'BookingRooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'BookingRooms.Rooms', 'BookingSurcharges']
        ]);

        $this->set('booking', $booking);
        $payment = $this->Payments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
        $this->set('payment', $payment);

        $userLogs = $this->Auth->user();
        $this->set('userLogs', $userLogs);
        $bookingLogs = $this->BookingLogs->find()
            ->join([
                'u' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'u.id = BookingLogs.user_id',
                ]
            ])
            ->where([
                'booking_id' => $booking->id,
                'code' => $booking->code
            ])
            ->select(['u.screen_name', 'u.role_id', 'BookingLogs.id',  'BookingLogs.comment', 'BookingLogs.title', 'BookingLogs.created'])
            ->toArray();
        $this->set('bookingLogs', $bookingLogs);
    }

    public function viewVin($id = null)
    {
        $this->loadModel('Vinpayments');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('BookingLogs');
        $booking = $this->Vinhmsbookings->get($id, [
            'contain' => ['VinhmsbookingRooms', 'Hotels', 'Vinpayments', 'Users', 'Hotels.Locations']
        ]);

        $listRoom = [];
        foreach ($booking->vinhmsbooking_rooms as $room) {
            if (!isset($listRoom[$room->room_index])) {
                $listRoom[$room->room_index]['vinhms_name'] = $room['vinhms_name'];
                $listRoom[$room->room_index]['num_adult'] = $room['num_adult'];
                $listRoom[$room->room_index]['num_kid'] = $room['num_kid'];
                $listRoom[$room->room_index]['num_child'] = $room['num_child'];
                $listRoom[$room->room_index]['packages'][] = $room;
            } else {
                $listRoom[$room->room_index]['packages'][] = $room;
            }
        }
        $booking->vinhmsbooking_rooms = $listRoom;

        $this->set('booking', $booking);
        $payment = $this->Vinpayments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
        $this->set('payment', $payment);
        $userLogs = $this->Auth->user();
        $this->set('userLogs', $userLogs);
        $bookingLogs = $this->BookingLogs->find()
            ->join([
                'u' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'u.id = BookingLogs.user_id',
                ]
            ])
            ->where([
                'booking_id' => $booking->id,
                'code' => $booking->code
            ])
            ->select(['u.screen_name', 'u.role_id', 'BookingLogs.id',  'BookingLogs.comment', 'BookingLogs.title', 'BookingLogs.created'])
            ->toArray();
        $this->set('bookingLogs', $bookingLogs);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
//    public function add()
//    {
//        $this->loadModel('Users');
//        $status = [
//            '0' => 'Chưa thanh toán',
//            '1' => 'Đã thanh toán'
//        ];
//        $booking = $this->Bookings->newEntity();
//        if ($this->request->is('post')) {
//            $data = $this->request->getData();
//            $item_id = $data['item_id'];
//            $date_array = explode(' - ', $data['reservation']);
//            $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
//            $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');
//            switch ($data['type']) {
//                case HOMESTAY:
//                    $dataBooking = $this->_buildSaveHomestay($data);
//                    break;
//                case VOUCHER:
//                    $dataBooking = $this->_buildSaveVoucher($data);
//                    break;
//                case LANDTOUR:
//                    $dataBooking = $this->_buildSaveLandtour($data);
//                    break;
//                case HOTEL:
//                    $dataBooking = $this->_buildSaveHotel($data);
//                    break;
//            }
//            if ($dataBooking['success']) {
//                $dataBooking['data']['user_id'] = $this->Auth->user('id');
//                $booking = $this->Bookings->patchEntity($booking, $dataBooking['data']);
//                if ($this->Bookings->save($booking)) {
//                    $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($booking->id, 9, '0', STR_PAD_LEFT)]);
//                    $this->Bookings->save($booking);
//                        $this->Flash->success(__('The booking has been saved.'));
//
//                    return $this->redirect(['action' => 'index']);
//                }
//                $this->Flash->error(__('The booking could not be saved. Please, try again.'));
//            } else {
//                $this->Flash->error(__($dataBooking['message']));
//            }
//        }
//        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
//        $object_types = [
//            HOMESTAY => 'Homestay',
//            VOUCHER => 'Voucher',
//            LANDTOUR => 'Land Tour',
//            HOTEL => 'Hotel'
//        ];
//        $querys = $this->Users->find()->where(['role_id' => 3 , 'parent_id' => $this->Auth->user('parent_id')])->toArray();
//        $objects = [];
//        foreach ($querys as $query){
//            $objects[$query['id']] = $query['screen_name'];
//        }
//        $this->set(compact('booking', 'users', 'combos','objects','item_id', 'object_types', 'status'));
//    }

    public function addNew()
    {
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->loadModel('Rooms');
        $booking_type = [
            SYSTEM_BOOKING => 'Booking thuộc hệ thống',
        ];
        $status = [
            '0' => 'Chưa thanh toán',
            '1' => 'Đã thanh toán'
        ];
        $method = [
            CUSTOMER_PAY => 'Khách hàng chuyển trực tiếp',
            AGENCY_PAY => 'CTV sẽ thu tiền hộ'
        ];
        $booking = $this->Bookings->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $isAllow = true;
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople < ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        $isAllow = false;
                        $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.'));
                        break;
                        if ($roomTotalMaxAdult < $booking_room['num_adult']) {
                            $isAllow = false;
                            $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người.'));
                            break;
                        }
                    }
                }
            }
            if ($isAllow) {
                $item_id = $data['item_id'];
//            $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
//            $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
                $data['sale_id'] = $this->Auth->user('id');
//            if (isset($data['adult_fee'])) {
//                $data['adult_fee'] = str_replace(',', '', $data['adult_fee']);
//            }
//            if (isset($data['children_fee'])) {
//                $data['children_fee'] = str_replace(',', '', $data['children_fee']);
//            }
//            if (isset($data['holiday_fee'])) {
//                $data['holiday_fee'] = str_replace(',', '', $data['holiday_fee']);
//            }
//            if (isset($data['other_fee'])) {
//                $data['other_fee'] = str_replace(',', '', $data['other_fee']);
//            }
//                dd($data);
                if (isset($data['payment_deadline'])) {
                    $data['payment_deadline'] = $this->Util->formatSQLDate($data['payment_deadline'], 'd/m/Y');
                }
                $data['customer_deposit'] = str_replace(',', '', $data['customer_deposit']);
                $data['is_send_notice'] = 1;
                if (isset($data['sale_discount'])) {
                    $data['sale_discount'] = str_replace(',', '', $data['sale_discount']);
                }
                if (isset($data['agency_discount'])) {
                    $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                }
                if ($data['booking_type'] == SYSTEM_BOOKING) {
                    switch ($data['type']) {
                        case HOMESTAY:
                            $dataBooking = $this->_buildSaveHomestay($data);
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
                    if ($dataBooking['data'] != null) {
                        $dataBooking['data']['sale_discount'] = '';
                        if ($dataBooking['data']['sale_discount'] == '') {
                            $dataBooking['data']['sale_discount'] = 0;
                        }
                        $dataBooking['data']['agency_discount'] = '';
                        if ($dataBooking['data']['agency_discount'] == '') {
                            $dataBooking['data']['agency_discount'] = 0;
                        }
                        if ($dataBooking['data']['type'] == LANDTOUR) {
                            if ($dataBooking['data']['payment_method'] == AGENCY_PAY) {
                                $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                                $dataBooking['data']['revenue'] = 0;
                            }
                            if ($dataBooking['data']['payment_method'] == MUSTGO_DEPOSIT) {
                                $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                                $dataBooking['data']['revenue'] = ($dataBooking['data']['mustgo_deposit'] + $dataBooking['data']['customer_deposit']) - $dataBooking['data']['price'];
                            }
                            $dataBooking['data']['price'] += $dataBooking['data']['sale_discount'];
                            $dataBooking['data']['revenue'] -= $dataBooking['data']['agency_discount'];
                        } else {
                            $dataBooking['data']['sale_revenue'] = isset($dataBooking['data']['sale_revenue']) ? $dataBooking['data']['sale_revenue'] + $dataBooking['data']['sale_discount'] : $dataBooking['data']['sale_discount'];
                            if (isset($dataBooking['sale_id']) && isset($dataBooking['user_id']) && $dataBooking['sale_id'] != $dataBooking['user_id']) {
                                $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                                $dataBooking['data']['revenue'] = 0;
                                $dataBooking['data']['payment_method'] = AGENCY_PAY;
                            } else {
                                $dataBooking['data']['payment_method'] = AGENCY_PAY;
                            }
                        }
                        $dataBooking['data']['price'] -= $dataBooking['data']['agency_discount'];
                        $dataBooking['data']['status'] = 0;
                        if (isset($dataBooking['errors']) && $dataBooking['errors']) {
                            $dataBooking['success'] = false;
                            $dataBooking['message'] = $dataBooking['errors']['incorrect_info']['message'];

                        }
                    }
                } else if ($data['booking_type'] == ANOTHER_BOOKING) {
                    $dataBooking = $this->_buildSaveAnother($data);
                }

//                dd($dataBooking['success']);
                if ($dataBooking['success']) {
                    if (!isset($booking['data']['creator_type'])) {
                        $booking['data']['creator_type'] = 1;
                    }
                    $booking = $this->Bookings->patchEntity($booking, $dataBooking['data']);
                    if ($this->Bookings->save($booking)) {
                        $newBooking = $this->Bookings->get($booking->id);
                        if ($booking->type != LANDTOUR) {
                            $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                        } else {
                            $booking = $this->Bookings->patchEntity($booking, ['code' => "MPQ" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                        }
                        $this->Bookings->save($booking);

                        $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
                        if (!$payment) {
                            $payment = $this->Payments->newEntity();
                            $paymentData['booking_id'] = $booking->id;
                        }
                        if (isset($data['payment_type'])) {
                            $paymentData['type'] = $data['payment_type'];
                        }
                        if (isset($data['payment_invoice'])) {
                            $paymentData['invoice'] = $data['payment_invoice'];
                        }
                        if (isset($data['payment_invoice_information'])) {
                            $paymentData['invoice_information'] = $data['payment_invoice_information'];
                        }
                        if (isset($data['payment_address'])) {
                            $paymentData['address'] = $data['payment_address'];
                        }
                        if (isset($data['media'])) {
                            $paymentData['images'] = $data['media'];
                        }
                        if (isset($data['pay_object'])) {
                            $paymentData['pay_object'] = $data['pay_object'];
                            if ($data['pay_object'] == PAY_HOTEL) {
                                $paymentData['check_type'] = $data['check_type'];
                                $paymentData['partner_information'] = '';
                            } else {
                                $paymentData['check_type'] = 0;
                                $paymentData['partner_information'] = [
                                    'name' => $data['partner_name'],
                                    'number' => $data['partner_number'],
                                    'bank' => $data['partner_bank'],
                                    'email' => $data['partner_email']
                                ];
                                $paymentData['partner_information'] = json_encode($paymentData['partner_information'], JSON_UNESCAPED_UNICODE);
                            }
                        }
                        $payment = $this->Payments->patchEntity($payment, $paymentData);
                        $this->Payments->save($payment);
                        $this->Flash->success(__('The booking has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The booking could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__($dataBooking['message']));
                }
            }
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        if ($this->Auth->user('role_id') == 5) {
            $object_types = [
                LANDTOUR => 'Landtour'
            ];
            $querys = $this->Users->find()->where(['role_id' => 3, 'landtour_parent_id' => $this->Auth->user('id')])->toArray();
        } elseif ($this->Auth->user('role_id') == 2) {
            $object_types = [
                HOMESTAY => 'Homestay',
                VOUCHER => 'Voucher',
                HOTEL => 'Hotel'
            ];
            $querys = $this->Users->find()->where(['role_id' => 3, 'parent_id' => $this->Auth->user('id')])->toArray();
        }

        $objects = [];
        $objects[$this->Auth->user('id')] = 'Khách lẻ';
        foreach ($querys as $query) {
            $objects[$query['id']] = $query['screen_name'];
        }

        $querysV2 = $this->Users->find()->where(['role_id' => 3])->toArray();
        $objectsV2 = [];
        $objectsV2[$this->Auth->user('id')] = 'Khách lẻ';
        foreach ($querysV2 as $queryV2) {
            $objectsV2[$queryV2['id']] = $queryV2['screen_name'];
        }
        $payment = null;
        $list_images = '';
        $this->set(compact('booking', 'users', 'objects', 'object_types', 'booking_type', 'objectsV2', 'status', 'method', 'payment', 'list_images'));
    }

    public function addNewVinpearl()
    {
        $this->loadModel('Hotels');
        $this->loadModel('Users');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('VinhmsbookingRooms');
        $this->loadModel('Vinpayments');
        $listVinpearl = $this->Hotels->find()->where(['is_vinhms' => 1]);
        $qs = $this->Users->find()->where(['parent_id' => $this->Auth->user('id'), 'role_id' => 3]);
        $listAgency = [];
        $listAgency[$this->Auth->user('id')] = "Khách lẻ";
        foreach ($qs as $q) {
            $listAgency[$q->id] = $q->screen_name;
        }
        $listVinpearlHotel = [];
        foreach ($listVinpearl as $vinpearl) {
            $listVinpearlHotel[$vinpearl->id] = $vinpearl->name;
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $booking = $this->Vinhmsbookings->newEntity();
            $dateRange = explode(' - ', $data['daterange']);
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
            if (isset($data['agency_discount'])) {
                $data['agency_discount'] = str_replace(' ', '', $data['agency_discount']);
                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                $data['agency_discount'] = str_replace('.', '', $data['agency_discount']);
            }
            if (isset($data['change_price'])) {
                $data['change_price'] = str_replace(' ', '', $data['change_price']);
                $data['change_price'] = str_replace(',', '', $data['change_price']);
                $data['change_price'] = str_replace('.', '', $data['change_price']);
            }
            $bookingData = [
                'user_id' => $data['user_id'],
                'sale_id' => $this->Auth->user('id'),
                'hotel_id' => $data['hotel_id'],
                'first_name' => $data['first_name'],
                'sur_name' => $data['sur_name'],
                'nationality' => $data['nationality'],
                'nation' => $data['nation'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'note' => $data['note'],
                'change_price' => $data['change_price'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'vin_information' => json_encode($data['vin_information'], JSON_UNESCAPED_SLASHES),
                'sale_discount' => isset($data['sale_discount']) ? $data['sale_discount'] : 0,
                'agency_discount' => isset($data['agency_discount']) ? $data['agency_discount'] : 0,
                'status' => 1,
                'creator_type' => 1
            ];
            $booking = $this->Vinhmsbookings->patchEntity($booking, $bookingData);
            $this->Vinhmsbookings->save($booking);

            $booking = $this->Vinhmsbookings->patchEntity($booking, ['code' => "MVP" . str_pad($booking->id, 9, '0', STR_PAD_LEFT)]);
            $this->Vinhmsbookings->save($booking);

            $totalPrice = 0;
            $totalRevenue = 0;
            $totalSaleRevenue = 0;
            foreach ($data['vin_room'] as $k => $room) {
                foreach ($room['package'] as $kP => $package) {
                    $vinBookingRoom = $this->VinhmsbookingRooms->newEntity();
                    $singleRoomData = [
                        'room_index' => $k,
                        'vinhms_name' => $room['name'],
                        'vinhmsbooking_id' => $booking->id,
                        'vinhms_package_id' => $package['package_id'],
                        'vinhms_package_code' => $package['code'],
                        'vinhms_package_name' => $package['package_name'],
                        'vinhms_room_id' => $room['id'],
                        'vinhms_rateplan_id' => $package['rateplan_id'],
                        'vinhms_allotment_id' => $package['allotment_id'],
                        'vinhms_room_type_code' => $room['room_type_code'],
                        'vinhms_rateplan_code' => $package['rateplan_code'],
                        'room_id' => $room['id'],
                        'checkin' => date('Y-m-d', strtotime(str_replace('/', '-', $package['start_date']))),
                        'checkout' => date('Y-m-d', strtotime(str_replace('/', '-', $package['end_date']))),
                        'num_adult' => $room['num_adult'],
                        'num_kid' => $room['num_kid'],
                        'num_child' => $room['num_child'],
                        'customer_note' => '',
                        'detail_by_day' => '',
                        'price' => str_replace(',', '', $package['default_price']),
                        'revenue' => $package['revenue'],
                        'sale_revenue' => $package['sale_revenue']
                    ];
                    $totalPrice += str_replace(',', '', $package['price']);
                    $totalRevenue += $package['revenue'];
                    $totalSaleRevenue += $package['sale_revenue'];
                    $vinBookingRoom = $this->VinhmsbookingRooms->patchEntity($vinBookingRoom, $singleRoomData);
                    $this->VinhmsbookingRooms->save($vinBookingRoom);
                }
            }

            $booking = $this->Vinhmsbookings->patchEntity($booking, [
                'price' => $totalPrice + $data['change_price'],
                'price_default' => $totalPrice + $data['change_price'],
                'revenue' => $totalRevenue,
                'sale_revenue' => $totalSaleRevenue,
                'sale_revenue_default' => $totalSaleRevenue,
            ]);
            $this->Vinhmsbookings->save($booking);

            if (isset($data['payment']) && !empty($data['payment']) && (count($data['payment'])) > 1) {
                $vinpayment = $this->Vinpayments->newEntity();
                $dataPayment = [
                    'booking_id' => $booking->id,
                    'type' => $data['payment']['payment_type'],
                    'invoice' => $data['payment']['payment_invoice'],
                    'invoice_information' => isset($data['payment']['payment_invoice_information']) ? $data['payment']['payment_invoice_information'] : '',
                    'images' => isset($data['media']) ? $data['media'] : ''
                ];
                $vinpayment = $this->Vinpayments->patchEntity($vinpayment, $dataPayment);
                $this->Vinpayments->save($vinpayment);
            }

            $this->redirect(['controller' => 'bookings', 'action' => 'indexVin']);
        }
        $this->set(compact('listAgency', 'listVinpearlHotel'));
    }

    public function editVin($id)
    {
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Users');
        $this->loadModel('Hotels');
        $this->loadModel('Vinhmsallotments');
        $this->loadModel('Vinrooms');
        $this->loadModel('VinhmsbookingRooms');
        $this->loadModel('Vinpayments');
        $vinBooking = $this->Vinhmsbookings->get($id, ['contain' => ['VinhmsbookingRooms', 'Vinpayments', 'Hotels']]);
        $hotel = $this->Hotels->get($vinBooking->hotel_id);
        $listVinpearl = $this->Hotels->find()->where(['is_vinhms' => 1]);
        $qs = $this->Users->find()->where(['parent_id' => $this->Auth->user('id'), 'role_id' => 3]);
        $listAgency = [];
        $listAgency[$this->Auth->user('id')] = "Khách lẻ";
        foreach ($qs as $q) {
            $listAgency[$q->id] = $q->screen_name;
        }
        $listVinpearlHotel = [];
        foreach ($listVinpearl as $vinpearl) {
            $listVinpearlHotel[$vinpearl->id] = $vinpearl->name;
        }
        $numAdult = $numChild = $numKid = 0;
        $vinBookingRooms = [];
        foreach ($vinBooking->vinhmsbooking_rooms as $room) {
            if (!isset($vinBookingRooms[$room->room_index])) {
                $numAdult += $room->num_adult;
                $numChild += $room->num_child;
                $numKid += $room->num_child;
                $vinBookingRooms[$room->room_index] = [
                    'vinhms_name' => $room->vinhms_name,
                    'vinhms_room_id' => $room->vinhms_room_id,
                    'vinhms_room_type_code' => $room->vinhms_room_type_code,
                    'room_id' => $room->room_id,
                    'num_adult' => $room->num_adult,
                    'num_kid' => $room->num_kid,
                    'num_child' => $room->num_child,
                    'packages' => [],
                    'room_price' => $room->price + $room->revenue + $room->sale_revenue
                ];
                $vinBookingRooms[$room->room_index]['packages'][] = [
                    'vinhms_package_id' => $room->vinhms_package_id,
                    'vinhms_package_code' => $room->vinhms_package_code,
                    'vinhms_package_name' => $room->vinhms_package_name,
                    'vinhms_rateplan_id' => $room->vinhms_rateplan_id,
                    'vinhms_allotment_id' => $room->vinhms_allotment_id,
                    'vinhms_room_type_code' => $room->vinhms_room_type_code,
                    'vinhms_rateplan_code' => $room->vinhms_rateplan_code,
                    'price' => $room->price,
                    'revenue' => $room->revenue,
                    'sale_revenue' => $room->sale_revenue,
                    'checkin' => date('d-m-Y', strtotime($room->checkin)),
                    'checkout' => date('d-m-Y', strtotime($room->checkout))
                ];
            } else {
                $vinBookingRooms[$room->room_index]['packages'][] = [
                    'vinhms_package_id' => $room->vinhms_package_id,
                    'vinhms_package_code' => $room->vinhms_package_code,
                    'vinhms_package_name' => $room->vinhms_package_name,
                    'vinhms_rateplan_id' => $room->vinhms_rateplan_id,
                    'vinhms_allotment_id' => $room->vinhms_allotment_id,
                    'vinhms_room_type_code' => $room->vinhms_room_type_code,
                    'vinhms_rateplan_code' => $room->vinhms_rateplan_code,
                    'price' => $room->price,
                    'revenue' => $room->revenue,
                    'sale_revenue' => $room->sale_revenue,
                    'checkin' => date('d-m-Y', strtotime($room->checkin)),
                    'checkout' => date('d-m-Y', strtotime($room->checkout)),
                ];
                $vinBookingRooms[$room->room_index]['room_price'] += $room->price + $room->revenue + $room->sale_revenue;
            }
        }
        $numRoom = count($vinBookingRooms);
        $vinBooking->vinhmsbooking_rooms = $vinBookingRooms;
        $userId = $vinBooking->user_id;

        $dateBooking = date('d/m/Y', strtotime($vinBooking->start_date)) . ' - ' . date('d/m/Y', strtotime($vinBooking->end_date));
        $startDate = date('Y-m-d', strtotime($vinBooking->start_date));
        $endDate = date('Y-m-d', strtotime($vinBooking->end_date));

        $dateDiff = date_diff(date_create($startDate), date_create($endDate));
        $singleVinChooseRoom = [];
        $listAllotments = $this->Vinhmsallotments->find()
            ->where([
                'hotel_id' => $vinBooking->hotel->id,
            ]);
        $allotmentRoom = [];
        foreach ($listAllotments as $k => $singleRoom) {
            if (!isset($allotmentRoom[$singleRoom->code])) {
                $allotmentRoom[$singleRoom->code][$singleRoom->vinroom_code] = [
                    'sale_revenue_type' => $singleRoom->sale_revenue_type,
                    'sale_revenue' => $singleRoom->sale_revenue,
                    'revenue_type' => $singleRoom->revenue_type,
                    'revenue' => $singleRoom->revenue,
                ];
            } else {
                $allotmentRoom[$singleRoom->code][$singleRoom->vinroom_code] = [
                    'sale_revenue_type' => $singleRoom->sale_revenue_type,
                    'sale_revenue' => $singleRoom->sale_revenue,
                    'revenue_type' => $singleRoom->revenue_type,
                    'revenue' => $singleRoom->revenue,
                ];
            }
        }
        foreach ($vinBooking->vinhmsbooking_rooms as $singleDataRoom) {
            $data = [
                "arrivalDate" => $startDate,
                "departureDate" => $endDate,
                "numberOfRoom" => 1,
                "propertyIds" => [$vinBooking->hotel->vinhms_code],
                "roomOccupancy" => []
            ];
            empty($data['roomOccupancy']);
            $roomOccupancy = [
                'numberOfAdult' => $singleDataRoom['num_adult'],
                'otherOccupancies' => [
                    [
                        'otherOccupancyRefCode' => 'child',
                        'quantity' => $singleDataRoom['num_child']
                    ],
                    [
                        'otherOccupancyRefCode' => 'infant',
                        'quantity' => $singleDataRoom['num_kid']
                    ]
                ]
            ];
            $data['roomOccupancy'] = $roomOccupancy;
            $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
            $listRoom = [];
            if (isset($dataApi['isSuccess'])) {
                if (!empty($dataApi['data']['rates'])) {
                    foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $k => $singleRoom) {
                        $listRoom[$singleRoom['id']] = [
                            'information' => [
                                'name' => $singleRoom['name'],
                            ]
                        ];
                    }
                }

                if (!empty($dataApi['data']['rates'])) {
                    foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
                        $hasSpecialPackage = false;
                        if (isset($ratePackage['rateAvailablity']['allotments'][0]) && isset($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']])) {
                            $hasSpecialPackage = true;
                        }

                        if ($hotel->price_agency_type == 0) {
                            $ratePackage['trippal_price'] = $hotel->price_agency * $dateDiff->days;
                        } else {
                            $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $hotel->price_agency / 100);
                        }
                        if ($hotel->price_agency_type == 0) {
                            $ratePackage['customer_price'] = $hotel->price_customer * $dateDiff->days;
                        } else {
                            $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $hotel->price_customer / 100);
                        }

                        $vinRoom = $this->Vinrooms->find()->where(['vin_code' => $ratePackage['roomTypeID'], 'hotel_id' => $hotel->id])->first();
                        if ($vinRoom) {
                            if ($vinRoom->trippal_price_type == 0) {
                                $ratePackage['trippal_price'] = $vinRoom->trippal_price != 0 ? $vinRoom->trippal_price * $dateDiff->days : true;
                            } else {
                                $ratePackage['trippal_price'] = $vinRoom->trippal_price != 0 ? intval(intval($ratePackage['totalAmount']['amount']['amount']) * $vinRoom->trippal_price / 100) : true;
                            }
                            if ($vinRoom->customer_price_type == 0) {
                                $ratePackage['customer_price'] = $vinRoom->customer_price != 0 ? $vinRoom->customer_price * $dateDiff->days : true;
                            } else {
                                $ratePackage['customer_price'] = $vinRoom->customer_price != 0 ? intval(intval($ratePackage['totalAmount']['amount']['amount']) * $vinRoom->customer_price / 100) : true;
                            }
                        }
                        if ($hasSpecialPackage) {
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] != 0) {
                                if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue_type'] == 0) {
                                    $ratePackage['trippal_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] * $dateDiff->days;
                                } else {
                                    $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] / 100);
                                }
                            }
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] != 0) {
                                if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue_type'] == 0) {
                                    $ratePackage['customer_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] * $dateDiff->days;
                                } else {
                                    $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] / 100);
                                }
                            }
                        }

                        if (isset($listRoom[$ratePackage['roomTypeID']])) {
                            $tmpPrice = $ratePackage['rateAvailablity']['amount'] + $ratePackage['trippal_price'] + $ratePackage['customer_price'];
                            $listRoom[$ratePackage['roomTypeID']]['package'][] = $ratePackage;
                        }
                    }
                }
            }
            $listRoom = array_reverse($listRoom);
            $singleVinChooseRoom[] = $listRoom;
        }
        $paymentImages = [];
        if ($vinBooking->vinpayment && $vinBooking->vinpayment->images) {
            $medias = json_decode($vinBooking->vinpayment->images, true);
            if (is_array($medias)) {
                foreach ($medias as $media) {
                    if (file_exists($media)) {
                        $obj['name'] = basename($media);
                        $obj['size'] = filesize($media);
                        $paymentImages[] = $obj;
                    }
                }
            }
        }
        $paymentImages = json_encode($paymentImages);
        $this->set(compact('vinBooking', 'listAgency', 'listVinpearlHotel', 'numAdult', 'numChild', 'numKid', 'numRoom', 'userId', 'singleVinChooseRoom', 'dateBooking', 'paymentImages'));

        if ($this->request->is('post')) {
            $listDeleteRooms = $this->VinhmsbookingRooms->find()->where(['vinhmsbooking_id' => $vinBooking->id]);
            foreach ($listDeleteRooms as $singleDelteRoom) {
                $this->VinhmsbookingRooms->delete($singleDelteRoom);
            }
            $data = $this->request->getData();
            $booking = $this->Vinhmsbookings->get($id);
            if (!$booking) {
                $booking = $this->Vinhmsbookings->newEntity();
            }
            $dateRange = explode(' - ', $data['daterange']);
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
            if (isset($data['agency_discount'])) {
                $data['agency_discount'] = str_replace(' ', '', $data['agency_discount']);
                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                $data['agency_discount'] = str_replace('.', '', $data['agency_discount']);
            }
            if (isset($data['change_price'])) {
                $data['change_price'] = str_replace(' ', '', $data['change_price']);
                $data['change_price'] = str_replace(',', '', $data['change_price']);
                $data['change_price'] = str_replace('.', '', $data['change_price']);
            }
            $bookingData = [
                'user_id' => $data['user_id'],
                'sale_id' => $this->Auth->user('id'),
                'hotel_id' => $data['hotel_id'],
                'first_name' => $data['first_name'],
                'sur_name' => $data['sur_name'],
                'nationality' => $data['nationality'],
                'nation' => $data['nation'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'note' => $data['note'],
                'change_price' => $data['change_price'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'vin_information' => json_encode($data['vin_information'], JSON_UNESCAPED_SLASHES),
                'sale_discount' => isset($data['sale_discount']) ? $data['sale_discount'] : 0,
                'agency_discount' => isset($data['agency_discount']) ? $data['agency_discount'] : 0,
                'status' => 1,
                'creator_type' => 1
            ];
            $booking = $this->Vinhmsbookings->patchEntity($booking, $bookingData);
            $this->Vinhmsbookings->save($booking);

            $totalPrice = 0;
            $totalRevenue = 0;
            $totalSaleRevenue = 0;
            foreach ($data['vin_room'] as $k => $room) {
                foreach ($room['package'] as $kP => $package) {
                    $vinBookingRoom = $this->VinhmsbookingRooms->newEntity();
                    $singleRoomData = [
                        'room_index' => $k,
                        'vinhms_name' => $room['name'],
                        'vinhmsbooking_id' => $booking->id,
                        'vinhms_package_id' => $package['package_id'],
                        'vinhms_package_code' => $package['package_code'],
                        'vinhms_package_name' => $package['package_name'],
                        'vinhms_room_id' => $package['room_id'],
                        'vinhms_rateplan_id' => $package['rateplan_id'],
                        'vinhms_allotment_id' => $package['allotment_id'],
                        'vinhms_room_type_code' => $package['room_type_code'],
                        'vinhms_rateplan_code' => $package['rateplan_code'],
                        'room_id' => $package['room_id'],
                        'checkin' => date('Y-m-d', strtotime($package['start_date'])),
                        'checkout' => date('Y-m-d', strtotime($package['end_date'])),
                        'num_adult' => $room['num_adult'],
                        'num_kid' => $room['num_kid'],
                        'num_child' => $room['num_child'],
                        'customer_note' => '',
                        'detail_by_day' => '',
                        'price' => str_replace(',', '', $package['default_price']),
                        'revenue' => $package['revenue'],
                        'sale_revenue' => $package['sale_revenue']
                    ];
                    $totalPrice += str_replace(',', '', $room['price']);
                    $totalRevenue += $package['revenue'];
                    $totalSaleRevenue += $package['sale_revenue'];
                    $vinBookingRoom = $this->VinhmsbookingRooms->patchEntity($vinBookingRoom, $singleRoomData);
                    $this->VinhmsbookingRooms->save($vinBookingRoom);
                }
            }

            $booking = $this->Vinhmsbookings->patchEntity($booking, ['price' => $totalPrice + $data['change_price'], 'revenue' => $totalRevenue, 'sale_revenue' => $totalSaleRevenue]);
            $this->Vinhmsbookings->save($booking);

            if (isset($data['payment']) && !empty($data['payment']) && (count($data['payment'])) > 1) {
                $vinpayment = $this->Vinpayments->find()->where(['booking_id' => $booking->id])->first();
                if (!$vinpayment) {
                    $vinpayment = $this->Vinpayments->newEntity();
                }
                $dataPayment = [
                    'booking_id' => $booking->id,
                    'type' => $data['payment']['payment_type'],
                    'invoice' => $data['payment']['payment_invoice'],
                    'invoice_information' => isset($data['payment']['payment_invoice_information']) ? $data['payment']['payment_invoice_information'] : '',
                    'images' => isset($data['media']) ? $data['media'] : ''
                ];
                $vinpayment = $this->Vinpayments->patchEntity($vinpayment, $dataPayment);
                $this->Vinpayments->save($vinpayment);
            }

            $this->redirect(['controller' => 'bookings', 'action' => 'indexVin']);
        }
    }

    public function editPriceVin($id)
    {
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Vinpayments');
        $vinBooking = $this->Vinhmsbookings->get($id);
        $vinPayment = $this->Vinpayments->find()->where(['booking_id' => $id])->first();

        $listPaymentImages = null;
        if ($vinPayment) {
            $listPaymentImages = $vinPayment->images;
        }
        $this->set(compact('vinBooking', 'vinPayment', 'listPaymentImages'));

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['customer_pay'] = str_replace(',', '', $data['customer_pay']);
            $data['hotel_pay'] = str_replace(',', '', $data['hotel_pay']);

            $saleRevenue = $data['customer_pay'] - $data['hotel_pay'];
            $price = $vinBooking->revenue + $data['customer_pay'];

            $vinBooking = $this->Vinhmsbookings->patchEntity($vinBooking, ['price' => $price, 'sale_revenue' => $saleRevenue]);
            $this->Vinhmsbookings->save($vinBooking);

            if (isset($data['payment']) && !empty($data['payment']) && (count($data['payment'])) > 1) {
                if (!$vinPayment) {
                    $vinPayment = $this->Vinpayments->newEntity();
                }
                $dataPayment = [
                    'booking_id' => $vinBooking->id,
                    'type' => $data['payment']['payment_type'],
                    'invoice' => $data['payment']['payment_invoice'],
                    'invoice_information' => isset($data['payment']['payment_invoice_information']) ? $data['payment']['payment_invoice_information'] : '',
                    'images' => isset($data['media']) ? $data['media'] : ''
                ];
                $vinPayment = $this->Vinpayments->patchEntity($vinPayment, $dataPayment);
                $this->Vinpayments->save($vinPayment);
            }
            $this->redirect(['action' => 'indexVin']);
        }
    }

    public function chooseVinHotel($hotelId)
    {
        $testUrl = $this->viewVars['testUrl'];
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Hotels');
        $this->loadModel('Vinrooms');
        $this->loadModel('Vinhmsallotments');
        $data = $this->request->getQuery();
        $hotel = $this->Hotels->get($hotelId);
        $dataRoom = $data['vin_room'];
        $numRoom = count($dataRoom);
        $dateRange = explode(' - ', $data['daterange']);
        $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
        $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
        $userId = $data['user_id'];

        $dateDiff = date_diff(date_create($startDate), date_create($endDate));
        $singleVinChooseRoom = [];
        $listAllotments = $this->Vinhmsallotments->find()
            ->where([
                'hotel_id' => $hotel->id,
            ]);
        $allotmentRoom = [];
        foreach ($listAllotments as $k => $singleRoom) {
            if (!isset($allotmentRoom[$singleRoom->code])) {
                $allotmentRoom[$singleRoom->code][$singleRoom->vinroom_code] = [
                    'sale_revenue_type' => $singleRoom->sale_revenue_type,
                    'sale_revenue' => $singleRoom->sale_revenue,
                    'revenue_type' => $singleRoom->revenue_type,
                    'revenue' => $singleRoom->revenue,
                ];
            } else {
                $allotmentRoom[$singleRoom->code][$singleRoom->vinroom_code] = [
                    'sale_revenue_type' => $singleRoom->sale_revenue_type,
                    'sale_revenue' => $singleRoom->sale_revenue,
                    'revenue_type' => $singleRoom->revenue_type,
                    'revenue' => $singleRoom->revenue,
                ];
            }
        }
        foreach ($dataRoom as $singleDataRoom) {
            $data = [
                "arrivalDate" => $startDate,
                "departureDate" => $endDate,
                "numberOfRoom" => 1,
                "propertyIds" => [$hotel->vinhms_code],
                "roomOccupancy" => []
            ];
            empty($data['roomOccupancy']);
            $roomOccupancy = [
                'numberOfAdult' => $singleDataRoom['num_adult'],
                'otherOccupancies' => [
                    [
                        'otherOccupancyRefCode' => 'child',
                        'quantity' => $singleDataRoom['num_child']
                    ],
                    [
                        'otherOccupancyRefCode' => 'infant',
                        'quantity' => $singleDataRoom['num_kid']
                    ]
                ]
            ];
            $data['roomOccupancy'] = $roomOccupancy;
            $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
            $listRoom = [];
            if (isset($dataApi['isSuccess'])) {
                if (!empty($dataApi['data']['rates'])) {
                    foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $k => $singleRoom) {
                        $listRoom[$singleRoom['id']] = [
                            'information' => [
                                'name' => $singleRoom['name'],
                            ]
                        ];
                    }
                }

                if (!empty($dataApi['data']['rates'])) {
                    foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
                        $hasSpecialPackage = false;
                        $firstAllotment = $ratePackage['rateAvailablity']['allotments'][0];
                        foreach ($ratePackage['rateAvailablity']['allotments'] as $singleAllotmentCheck) {
                            if ($firstAllotment['quantity'] < $singleAllotmentCheck['quantity']) {
                                $firstAllotment = $singleAllotmentCheck;
                            }
                        }
                        $ratePackage['rateAvailablity']['allotments'][0] = $firstAllotment;
                        if (isset($ratePackage['rateAvailablity']['allotments'][0]) && isset($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']])) {
                            $hasSpecialPackage = true;
                        }

                        if ($hotel->price_agency_type == 0) {
                            $ratePackage['trippal_price'] = $hotel->price_agency * $dateDiff->days;
                        } else {
                            $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $hotel->price_agency / 100);
                        }
                        if ($hotel->price_agency_type == 0) {
                            $ratePackage['customer_price'] = $hotel->price_customer * $dateDiff->days;
                        } else {
                            $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $hotel->price_customer / 100);
                        }

                        $vinRoom = $this->Vinrooms->find()->where(['vin_code' => $ratePackage['roomTypeID'], 'hotel_id' => $hotel->id])->first();
                        if ($vinRoom) {
                            if ($vinRoom->trippal_price_type == 0) {
                                $ratePackage['trippal_price'] = $vinRoom->trippal_price != 0 ? $vinRoom->trippal_price * $dateDiff->days : true;
                            } else {
                                $ratePackage['trippal_price'] = $vinRoom->trippal_price != 0 ? intval(intval($ratePackage['totalAmount']['amount']['amount']) * $vinRoom->trippal_price / 100) : true;
                            }
                            if ($vinRoom->customer_price_type == 0) {
                                $ratePackage['customer_price'] = $vinRoom->customer_price != 0 ? $vinRoom->customer_price * $dateDiff->days : true;
                            } else {
                                $ratePackage['customer_price'] = $vinRoom->customer_price != 0 ? intval(intval($ratePackage['totalAmount']['amount']['amount']) * $vinRoom->customer_price / 100) : true;
                            }
                        }
                        if ($hasSpecialPackage) {
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] != 0) {
                                if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue_type'] == 0) {
                                    $ratePackage['trippal_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] * $dateDiff->days;
                                } else {
                                    $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] / 100);
                                }
                            }
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] != 0) {
                                if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue_type'] == 0) {
                                    $ratePackage['customer_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] * $dateDiff->days;
                                } else {
                                    $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] / 100);
                                }
                            }
                        }
                        $ratePackage['amount_left'] = $ratePackage['rateAvailablity']['allotments'][0]['quantity'];

                        if (isset($listRoom[$ratePackage['roomTypeID']])) {
                            $tmpPrice = $ratePackage['rateAvailablity']['amount'] + $ratePackage['trippal_price'] + $ratePackage['customer_price'];
                            $listRoom[$ratePackage['roomTypeID']]['package'][] = $ratePackage;
                        }
                    }
                }
            }
            $listRoom = array_reverse($listRoom);
            $singleVinChooseRoom[] = $listRoom;
        }
        $this->set(compact('hotel', 'numRoom', 'singleVinChooseRoom', 'dataRoom', 'userId'));
        $this->render('choose_vin_hotel')->getBody();
    }

    public function chooseVinRoom()
    {
        $response = ['success' => true, 'choose_vin_room' => null, 'input_vin_room' => null];
        $this->viewBuilder()->enableAutoLayout(false);
        $data = $this->request->getData();
        $id = $data['id'];
        $room_id = $data['room_id'];
        $price = $data['price'];
        $package_id = $data['package_id'];
        $rateplan_id = $data['rateplan_id'];
        $revenue = $data['revenue'];
        $saleRevenue = $data['saleRevenue'];
        $packageCode = $data['packageCode'];
        $packageName = $data['packageName'];
        $allotmentId = $data['allotmentId'];
        $roomTypeCode = $data['roomTypeCode'];
        $ratePlanCode = $data['ratePlanCode'];
        $defaultPrice = $data['defaultPrice'];
        $roomData = $data['roomData'];
        $hotelId = $data['hotelId'];
        $startDate = explode(' - ', $data['dateRange'])[0];
        $endDate = explode(' - ', $data['dateRange'])[1];
        $numAdult = $data['numAdult'];
        $numChild = $data['numChild'];
        $numKid = $data['numKid'];
        $this->set(compact('id', 'numAdult', 'numChild', 'numKid', 'roomData', 'hotelId', 'startDate', 'endDate', 'room_id', 'price', 'package_id', 'rateplan_id', 'revenue', 'saleRevenue', 'packageCode', 'packageName', 'allotmentId', 'roomTypeCode', 'ratePlanCode', 'defaultPrice'));
        $choRoomHtml = $this->render('choose_vin_room')->body();
        $inpRoomHtml = $this->render('input_vin_room')->body();
        $response['choose_vin_room'] = $choRoomHtml;
        $response['input_vin_room'] = $inpRoomHtml;

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function removePackageCalPrice()
    {
        $data = $this->request->getQuery();
        $this->viewBuilder()->enableAutoLayout(false);
        $currentRoomPrice = $data['currentRoomPrice'];
        $currentRoomPrice = str_replace(',', '', $currentRoomPrice);
        $currentRoomPrice = str_replace('.', '', $currentRoomPrice);

        $removePackagePrice = $data['removePackagePrice'];
        $removePackagePrice = str_replace(',', '', $removePackagePrice);
        $removePackagePrice = str_replace('.', '', $removePackagePrice);

        $removePackageRevenue = $data['removePackageRevenue'];
        $removePackageRevenue = str_replace(',', '', $removePackageRevenue);
        $removePackageRevenue = str_replace('.', '', $removePackageRevenue);

        $removePackageSaleRevenue = $data['removePackageSaleRevenue'];
        $removePackageSaleRevenue = str_replace(',', '', $removePackageRevenue);
        $removePackageSaleRevenue = str_replace('.', '', $removePackageSaleRevenue);

        $totalVinBookingPrice = $data['totalVinBookingPrice'];
        $totalVinBookingPrice = str_replace(',', '', $totalVinBookingPrice);
        $totalVinBookingPrice = str_replace('.', '', $totalVinBookingPrice);

        $totalVinBookingRevenue = $data['totalVinBookingRevenue'];
        $totalVinBookingRevenue = str_replace(',', '', $totalVinBookingRevenue);
        $totalVinBookingRevenue = str_replace('.', '', $totalVinBookingRevenue);


        $room_total = $currentRoomPrice - $removePackagePrice;
        $total_vin_booking_price = $totalVinBookingPrice - $removePackagePrice;
        $total_vin_booking_revenue = $totalVinBookingRevenue - $removePackageRevenue;
        $total_agency_pay_vin_booking = $total_vin_booking_price - $total_vin_booking_revenue;

        $response = [
            'success' => true,
            'room_total' => number_format($room_total),
            'total_vin_booking_price' => number_format($total_vin_booking_price),
            'total_vin_booking_revenue' => number_format($total_vin_booking_revenue),
            'total_agency_pay_vin_booking' => number_format($total_agency_pay_vin_booking),
        ];
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function inputRoomVin()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $keyword = $this->request->getQuery('room_number');
        $this->set(compact('keyword'));
        $this->render('input_room_vin')->getBody();
    }

    private function _buildSaveAnother($data)
    {
        $response = ['success' => false, 'message' => '', 'errors' => []];
        $data['price'] = str_replace(',', '', $data['price']);
        $data['default_price'] = str_replace(',', '', $data['default_price']);
        $data['sale_revenue'] = $data['price'] - $data['default_price'];
        $data['revenue'] = 0;
        if ($data['type'] == HOTEL) {
            if (isset($data['booking_rooms'])) {
                $minDate = $data['booking_rooms'][0]['start_date'];
                $maxDate = $data['booking_rooms'][0]['end_date'];
                $totalAmount = 0;
                foreach ($data['booking_rooms'] as $k => $bookingRoom) {
                    if (strtotime($bookingRoom['start_date'] <= strtotime($minDate))) {
                        $minDate = $bookingRoom['start_date'];
                    }
                    if (strtotime($bookingRoom['end_date'] >= strtotime($maxDate))) {
                        $maxDate = $bookingRoom['end_date'];
                    }
                    $data['booking_rooms'][$k]['start_date'] = $this->Util->formatSQLDate($bookingRoom['start_date'], 'd/m/Y');
                    $data['booking_rooms'][$k]['end_date'] = $this->Util->formatSQLDate($bookingRoom['end_date'], 'd/m/Y');
                    if (isset($bookingRoom['child_ages'])) {
                        $data['booking_rooms'][$k]['child_ages'] = json_encode($bookingRoom['child_ages'], JSON_UNESCAPED_UNICODE);
                    }
                    $totalAmount += $bookingRoom['num_room'];
                }
                $data['amount'] = $totalAmount;
                $data['start_date'] = $this->Util->formatSQLDate($minDate, 'd/m/Y');
                $data['end_date'] = $this->Util->formatSQLDate($maxDate, 'd/m/Y');
            } else {
                $response['message'] = 'Phải chọn ít nhất 1 hạng phòng';
                $response['errors']['booking_rooms'] = 'Phải chọn ít nhất 1 hạng phòng';
            }
        } else {
            if ($data['type'] == LANDTOUR) {
                $data['booking_landtour']['child_ages'] = json_encode($data['child'], JSON_UNESCAPED_UNICODE);
                $data['booking_landtour']['num_adult'] = $data['num_adult'];
                $data['booking_landtour']['num_children'] = $data['num_children'];
                $data['booking_landtour']['landtour_id'] = $data['item_id'];
            }
            $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            if (isset($data['end_date'])) {
                $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            } else {
                $data['end_date'] = $data['start_date'];
            }
        }
        $data['status'] = 4;
        $data['sale_discount'] = 0;
        $data['agency_discount'] = 0;
        $data['payment_method'] = AGENCY_PAY;
        $data['status'] = 4;
        $data['complete_date'] = date('Y-m-d');
        $response['data'] = $data;
        $response['success'] = true;
        if (isset($response['errors']['booking_rooms'])) {
            $response['success'] = false;
        }
        return $response;
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

    private function _buildSaveHomestay($data)
    {
        $this->loadModel('Homestays');
        $response = ['success' => false, 'message' => '', 'errors' => []];

        $start_date = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
        $end_date = $this->Util->formatSQLDate(\DateTime::createFromFormat('d/m/Y', $data['end_date'])->modify('-1 day')->format('d/m/Y'), 'd/m/Y');
        $date_start_attended = date_create(date('Y-m-d', strtotime($start_date)));
        $date_end_attended = date_create(date('Y-m-d', strtotime($end_date)));

        $homestay = $this->Homestays->get($data['item_id'], ['contain' => 'PriceHomeStays']);
        $days_attended = date_diff($date_start_attended, $date_end_attended);
        $dateArray = $this->Util->_dateRange($start_date, $end_date);

        $totalPrice = 0;
        foreach ($dateArray as $date) {
            $totalPrice += $this->Util->countingHomeStayPrice($date, $homestay);
        }
        $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
        $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
        $data['revenue'] = $homestay->price_customer * ($days_attended->days + 1);
        $data['sale_revenue'] = $homestay->price_agency * ($days_attended->days + 1);
        $data['price'] = $totalPrice;
        $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'Y-m-d');
        $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
        $response['data'] = $data;
        $response['success'] = true;

        return $response;
    }

    private function _buildSaveVoucher($data)
    {
        $this->loadModel('Vouchers');
        $response = ['success' => false, 'data' => [], 'message' => ''];
        $voucher = $this->Vouchers->get($data['item_id']);
        $start_date = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
        $end_date = $this->Util->formatSQLDate(\DateTime::createFromFormat('d/m/Y', $data['start_date'])->modify('+' . $voucher->days_attended . ' days')->format('d/m/Y'), 'd/m/Y');

        $bookingDateArray = $this->Util->_dateRange($start_date, $end_date);

//        dd($bookingDateArray);
        if ($voucher->days_attended == count($bookingDateArray) - 1) {
            if ($this->Util->checkBetweenDate($start_date, $voucher->start_date, $voucher->end_date) && $this->Util->checkBetweenDate($end_date, $voucher->start_date, $voucher->end_date)) {
                $data['price'] = ($voucher->price + $voucher->trippal_price + $voucher->customer_price) * $data['amount'];;
                $data['revenue'] = $voucher->customer_price * $data['amount'];
                $data['sale_revenue'] = $voucher->trippal_price * $data['amount'];
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
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
        $this->loadModel('LandTourSurcharges');
        $this->loadModel('LandTourUserPrices');
        $this->loadModel('BookingLandtourAccessories');
        $this->loadComponent('Util');
        if (isset($data['drive_surchage_pickup'])) {
            $data['pickup_id'] = $data['drive_surchage_pickup'];
        }
        if (isset($data['drive_surchage_drop'])) {
            $data['drop_id'] = $data['drive_surchage_drop'];
        }
        $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingLandtour', 'associated' => ['BookingLandtours', 'BookingLandtourAccessories']]);
        $response = ['success' => false, 'data' => [], 'message' => ''];
        if (!isset($data['accessroy'])) {
            $data['accessroy'] = [0];
        }
        if ($validate->getErrors()) {
            $response['errors'] = $validate->getErrors();
        } else {
            if (!isset($data['drive_surchage_pickup'])) {
                $data['drive_surchage_pickup'] = 0;
            }
            if (!isset($data['drive_surchage_drop'])) {
                $data['drive_surchage_drop'] = 0;
            }
            $landTour = $this->LandTours->get($data['item_id'], ['contain' => ['LandTourAccessories' => function ($q) use ($data) {
                return $q->where(['LandTourAccessories.Id IN' => $data['accessroy']]);
            },
                'LandTourDrivesurchages' => function ($q) use ($data) {
                    return $q->where(['id IN' => [$data['drive_surchage_pickup'], $data['drive_surchage_drop']]]);
                }
            ]]);
            $data['mustgo_deposit'] = str_replace(',', '', $data['mustgo_deposit']);
            $price = 0;
            if (isset($data['accessroy']) && !empty($data['accessroy'])) {
                $booking_landtour_accessories = [];
                foreach ($data['accessroy'] as $k => $id) {
                    $booking_landtour_accessories[$k]['land_tour_accessory_id'] = $id;
                }
                $data['booking_landtour_accessories'] = $booking_landtour_accessories;
                unset($data['accessroy']);
                if (!isset($data['booking_id'])) {
                    $data['booking_id'] = 0;
                }

                foreach ($data['booking_landtour_accessories'] as $k => $landtour_accessory) {
                    $bookingLandtourAccessories = $this->BookingLandtourAccessories->find()->where(['booking_id' => $data['booking_id'], 'land_tour_accessory_id' => $landtour_accessory['land_tour_accessory_id']])->first();
                    if ($bookingLandtourAccessories) {
                        $data['booking_landtour_accessories'][$k]['id'] = $bookingLandtourAccessories->id;
                    }
                }
            }
            $priceDefault = $landTour->price + $landTour->customer_price;
            $userPrice = $this->LandTourUserPrices->find()->where(['user_id' => $data['user_id'], 'land_tour_id' => $data['item_id']])->first();
            if ($userPrice) {
                $priceDefault += $userPrice->price;
                $data['sale_revenue'] = $userPrice->price * $data['booking_landtour']['num_adult'] + $userPrice->price * $data['booking_landtour']['num_children'] * $landTour->child_rate / 100 + $userPrice->price * $data['booking_landtour']['num_kid'] * $landTour->kid_rate / 100;
            } else {
                $priceDefault += $landTour->trippal_price;
                $data['sale_revenue'] = $landTour->trippal_price * $data['booking_landtour']['num_adult'] + $landTour->trippal_price * $data['booking_landtour']['num_children'] * $landTour->child_rate / 100 + $landTour->trippal_price * $data['booking_landtour']['num_kid'] * $landTour->kid_rate / 100;
            }
            $data['revenue'] = $landTour->customer_price * $data['booking_landtour']['num_adult'] + $landTour->customer_price * $data['booking_landtour']['num_children'] * $landTour->child_rate / 100 + $landTour->customer_price * $data['booking_landtour']['num_kid'] * $landTour->kid_rate / 100;
            foreach ($landTour->land_tour_accessories as $accessory) {
                $priceDefault += $accessory->adult_price;
            }
            $price = $priceDefault * $data['booking_landtour']['num_adult'] + $priceDefault * $data['booking_landtour']['num_children'] * $landTour->child_rate / 100 + $priceDefault * $data['booking_landtour']['num_kid'] * $landTour->kid_rate / 100;
            $data['amount'] = $data['booking_landtour']['num_adult'] + $data['booking_landtour']['num_children'] + $data['booking_landtour']['num_kid'];
            $tempDriveSurchage = 0;
            if (count($landTour->land_tour_drivesurchages) == 1) {
                if ($data['drive_surchage_pickup'] == 0 || $data['drive_surchage_drop'] == 0) {
                    $tempDriveSurchage = $data['booking_landtour']['num_adult'] * $landTour->land_tour_drivesurchages[0]->price_adult * 0.5;
                    if ($tempDriveSurchage > $landTour->land_tour_drivesurchages[0]->price_crowd * 0.5) {
                        $tempDriveSurchage = $landTour->land_tour_drivesurchages[0]->price_crowd * 0.5;
                    }
                } else {
                    $tempDriveSurchage = $data['booking_landtour']['num_adult'] * $landTour->land_tour_drivesurchages[0]->price_adult;
                    if ($tempDriveSurchage > $landTour->land_tour_drivesurchages[0]->price_crowd) {
                        $tempDriveSurchage = $landTour->land_tour_drivesurchages[0]->price_crowd;
                    }
                }
            } elseif (count($landTour->land_tour_drivesurchages) == 2) {
                $tempDriveSurchage1 = $data['booking_landtour']['num_adult'] * $landTour->land_tour_drivesurchages[0]->price_adult * 0.5;
                if ($tempDriveSurchage1 > $landTour->land_tour_drivesurchages[0]->price_crowd * 0.5) {
                    $tempDriveSurchage1 = $landTour->land_tour_drivesurchages[0]->price_crowd * 0.5;
                }
                $tempDriveSurchage2 = $data['booking_landtour']['num_adult'] * $landTour->land_tour_drivesurchages[1]->price_adult * 0.5;
                if ($tempDriveSurchage2 > $landTour->land_tour_drivesurchages[1]->price_crowd * 0.5) {
                    $tempDriveSurchage2 = $landTour->land_tour_drivesurchages[1]->price_crowd * 0.5;
                }
                $tempDriveSurchage = $tempDriveSurchage1 + $tempDriveSurchage2;
            }
            $price += $tempDriveSurchage;
            $data['booking_landtour']['drive_surchage'] = $tempDriveSurchage;

            if (isset($data['amount'])) {
                if (is_numeric($data['amount'])) {
                    $data['price'] = $price;
                }
            }
            $data['start_date'] = \DateTime::createFromFormat('d/m/Y', $data['start_date'])->format('Y-m-d');
            $data['end_date'] = $data['start_date'];
            $data['payment_deadline'] = $data['start_date'];
            $data['booking_landtour']['landtour_id'] = $data['item_id'];
            $data['booking_landtour']['pickup_id'] = $data['drive_surchage_pickup'];
            $data['booking_landtour']['drop_id'] = $data['drive_surchage_drop'];
            $response['success'] = true;
            $response['data'] = $data;
        }
        return $response;
    }

    private function _buildSaveHotel($data)
    {
        $this->loadModel('Hotels');
        $this->loadModel('Rooms');
        $this->loadModel('Users');
        $this->loadComponent('Util');
        $this->loadModel('HotelSurcharges');
        $response = ['success' => false, 'data' => [], 'message' => ''];
        $response = ['success' => false, 'message' => '', 'errors' => []];
        $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingHotel', 'associated' => ['BookingRooms']]);
        if ($validate->getErrors()) {
            $response['errors'] = $validate->getErrors();
        } else {
            $hotel = $this->Hotels->get($data['item_id']);
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $data['booking_rooms'][$key]['start_date'] = \DateTime::createFromFormat('d/m/Y', $booking_room['start_date'])->format('d-m-Y');
                    $data['booking_rooms'][$key]['end_date'] = \DateTime::createFromFormat('d/m/Y', $booking_room['end_date'])->format('d-m-Y');
                }
                $booking_rooms = $data['booking_rooms'];
                $totalPrice = $totalSaleRev = $totalRev = 0;
                $start_date = $end_date = '';

                $isAllow = true;
                $totalAmount = 0;
                foreach ($data['booking_rooms'] as $key => $booking_room) {
//                    dd($booking_room);
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    $totalAmount += $booking_room['num_room'];
                    if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                            if ($start_date) {
                                if (strtotime($start_date) > strtotime($booking_room['start_date'])) {
                                    $start_date = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                                }
                            } else {
                                $start_date = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                            }
                            if ($end_date) {
                                if (strtotime($end_date) < strtotime($booking_room['end_date'])) {
                                    $end_date = $this->Util->formatSQLDate($booking_room['end_date'], 'd-m-Y');
                                }
                            } else {
                                $end_date = $this->Util->formatSQLDate($booking_room['end_date'], 'd-m-Y');
                            }
//                            $sDate = $this->Util->formatSQLDate($booking_room['start_date'], 'd/m/Y');
//                            $eDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                            $sDate = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                            $eDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                            $dates = $this->Util->_dateRange($sDate, $eDate);
                            foreach ($dates as $date) {
                                $resPrice = $this->Util->calculateHotelPrice($hotel, $booking_room['room_id'], $date);
                                if ($resPrice['status']) {
                                    $totalPrice += $resPrice['price'] * $booking_room['num_room'];
                                    $totalRev += $this->Util->calculateHotelRevenue($hotel, $booking_room['room_id'], $date) * $booking_room['num_room'];
                                    $totalSaleRev += $this->Util->calculateHotelSaleRevenue($hotel, $booking_room['room_id'], $date) * $booking_room['num_room'];
                                } else {
                                    $isAllow = false;
                                    $response['errors']['incorrect_info'] = ['message' => $resPrice['message']];
                                    break;
                                }
                            }
                        } else {
                            $isAllow = false;
                            $response['message'] = 'Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.';
                        }
                    } else {
                        $isAllow = false;
                        $response['message'] = 'Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.';
                    }

                    $data['booking_rooms'][$key]['start_date'] = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                    $data['booking_rooms'][$key]['end_date'] = $this->Util->formatSQLDate($booking_room['end_date'], 'd-m-Y');
                    if (!isset($booking_room['child_ages'])) {
                        $booking_room['child_ages'] = [];
                    }
                    $data['booking_rooms'][$key]['child_ages'] = json_encode($booking_room['child_ages'], JSON_UNESCAPED_UNICODE);
                }
                if ($isAllow) {
                    if (isset($data['booking_surcharges'])) {
                        foreach ($data['booking_surcharges'] as $key => $booking_surcharge) {
                            if (isset($booking_surcharge['price']) && !empty($booking_surcharge['price'])) {
                                $quantity = (isset($booking_surcharge['quantity'])) ? $booking_surcharge['quantity'] : 0;
                                $data['booking_surcharges'][$key]['price'] = $this->Util->calHotelSurcharge($hotel, $booking_rooms, $booking_surcharge['surcharge_type'], $quantity, $booking_surcharge['id']);
                            } else {
                                unset($data['booking_surcharges'][$key]);
                            }
                        }

                        $data['booking_surcharges'] = array_values($data['booking_surcharges']);
                    }
                    if (isset($data['payment_method']) && $data['payment_method'] == 1) {
                        $totalPrice = $totalPrice - $totalRev;
                        $totalRev = 0;
                    }

                    $data['sale_id'] = $this->Auth->user('id');
                    $data['price'] = $totalPrice;
                    $data['sale_revenue'] = $totalSaleRev;
                    $data['revenue'] = $totalRev;
                    $data['start_date'] = $start_date;
                    $data['end_date'] = $end_date;
                    $data['amount'] = $totalAmount;
                }
            }
            $response['success'] = true;
            $response['data'] = $data;
            return $response;
        }

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
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->loadModel('Rooms');
        $this->loadModel('LandTours');
        $this->loadModel('BookingLogs');
        $booking = $this->Bookings->get($id, [
            'contain' => ['BookingSurcharges', 'BookingRooms', 'BookingLandtours', 'BookingLandtourAccessories']
        ]);
//        dd(2);
        if ($booking->status == 3 || $booking->status == 4) {
            $this->redirect(['action' => 'index']);
        }
        $referer = $this->referer();
        $url_components = parse_url($referer);
        if (isset($url_components['query'])) {
            parse_str($url_components['query'], $indexParams);
        } else {
            $indexParams = [];
        }
//        if ($booking->status < 4) {
        $status = [
            '0' => 'Chưa thanh toán',
            '1' => 'Đã thanh toán'
        ];

        $booking_type = [
            SYSTEM_BOOKING => 'Booking thuộc hệ thống',
        ];

        $method = [
            CUSTOMER_PAY => 'Khách hàng chuyển trực tiếp',
            AGENCY_PAY => 'CTV sẽ thu tiền hộ'
        ];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['booking_id'] = $id;
            $isAllow = true;
            $indexParams = json_decode($data['indexParams'], true);
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople < ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        $isAllow = false;
                        $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.'));
                        break;
                        if ($roomTotalMaxAdult < $booking_room['num_adult']) {
                            $isAllow = false;
                            $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người.'));
                            break;
                        }
                    }
                }
            }
            if ($isAllow) {
                $item_id = $data['item_id'];
                if (isset($data['payment_deadline'])) {
                    $data['payment_deadline'] = $this->Util->formatSQLDate($data['payment_deadline'], 'd/m/Y');
                }
                $data['customer_deposit'] = str_replace(',', '', $data['customer_deposit']);
                $data['sale_discount'] = 0;
                $data['agency_discount'] = 0;
//                $data['sale_discount'] = str_replace(',', '', $data['sale_discount']);
//                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                if ($data['booking_type'] == SYSTEM_BOOKING) {
                    switch ($data['type']) {
                        case HOMESTAY:
                            $dataBooking = $this->_buildSaveHomestay($data);
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
                    if ($dataBooking['data']['type'] != LANDTOUR) {
                        $dataBooking['data']['sale_revenue'] = (isset($dataBooking['data']['sale_revenue']) ? $dataBooking['data']['sale_revenue'] : 0) + $dataBooking['data']['sale_discount'];
                        if ($dataBooking['data']['user_id'] != $dataBooking['data']['sale_id']) {
                            $dataBooking['data']['price'] = (isset($dataBooking['data']['price']) ? $dataBooking['data']['price'] : 0) - $dataBooking['data']['revenue'];
                            $dataBooking['data']['revenue'] = 0;
                        }
                        $dataBooking['data']['price'] -= $dataBooking['data']['agency_discount'];
                    } else {
                        if ($dataBooking['data']['payment_method'] == AGENCY_PAY) {
                            $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                            $dataBooking['data']['revenue'] = 0;
                        }
                        if ($dataBooking['data']['payment_method'] == MUSTGO_DEPOSIT) {
                            $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                            $dataBooking['data']['revenue'] = $dataBooking['data']['mustgo_deposit'] - $dataBooking['data']['price'];
                        }
                        $dataBooking['data']['price'] += $dataBooking['data']['sale_discount'];
                        $dataBooking['data']['revenue'] -= $dataBooking['data']['agency_discount'];
                    }
                } else if ($data['booking_type'] == ANOTHER_BOOKING) {
                    $dataBooking = $this->_buildSaveAnother($data);
                }
                if ($dataBooking['success']) {
                    $priceOld = $this->Bookings->find()->where(['id' => $booking->id])->first();
                    $priceNew = intval(str_replace(',', '', $data['price']));
                    if ($priceNew != $priceOld->price)
                    {
                        $booking['status'] = 0;
                    }
                    $timestampDeadline = strtotime(str_replace('/', '-', $dataBooking['data']['paymentDeadline']));
                    $paymentDeadline = date('Y-m-d', $timestampDeadline);
                    $dataBooking['data']['payment_deadline'] = $paymentDeadline;
                    $booking = $this->Bookings->patchEntity($booking, $dataBooking['data']);
                    if ($this->Bookings->save($booking)) {
                        $this->Flash->success(__('The booking has been saved.'));
                        $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
                        if (!$payment) {
                            $payment = $this->Payments->newEntity();
                            $paymentData['booking_id'] = $booking->id;
                        }
                        if (isset($data['payment_type'])) {
                            $paymentData['type'] = $data['payment_type'];
                        }
                        if (isset($data['payment_invoice'])) {
                            $paymentData['invoice'] = $data['payment_invoice'];
                        }
                        if (isset($data['payment_invoice_information'])) {
                            $paymentData['invoice_information'] = $data['payment_invoice_information'];
                        }
                        if (isset($data['payment_address'])) {
                            $paymentData['address'] = $data['payment_address'];
                        }
                        if (isset($data['media'])) {
                            $paymentData['images'] = $data['media'];
                        }
                        if (isset($data['pay_object'])) {
                            $paymentData['pay_object'] = $data['pay_object'];
                            if ($data['pay_object'] == PAY_HOTEL) {
                                $paymentData['check_type'] = $data['check_type'];
                                $paymentData['partner_information'] = '';
                            } else {
                                $paymentData['check_type'] = 0;
                                $paymentData['partner_information'] = [
                                    'name' => $data['partner_name'],
                                    'number' => $data['partner_number'],
                                    'bank' => $data['partner_bank'],
                                    'email' => $data['partner_email']
                                ];
                                $paymentData['partner_information'] = json_encode($paymentData['partner_information'], JSON_UNESCAPED_UNICODE);
                            }
                        }
                        $payment = $this->Payments->patchEntity($payment, $paymentData);
                        $this->Payments->save($payment);
//                            return $this->redirect(['action' => 'index', '?' => $indexParams]);
                        return $this->redirect($dataBooking['data']['previous_url']);
                    }
                    $this->Flash->error(__('The booking could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__($dataBooking['message']));
                }
            }
        }
        if ($this->Auth->user('role_id') == 5) {
            $object_types = [
                LANDTOUR => 'Landtour'
            ];
            $querys = $this->Users->find()->where(['role_id' => 3, 'landtour_parent_id' => $this->Auth->user('id')])->toArray();
        } elseif ($this->Auth->user('role_id') == 2) {
            $object_types = [
                HOMESTAY => 'Homestay',
                VOUCHER => 'Voucher',
                HOTEL => 'Hotel'
            ];
            $querys = $this->Users->find()->where(['role_id' => 3, 'parent_id' => $this->Auth->user('id')])->toArray();
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $objects = [];
        $objects[$this->Auth->user('id')] = 'Khách lẻ';
        foreach ($querys as $query) {
            $objects[$query['id']] = $query['screen_name'];
        }
        $querysV2 = $this->Users->find()->where(['role_id' => 3])->toArray();
        $objectsV2 = [];
        $objectsV2[$this->Auth->user('id')] = 'Khách lẻ';
        foreach ($querysV2 as $queryV2) {
            $objectsV2[$queryV2['id']] = $queryV2['screen_name'];
        }
        $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
        $list_images = [];
        if ($payment) {
            if ($payment->images) {
                $list_images = $payment->images;
            }
        }

//        dd(1);

        $userLogs = $this->Auth->user();
        $bookingLogs = $this->BookingLogs->find()
            ->join([
                'u' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'u.id = BookingLogs.user_id',
                ]
            ])
            ->where(['booking_id' => $booking->id])
            ->select(['u.screen_name', 'BookingLogs.id',  'BookingLogs.comment', 'BookingLogs.title', 'BookingLogs.created'])
            ->toArray();
        $this->set(compact('booking', 'users', 'objects', 'object_types', 'status', 'method', 'booking_type', 'objectsV2', 'payment', 'list_images', 'indexParams', 'referer', 'bookingLogs', 'userLogs'));
//        } else {
//            return $this->redirect(['action' => 'view', $id]);
//        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $booking = $this->Bookings->get($id);

        if ($booking->sale_id == $this->Auth->user('id')) {
            if ($this->Bookings->delete($booking)) {
                $this->Flash->success(__('The booking has been deleted.'));
            } else {
                $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
            }
        } else $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
        return $this->redirect(['action' => 'index']);
    }

    public function deny($id = null)
    {
        $booking = $this->Bookings->get($id);
        $booking = $this->Bookings->patchEntity($booking, ['status' => 5]);
        if ($this->Bookings->save($booking)) {
            $this->Flash->success(__('The booking has been denied.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function deleteVin($id = null)
    {
        $this->loadModel('Vinhmsbookings');
        $this->request->allowMethod(['post', 'delete']);
        $booking = $this->Vinhmsbookings->get($id);

        if ($booking->sale_id == $this->Auth->user('id')) {
            if ($this->Vinhmsbookings->delete($booking)) {
                $this->Flash->success(__('The booking has been deleted.'));
            } else {
                $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
            }
        } else $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
        return $this->redirect(['action' => 'index']);
    }

    public function denyVin($id = null)
    {
        $this->loadModel('Vinhmsbookings');
        $booking = $this->Vinhmsbookings->get($id);
        $booking = $this->Vinhmsbookings->patchEntity($booking, ['status' => 5]);
        if ($this->Vinhmsbookings->save($booking)) {
            $this->Flash->success(__('The booking has been denied.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function finish($id = null)
    {
        $this->loadModel('Users');
        $booking = $this->Bookings->get($id);
        $booking = $this->Bookings->patchEntity($booking, ['status' => 4, 'complete_date' => date_format($booking->start_date, 'Y-m-d')]);
        if ($this->Bookings->save($booking)) {
            $user = $this->Users->get($booking->user_id);
            if ($user->role_id == 3) {
                $newRevenue = $user->revenue + $booking->revenue;
                $user = $this->Users->patchEntity($user, ['revenue' => $newRevenue]);
                $this->Users->save($user);
            }
            $this->Flash->success(__('The booking has been changed to done.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function getListObjectByType()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Homestays');
        $this->loadModel('Vouchers');
        $this->loadModel('LandTours');
        $this->loadModel('Hotels');
        $this->loadModel('Rooms');
        $response = ['success' => true, 'data' => '', 'room_id' => '', 'booking_type' => 0, 'object_type' => 0, 'is_edited' => false];
        $data = $this->request->getQuery();
        $item_id = $data['object_id'];
        $type = $data['object_type'];
        $booking_type = $data['booking_type'];
        if ($data['booking_id']) {
            $booking_id = $data['booking_id'];
            $response['is_edited'] = true;
            $this->set(compact('booking_id'));
        }

        $response['object_type'] = $data['object_type'];
        $label = '';
        switch ($data['object_type']) {
            case HOMESTAY:
                $query = $this->Homestays;
                $label = 'Chọn Homestays ';
                break;
            case VOUCHER:
                $query = $this->Vouchers;
                $label = 'Chọn Voucher ';
                break;
            case LANDTOUR:
                $query = $this->LandTours;
                $label = 'Chọn LandTour ';
                break;
            case HOTEL:
                $query = $this->Hotels;
                $label = 'Chọn Hotel ';
                break;
        }
        $objects = $query->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ]);
        $this->set(compact('objects', 'label', 'item_id', 'type', 'booking_type'));

        $response['data'] = $this->render('get_list_object_by_type')->body();
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;

    }

    public function getListRoomsForHotel()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Rooms');
        $this->loadModel('Hotels');
        $this->loadModel('Vouchers');
        $this->loadModel('HomeStays');
        $this->loadModel('LandTours');
        $this->loadModel('Bookings');
        $response = ['success' => false, 'data' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            if ($data['type'] == HOTEL) {
                $response['success'] = true;
                $hotel = $this->Hotels->get($data['item_id']);
                if ($data['booking_id']) {
                    $booking = $this->Bookings->get($data['booking_id'], ['contain' => ['BookingRooms']]);
                    $this->set(compact('booking'));
                }
                $rooms = $this->Rooms->find()->where(['hotel_id' => $data['item_id']]);
                $listRoom = $rooms->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'name'
                ]);
                $this->set(compact('listRoom', 'hotel'));
                $response['data'] = $this->render('get_list_rooms_for_hotel')->body();
            } else if ($data['type'] == VOUCHER) {
                $response['success'] = true;
                $voucher = $this->Vouchers->get($data['item_id'], ['contain' => ['Hotels']]);
                if ($data['booking_id']) {
                    $booking = $this->Bookings->get($data['booking_id']);
                    $this->set(compact('booking'));
                }
                $this->set(compact('voucher'));
                $response['data'] = $this->render('get_voucher_hotel_is_speacial')->body();
            } else if ($data['type'] == HOMESTAY) {
                $response['success'] = true;
                $homestay = $this->HomeStays->get($data['item_id']);
                if ($data['booking_id']) {
                    $booking = $this->Bookings->get($data['booking_id']);
                    $this->set(compact('booking'));
                }
                $this->set(compact('homestay'));
                $response['data'] = $this->render('get_homestay_another_booking')->body();
            } else if ($data['type'] == LANDTOUR) {
                $response['success'] = true;
                $land_tour = $this->LandTours->get($data['item_id']);
                if ($data['booking_id']) {
                    $booking = $this->Bookings->get($data['booking_id'], ['contain' => ['BookingLandtours']]);
                    $this->set(compact('booking'));
                }
                $this->set(compact('land_tour'));
                $response['data'] = $this->render('get_landtour_another_booking')->body();
            }

            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function bookingSendEmail($booking_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Users');
        $this->loadModel('UserTransactions');
//        $this->loadComponent('Email');
        $response = ['success' => false, 'message' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $booking = $this->Bookings->get($booking_id, ['contain' => ['Users', 'Hotels', 'Vouchers.Hotels', 'LandTours', 'LandTours.Destinations', 'Vouchers.Hotels', 'HomeStays', 'BookingLandtours', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'Payments']]);
            $itemName = '';
            $itemName .= $booking->hotels ? $booking->hotels->name : '';
            $itemName .= $booking->vouchers ? $booking->vouchers->name : '';
            $itemName .= $booking->home_stays ? $booking->home_stays->name : '';
            $itemName .= $booking->land_tours ? $booking->land_tours->name : '';
            $logFIle = [
                'booking_id' => $booking_id,
                'item_name' => $itemName,
            ];
            switch ($data['type']) {
                case E_PAY_AGENCY:
                    $logFIle['mail_type'] = 'E_PAY_AGENCY';
                    $logFIle['status'] = 'start';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    $response = $this->_sendPaymentToAgency($booking);
                    $logFIle['status'] = 'finish';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    break;
                case E_BOOK_HOTEL:
                    $logFIle['mail_type'] = 'E_BOOK_HOTEL';
                    $logFIle['status'] = 'start';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    $response = $this->_sendRequestBookingToHotel($booking);
                    $logFIle['status'] = 'finish';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    break;
                case E_BOOK_AGENCY:
                    $logFIle['mail_type'] = 'E_BOOK_AGENCY';
                    $logFIle['status'] = 'start';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    $response = $this->_sendBookingInfoToAgency($booking);
                    $logFIle['status'] = 'finish';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    break;
                case E_PAY_OBJECT:
                    $logFIle['mail_type'] = 'E_PAY_OBJECT';
                    $logFIle['status'] = 'start';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    $response = $this->_sendPaymentToObject($booking);
                    $logFIle['status'] = 'finish';
                    $this->Util->writeLogFile($logFIle, $booking->type);
                    break;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function bookingSendEmailV2($booking_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Users');
        $this->loadModel('UserTransactions');
//        $this->loadComponent('Email');
        $response = ['success' => false, 'message' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $booking = $this->Bookings->get($booking_id, ['contain' => ['Users', 'Hotels', 'Vouchers.Hotels', 'LandTours', 'LandTours.Destinations', 'Vouchers.Hotels', 'HomeStays', 'BookingLandtours', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'Payments']]);
            switch ($data['type']) {
                case E_PAY_AGENCY:
                    $response = $this->_sendPaymentToAgencyV2($booking);
                    break;
                case E_BOOK_HOTEL:
                    $response = $this->_sendRequestBookingToHotelV2($booking);
                    break;
                case E_BOOK_AGENCY:
                    $response = $this->_sendBookingInfoToAgencyV2($booking);
                    break;
                case E_PAY_OBJECT:
                    $response = $this->_sendPaymentToObjectV2($booking);
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
        $bodyEmail = 'Đơn hàng thanh toán cho booking: ' . $booking->id;
        if ($booking->vouchers) {
            $subject = $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->vouchers->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->hotels) {
            $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->hotels->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->home_stays) {
            $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->home_stays->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->land_tours) {
            $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->land_tours->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        $data_sendEmail = [
            'to' => $booking->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_PAY_AGENCY);
        if ($response['success']) {
            if ($booking->status == 1 || $booking->status == 2) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 2]);
                $this->Bookings->save($booking);
            }
        }
        return $response;
    }

    private function _sendPaymentToAgencyV2($booking)
    {
        $this->loadModel('UserExpotokens');
        $bodyEmail = 'Đơn hàng thanh toán cho booking: ' . $booking->id;
        if ($booking->vouchers) {
            $subject = $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->vouchers->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->hotels) {
            $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->hotels->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->home_stays) {
            $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->home_stays->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->land_tours) {
            $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và Đề nghị thanh toán - " . $booking->land_tours->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        $data_sendEmail = [
            'to' => $booking->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $response = $this->Email->sendEmailV2($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_PAY_AGENCY);
        if ($response['success']) {
            if ($booking->status == 1 || $booking->status == 2) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 2]);
                $this->Bookings->save($booking);
            }
        }
        $expo_tokens = $this->UserExpotokens->find()->where(['user_id' => $booking->user_id])->toArray();
        if ($expo_tokens) {
            $notification = ['title' => 'Mustgo.vn', 'body' => ' Yêu cầu thanh toán booking ' . $booking->code, 'badge' => 1];
            $arrayId = [];
            foreach ($expo_tokens as $expo_token) {
                $arrayId[] = $expo_token['expo_push_token'];
            }
            $notification['to'] = $arrayId;
            $this->Util->sendNotifical($notification);
        }
        return $response;
    }

    private function _sendRequestBookingToHotel($booking)
    {
        $this->loadModel('Users');
        $res = ['success' => true, 'request_booking' => false, 'data' => []];
        switch ($booking->type) {
            case HOMESTAY:
                $this->loadModel('Homestays');
                $homestay = $this->Homestays->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->home_stays->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $emails = json_decode($homestay->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
            case VOUCHER:
                $this->loadModel('Vouchers');
                $voucher = $this->Vouchers->get($booking->item_id, ['contain' => ['Hotels']]);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->vouchers->hotel->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $emails = json_decode($voucher->hotel->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
            case LANDTOUR:
                $this->loadModel('LandTours');
                $landTour = $this->LandTours->get($booking->item_id);
                $bodyEmail = 'Book LandTour: ' . $booking->id;
                $subject = date_format($booking->start_date, 'd/m/Y') . ' - ' . mb_strtoupper($landTour->name);
                if ($booking->sale_id == $booking->user_id) {
                    $subject .= ' - KHÁCH LẺ ' . $booking->full_name;
                } else {
                    $user = $this->Users->get($booking->user_id);
                    $subject .= ' - ĐL ' . mb_strtoupper($user->screen_name);
                }
                $emails = json_decode($landTour->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
            case HOTEL:
                $this->loadModel('Hotels');
                $hotel = $this->Hotels->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->hotels->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $emails = json_decode($hotel->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
        }
        if ($response['success']) {
            if ($booking->status == 0) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 1]);
                $this->Bookings->save($booking);
            }
        }
        $res = $response;
        return $res;
    }

    private function _sendRequestBookingToHotelV2($booking)
    {
        $res = ['success' => true, 'request_booking' => false, 'data' => []];
        switch ($booking->type) {
            case HOMESTAY:
                $this->loadModel('Homestays');
                $homestay = $this->Homestays->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->home_stays->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $emails = json_decode($homestay->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmailV2($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
            case VOUCHER:
                $this->loadModel('Vouchers');
                $voucher = $this->Vouchers->get($booking->item_id, ['contain' => ['Hotels']]);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->vouchers->hotel->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $emails = json_decode($voucher->hotel->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmailV2($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
            case LANDTOUR:
                $this->loadModel('LandTours');
                $landTour = $this->LandTours->get($booking->item_id);
                $bodyEmail = 'Book LandTour: ' . $booking->id;
                $subject = date_format($booking->start_date, 'd/m/Y') . ' - ' . mb_strtoupper($landTour->name);
                if ($booking->sale_id == $booking->user_id) {
                    $subject .= ' - KHÁCH LẺ ' . $booking->full_name;
                } else {
                    $user = $this->Users->get($booking->user_id);
                    $subject .= ' - ĐL ' . mb_strtoupper($user->screen_name);
                }
                $emails = json_decode($landTour->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
            case HOTEL:
                $this->loadModel('Hotels');
                $hotel = $this->Hotels->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Đặt phòng - ' . $booking->hotels->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
                $emails = json_decode($hotel->email, true);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $response = $this->Email->sendHotelEmailV2($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_HOTEL);
                break;
        }
        if ($response['success']) {
            if ($booking->status == 0) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 1]);
                $this->Bookings->save($booking);
            }
        }
        $res = $response;
        return $res;
    }

    private function _sendBookingInfoToAgency($booking)
    {
        $bodyEmail = 'Xác nhận thành công cho Booking: ' . $booking->code;
        if ($booking->vouchers) {
            $subject = $subject = "Mustgo.vn - Xác nhận đặt voucher - " . $booking->vouchers->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->hotels) {
            $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->hotels->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->combos) {
            $subject = "Mustgo.vn - Xác nhận đặt combo - " . $booking->combos->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->land_tours) {
            $subject = "Mustgo.vn - Xác nhận đặt landtour - " . $booking->land_tours->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->home_stays) {
            $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->home_stays->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        $data_sendEmail = [
            'to' => $booking->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $response = $this->Email->sendEmail($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_AGENCY);
        if ($response['success']) {
            if ($booking->status == 2) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 3]);
                $this->Bookings->save($booking);
            }

        }
        return $response;
    }

    private function _sendBookingInfoToAgencyV2($booking)
    {
        $bodyEmail = 'Xác nhận thành công cho Booking: ' . $booking->code;
        if ($booking->vouchers) {
            $subject = $subject = "Mustgo.vn - Xác nhận đặt voucher - " . $booking->vouchers->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->hotels) {
            $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->hotels->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->combos) {
            $subject = "Mustgo.vn - Xác nhận đặt combo - " . $booking->combos->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->land_tours) {
            $subject = "Mustgo.vn - Xác nhận đặt landtour - " . $booking->land_tours->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        if ($booking->home_stays) {
            $subject = "Mustgo.vn - Xác nhận đặt phòng - " . $booking->home_stays->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        }
        $data_sendEmail = [
            'to' => $booking->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $response = $this->Email->sendEmailV2($data_sendEmail, $this->Auth->user('email'), $this->Auth->user('email_access_code'), E_BOOK_AGENCY);
        if ($response['success']) {
            if ($booking->status == 2) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 3]);
                $this->Bookings->save($booking);
            }

        }
        return $response;
    }

    private function _sendPaymentToObject($booking)
    {
        switch ($booking->type) {
            case HOMESTAY:
                $this->loadModel('Homestays');
                $this->loadModel('Users');
                $homestay = $this->Homestays->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Thanh toán - ' . $booking->home_stays->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y") . ' - ' . $booking->code;
                $emails = json_decode($homestay->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendHotelEmail($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
            case VOUCHER:
                $this->loadModel('Vouchers');
                $this->loadModel('Users');
                $voucher = $this->Vouchers->get($booking->item_id, ['contain' => ['Hotels']]);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Thanh toán - ' . $booking->vouchers->hotel->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y") . ' - ' . $booking->code;
                $emails = json_decode($voucher->hotel->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendHotelEmail($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
            case LANDTOUR:
                $this->loadModel('LandTours');
                $this->loadModel('Users');
                $landTour = $this->LandTours->get($booking->item_id);
                $bodyEmail = 'Book LandTour: ' . $booking->id;
                $emails = json_decode($landTour->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => 'Book LandTour',
                    'title' => 'Book LandTour',
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendHotelEmail($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
            case HOTEL:
                $this->loadModel('Hotels');
                $this->loadModel('Users');
                $hotel = $this->Hotels->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Thanh toán - ' . $booking->hotels->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y") . ' - ' . $booking->code;
                $emails = json_decode($hotel->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendPaymentEmailToObject($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
        }
        if ($response['success']) {
            if ($booking->status == 2 || $booking->status == 1) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 3]);
                $this->Bookings->save($booking);
            }
        }
        $res = $response;
        return $res;
    }

    private function _sendPaymentToObjectV2($booking)
    {
        switch ($booking->type) {
            case HOMESTAY:
                $this->loadModel('Homestays');
                $this->loadModel('Users');
                $homestay = $this->Homestays->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Thanh toán - ' . $booking->home_stays->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y") . ' - ' . $booking->code;
                $emails = json_decode($homestay->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendHotelEmailv2($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
            case VOUCHER:
                $this->loadModel('Vouchers');
                $this->loadModel('Users');
                $voucher = $this->Vouchers->get($booking->item_id, ['contain' => ['Hotels']]);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Thanh toán - ' . $booking->vouchers->hotel->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y") . ' - ' . $booking->code;
                $emails = json_decode($voucher->hotel->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendHotelEmailv2($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
            case LANDTOUR:
                $this->loadModel('LandTours');
                $this->loadModel('Users');
                $landTour = $this->LandTours->get($booking->item_id);
                $bodyEmail = 'Book LandTour: ' . $booking->id;
                $subject = 'Mustgo.vn - Thanh toán - ' . $booking->land_tours->name . " - " . $booking->full_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y") . ' - ' . $booking->code;
                $emails = json_decode($landTour->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendHotelEmailv2($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
            case HOTEL:
                $this->loadModel('Hotels');
                $this->loadModel('Users');
                $hotel = $this->Hotels->get($booking->item_id);
                $bodyEmail = 'Book phòng: ' . $booking->id;
                $subject = 'Mustgo.vn - Thanh toán - ' . $booking->hotels->name . ' - ' . $booking->full_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y") . ' - ' . $booking->code;
                $emails = json_decode($hotel->email);
                $data_sendEmail = [
                    'to' => $emails,
                    'subject' => $subject,
                    'title' => $subject,
                    'body' => $bodyEmail,
                    'data' => $booking
                ];
                $sale = $this->Users->get($booking->sale_id);
                $email = $sale->email;
                $response = $this->Email->sendPaymentEmailToObjectV2($data_sendEmail, $email, $sale->email_access_code, E_PAY_OBJECT);
                break;
        }
        if ($response['success']) {
            if ($booking->status == 2 || $booking->status == 1) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 3]);
                $this->Bookings->save($booking);
            }
        }
        $res = $response;
        return $res;
    }

    public function calculateDefaultPrice()
    {
        $response = ['success' => false, 'price' => 0];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $response['success'] = true;
            $booking = $this->Bookings->get($data['booking_id'], ['contain' => [
                'Hotels',
                'HomeStays',
                'Vouchers',
                'LandTours'
            ]]);
            $response['price'] = $booking->price;
            $price = $booking->price;
            if ($booking->payment_method != $data['method']) {
                if ($data['method'] == CUSTOMER_PAY) {
                    if ($booking->type == LANDTOUR) {
                        $revenue = $booking->land_tours->customer_price * $booking->amount;
                    }
                    if ($booking->type == HOTEL) {
                        $revenue = $booking->hotels->price_customer * $booking->amount;
                    }
                    if ($booking->type == HOMESTAY) {
                        $revenue = $booking->home_stays->price_customer * $booking->amount;
                    }
                    if ($booking->type == VOUCHER) {
                        $revenue = $booking->vouchers->customer_price * $booking->amount;
                    }

                    $price = $booking->price + $revenue;
                } elseif ($data['method'] == AGENCY_PAY) {
                    $price = $booking->price - $booking->revenue;
                }
            }
            $response['price'] = number_format($price);

            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));

            return $output;
        }
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

    private function _dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public function addFormType()
    {
        $this->loadModel('Hotels');
        $this->loadModel('HomeStays');
        $this->loadModel('LandTours');
        $this->loadModel('Vouchers');
        $this->loadModel('Payments');
        $this->loadModel('LandTourAccessories');
        $this->loadModel('Users');
        $this->viewBuilder()->enableAutoLayout(false);
        $status = [
            '0' => 'Chưa thanh toán',
            '1' => 'Đã thanh toán'
        ];
        $method = [
            CUSTOMER_PAY => 'Khách hàng chuyển trực tiếp',
            AGENCY_PAY => 'CTV sẽ thu tiền hộ'
        ];

        if ($this->request->is('ajax')) {
            $type = $this->request->getQuery('type');
            $userId = $this->request->getQuery('user_id');
            $email = '';
            if (isset($userId) && !empty($userId)) {
                $user = $this->Users->get($userId);
                if ($user && $user->role_id == 3) {
                    $email = $user->email;
                }
            }

            $booking_id = $this->getRequest()->getQuery('booking_id');
            if ($booking_id) {
                $booking = $this->Bookings->get($booking_id, ['contain' => ['BookingSurcharges', 'BookingRooms', 'BookingLandtours', 'BookingLandtourAccessories', 'LandTours', 'Hotels', 'Vouchers', 'HomeStays']]);
                $payment = $this->Payments->find()->where(['booking_id' => $booking_id])->first();
                $list_images = '';
                if ($payment) {
                    if ($payment->images) {
                        $list_images = $payment->images;
                    }
                }
            } else {
                $booking = null;
                $payment = null;
                $list_images = '';
            }

            switch ($type) {
                case VOUCHER:
                    $listObjs = [];
                    $listVouchers = $this->Vouchers->find()->where([
                        'start_date <=' => date('Y-m-d'),
                        'end_date >=' => date('Y-m-d'),
                    ]);
                    foreach ($listVouchers as $voucher) {
                        $listObjs[$voucher->id] = $voucher->name;
                    }
                    $this->set(compact('listObjs', 'status', 'method', 'booking', 'list_images', 'payment', 'email'));
                    $this->viewBuilder()->setTemplate('add_form_type_voucher');
                    break;
                case LANDTOUR:
                    $listObjs[0] = "Chọn Landtour";
                    $listLandtours = $this->LandTours->find('list')->toArray();
                    foreach ($listLandtours as $k => $listLandtour) {
                        $listObjs[$k] = $listLandtour;
                    }
                    $this->loadModel('BookingLandtourAccessories');
                    $listAge = [];
                    $method = [
                        0 => "Chọn phương thức thanh toán",
                        MUSTGO_DEPOSIT => 'Mustgo thu hộ',
                        AGENCY_PAY => 'Đại lý thanh toán cho Mustgo'
                    ];
                    if ($booking) {
                        $landtour = $this->LandTours->get($booking->item_id, ['contain' => [
                            'LandTourAccessories',
                            'LandTourUserPrices' => function ($q) use ($booking) {
                                return $q->where(['user_id' => $booking->user_id]);
                            },
                            'LandTourDriveSurchages'
                        ]]);
                        $landtourAccessories = $this->LandTourAccessories->find()->where(['land_tour_id' => $landtour->id]);

                        $accessoryId = $this->BookingLandtourAccessories->find('list', [
                            'keyField' => 'id',
                            'valueField' => 'land_tour_accessory_id'
                        ])->where(['booking_id' => $booking->id])->toArray();

                        $this->set(compact('landtourAccessories', 'accessoryId'));
                    } else {
                        $landtour = $this->LandTours->find()->first();
                    }
                    $infors = json_decode($landtour->payment_information, true);
                    $payment_information = '';
                    if ($infors) {
                        foreach ($infors as $infor) {
                            $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                        }
                    }
                    $this->set(compact('listObjs', 'status', 'method', 'booking', 'listAge', 'list_images', 'payment', 'landtour', 'payment_information', 'email'));
                    $this->viewBuilder()->setTemplate('add_form_type_landtour');
                    break;
                case HOTEL:
                    $listObjs = $this->Hotels->find('list')->where(['is_vinhms !=' => 1])->toArray();
                    $this->set(compact('listObjs', 'type', 'status', 'method', 'booking', 'list_images', 'payment', 'email'));
                    $this->viewBuilder()->setTemplate('add_form_type_hotel');
                    break;
                case HOMESTAY:
                    $listObjs = $this->HomeStays->find('list')->toArray();
                    $this->set(compact('listObjs', 'status', 'method', 'booking', 'list_images', 'payment', 'email'));
                    $this->viewBuilder()->setTemplate('add_form_type_homestay');
                    break;
            }
        }
    }

    public function showListRooms()
    {
        $this->loadModel('Hotels');
        $this->viewBuilder()->enableAutoLayout(false);
        if ($this->request->is('ajax')) {
            $id = $this->request->getQuery('hotel_id');
            $hotel = $this->Hotels->get($id, ['contain' => 'Rooms']);
            $listRoom = [];
            foreach ($hotel->rooms as $room) {
                $listRoom[$room->id] = $room->name;
            }
            $this->set(compact('hotel', 'listRoom'));
        }
    }

    public function getEditedBookingRoom()
    {
        $this->loadModel('Rooms');
        $this->loadModel('BookingRooms');
        $this->viewBuilder()->enableAutoLayout(false);

        $hotel_id = $this->getRequest()->getQuery('hotel_id');
        $booking_id = $this->getRequest()->getQuery('booking_id');

        $listRoom = $this->Rooms->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['hotel_id' => $hotel_id]);

        $booking_rooms = $this->BookingRooms->find()->where(['booking_id' => $booking_id]);

        $this->set(compact('booking_rooms', 'listRoom'));
    }

    public function addSelectChildAge()
    {
        $this->loadModel('Rooms');
        $response = ['success' => false, 'data' => '', 'errors' => []];

        $this->viewBuilder()->enableAutoLayout(false);
        $data = $this->getRequest()->getData();
        $key = $firstKey = array_key_first($data['booking_rooms']);
        $roomData = array_values($data['booking_rooms'])[0];
        $room = $this->Rooms->find()->where(['id' => $roomData['room_id']])->first();
        if ($room) {
            $roomTotalMaxPeople = $room->max_people * $roomData['num_room'];
            if (($roomData['num_adult'] + $roomData['num_children']) <= $roomTotalMaxPeople) {
                $numChildren = $roomData['num_children'];
                $this->set(compact('numChildren', 'room'));
                $response['data'] = $this->render('add_select_child_age')->body();
                $response['success'] = true;
            } else {
                $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                $numChildren = 0;
            }
        } else {
            $response['errors']['booking_rooms'][$key] = ['num_people' => ['Chưa chọn hạng phòng']];
        }

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function changeHotelSurcharge()
    {
        $this->loadModel('HotelSurcharges');
        $this->loadModel('BookingSurcharges');
        $this->loadModel('Hotels');
        $this->viewBuilder()->enableAutoLayout(false);
        $hotel_id = $this->getRequest()->getQuery('hotel_id');
        $booking_id = $this->getRequest()->getQuery('booking_id');
        $arr_booking_surcharges = [];
        if ($booking_id) {
            $booking_surcharges = $this->BookingSurcharges->find()->where(['booking_id' => $booking_id])->toArray();
            foreach ($booking_surcharges as $booking_surcharge) {
                $arr_booking_surcharges[$booking_surcharge['surcharge_type']] = $booking_surcharge;
            }
        }

        $hotel = $this->Hotels->get($hotel_id);
        $infors = json_decode($hotel->payment_information, true);
        $payment_information = '';
        if ($infors) {
            foreach ($infors as $infor) {
                $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
            }
        }

        $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
        $normalSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type NOT IN' => $constantAutoSurcharge, 'hotel_id' => $hotel_id]);
        $autoSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type IN' => $constantAutoSurcharge, 'hotel_id' => $hotel_id]);
        $this->set(compact('normalSurcharges', 'autoSurcharges', 'arr_booking_surcharges', 'payment_information'));

        $response['surcharge'] = $this->render('change_hotel_surcharge_v2')->body();
        $response['payment_information'] = $this->render('payment_information')->body();

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function calBookingHotelPrice()
    {
        $this->loadModel('Rooms');
        $this->loadModel('HotelSurcharges');
        $this->loadModel('Hotels');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'errors' => [], 'data_auto_surcharge' => '', 'data_surcharge_price' => [], 'data_booking_rooms' => [], 'total_price' => 0, 'total_revenue' => 0];
        $data = $this->getRequest()->getQuery();
        $data['sale_id'] = $this->Auth->user('id');
        $validate = $this->Bookings->newEntity($data, ['validate' => 'calBookingHotel', 'associated' => ['BookingRooms']]);
        if ($validate->getErrors()) {
            $response['errors'] = $validate->getErrors();
        } else {
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $data['booking_rooms'][$key]['start_date'] = \DateTime::createFromFormat('d/m/Y', $booking_room['start_date'])->format('d-m-Y');
                    $data['booking_rooms'][$key]['end_date'] = \DateTime::createFromFormat('d/m/Y', $booking_room['end_date'])->format('d-m-Y');
                }
            }

            $hotel = $this->Hotels->get($data['item_id'], ['contain' => ['HotelSurcharges']]);

            $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
            $hotelSurchargeLists = Hash::extract($hotel->hotel_surcharges, '{n}.surcharge_type');
            $hotelAutoSurcharges = array_values(array_intersect($constantAutoSurcharge, $hotelSurchargeLists));
            $total_price = $revenue = 0;

            $bookingStr = 'Khách sạn ' . $hotel->name . '. ';
            $data_booking_rooms = [];
            $isAllow = true;
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                            if ($booking_room['start_date'] && $booking_room['end_date']) {
                                $calSDate = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                                $calEDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                                $dates = $this->Util->_dateRange($calSDate, $calEDate);
                            } else {
                                $dates[] = date('Y-m-d');
                            }

                            if ($booking_room['num_room']) {
                                $num_room = $booking_room['num_room'];
                            } else {
                                $num_room = 1;
                            }
                            $bookingRoomPrice = $tmpRevenue = 0;
                            $singleRoomPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $calSDate);
                            $calDateRevenue = $this->Util->calculateHotelRevenue($hotel, $room->id, $calSDate);
                            foreach ($dates as $date) {
                                $resPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $date);
                                if ($resPrice['status']) {
                                    $bookingRoomPrice += $resPrice['price'];
                                    $tmpRevenue += $this->Util->calculateHotelRevenue($hotel, $room->id, $date);
                                } else {
                                    $response['success'] = false;
                                    $response['errors']['incorrect_info'] = ['message' => $resPrice['message']];
                                    break;
                                }
                            }
                            $total_price += $bookingRoomPrice * $num_room;
                            $totalBookingRoomPrice = $bookingRoomPrice * $num_room;
                            $totalRevenue = $tmpRevenue * $num_room;
                            $revenue += $totalRevenue;
                            if ($data['sale_id'] != $data['user_id']) {
                                $singleRoomPrice['price'] -= $calDateRevenue;
                                $totalBookingRoomPrice -= $totalRevenue;
                            }
                            $bookingStr .= 'Hạng phòng ' . $room->name . ', checkin ' . $booking_room['start_date'] . ', check out ' . $booking_room['end_date'] . ', ';
                            $bookingStr .= $booking_room['num_adult'] . ' người lớn, ' . $booking_room['num_children'] . ' trẻ em.';
                            $data_booking_rooms[$key]['room_single_price'] = $singleRoomPrice['price'];
                            $data_booking_rooms[$key]['room_total_price'] = $totalBookingRoomPrice;
                            dd($data_booking_rooms);
                        } else {
                            $response['success'] = false;
                            $isAllow = false;
                            $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người.']];
                        }
                    } else {
                        $response['success'] = false;
                        $isAllow = false;
                        $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                    }
                }
            }
            if ($isAllow) {
                $data_surcharges = [];
                foreach ($hotelAutoSurcharges as $surcharge_id) {
                    if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                        $priceCalculated = $this->Util->calHotelSurcharge($hotel, $data['booking_rooms'], $surcharge_id, 0, 0);
                        $data_surcharges[$this->Util->getSurchargeId($surcharge_id)] = $priceCalculated;
                        $data_surcharges[$this->Util->getSurchargeId($surcharge_id, false)] = $priceCalculated;
                        $total_price += $priceCalculated;
                    }
                }
                if (isset($data['booking_surcharges']) && !empty($data['booking_surcharges'])) {
                    foreach ($data['booking_surcharges'] as $key => $booking_surcharge) {
                        if (isset($booking_surcharge['quantity']) && !empty($booking_surcharge['quantity'])) {
                            $quantity = (isset($booking_surcharge['quantity'])) ? $booking_surcharge['quantity'] : 0;
                            if ($booking_surcharge['surcharge_type'] == SUR_OTHER) {
                                $hotel_surcharge = $this->HotelSurcharges->get($booking_surcharge['id']);
                                $other_id = $hotel_surcharge->other_slug;
                            } else {
                                $other_id = '';
                            }
                            $priceCalculated = $this->Util->calHotelSurcharge($hotel, $data['booking_rooms'], $booking_surcharge['surcharge_type'], $quantity, $booking_surcharge['id']);
                            $data_surcharges[$this->Util->getSurchargeId($booking_surcharge['surcharge_type'], $other_id)] = $priceCalculated;
                            $data_surcharges[$this->Util->getSurchargeId($booking_surcharge['surcharge_type'], $other_id, false)] = $priceCalculated;
                            $total_price += $priceCalculated;
                        }
                    }
                }
                $response['data_surcharge_price'] = $data_surcharges;
                $response['total_price'] = $total_price;
//                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                $data['agency_discount'] = 0;

                if ($data['sale_id'] != $data['user_id']) {
                    $response['total_price'] -= $revenue;
                }
                $response['total_price'] -= intval($data['agency_discount']);
                if ($response['total_price'] >= 0) {
                    $response['success'] = true;
                }
                $response['total_revenue'] = $revenue;
                $response['booking_str'] = $bookingStr;
                $response['data_booking_rooms'] = $data_booking_rooms;
            }

        }

        $output = $this->getResponse();
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function addInforIfSpecial()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Vouchers');
        $response = ['success' => false, 'message' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $voucher = $this->Vouchers->get($data['item_id'], ['contain' => 'Hotels']);
            if ($voucher->hotel->is_special == 1) {
                $response['success'] = true;
            }
        }
        $response['moreInfor'] = $this->render('get_voucher_hotel_is_speacial')->body();
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function addSelectChildAgeLandtour()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('LandTours');
        if ($this->request->is('ajax')) {
            $numChild = $this->request->getQuery('num_child');
            $landtour = $this->LandTours->get($this->request->getQuery('landtour_id'), ['contain' => 'LandTourSurcharges']);
            $option = !empty($landtour->land_tour_surcharges) ? json_decode($landtour->land_tour_surcharges[0]->options) : [];
            $sAge = !empty($option) ? $option[0]->start : 0;
            $eAge = !empty($option) ? $option[count($option) - 1]->end : 17;
            $listAge = [];
            for ($i = $sAge; $i <= $eAge; $i++) {
                $listAge[$i] = $i;
            }
            $this->set(compact('numChild', 'listAge'));
        }
    }

    public function updateTotalPriceLandtour()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('LandTours');
        $response = ['success' => true, 'messsage' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            if (!isset($data['accessroy'])) {
                $data['accessroy'] = [0];
            }
            if (!isset($data['drive_surchage_pickup'])) {
                $data['drive_surchage_pickup'] = 0;
            }
            if (!isset($data['drive_surchage_drop'])) {
                $data['drive_surchage_drop'] = 0;
            }
            $landtour = $this->LandTours->get($data['item_id'], ['contain' => [
                'LandTourAccessories' => function ($q) use ($data) {
                    return $q->where(['id IN' => $data['accessroy']]);
                },
                'LandTourUserPrices' => function ($q) use ($data) {
                    return $q->where(['user_id' => $data['user_id']]);
                },
                'LandTourDrivesurchages' => function ($q) use ($data) {
                    return $q->where(['id IN' => [$data['drive_surchage_pickup'], $data['drive_surchage_drop']]]);
                }
            ]]);
            $totalPrice = 0;
            if (count($landtour->land_tour_accessories) > 0) {
                foreach ($landtour->land_tour_accessories as $accessory) {
                    $totalPrice += $accessory->adult_price;
                }
            }
            if (count($landtour->land_tour_user_prices) > 0) {
                $totalPrice += $landtour->price + $landtour->customer_price + $landtour->land_tour_user_prices[0]->price;
            } else {
                $totalPrice += $landtour->price + $landtour->trippal_price + $landtour->customer_price;
            }
            $totalPrice = $totalPrice * $data['booking_landtour']['num_adult'] + $totalPrice * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $totalPrice * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
            $revenue = $landtour->customer_price * $data['booking_landtour']['num_adult'] + $landtour->customer_price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $landtour->customer_price * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
            $tempDriveSurchage = 0;
            if (count($landtour->land_tour_drivesurchages) == 1) {
                $tempDriveSurchage = $data['booking_landtour']['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult;
                if ($tempDriveSurchage > $landtour->land_tour_drivesurchages[0]->price_crowd) {
                    $tempDriveSurchage = $landtour->land_tour_drivesurchages[0]->price_crowd;
                }
                if ($data['drive_surchage_pickup'] == 0 || $data['drive_surchage_drop'] == 0) {
                    $tempDriveSurchage = $tempDriveSurchage / 2;
                }
            } elseif (count($landtour->land_tour_drivesurchages) == 2) {
                $tempDriveSurchage1 = $data['booking_landtour']['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult * 0.5;
                if ($tempDriveSurchage1 > $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5) {
                    $tempDriveSurchage1 = $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5;
                }
                $tempDriveSurchage2 = $data['booking_landtour']['num_adult'] * $landtour->land_tour_drivesurchages[1]->price_adult * 0.5;
                if ($tempDriveSurchage2 > $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5) {
                    $tempDriveSurchage2 = $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5;
                }
                $tempDriveSurchage = $tempDriveSurchage1 + $tempDriveSurchage2;
            }
            $driveSurchage = $tempDriveSurchage;
            $totalPrice += $driveSurchage;
            if ($data['payment_method'] == AGENCY_PAY) {
                $totalPrice = $totalPrice - $revenue;
            }
            if ($data['payment_method'] == MUSTGO_DEPOSIT) {
                $totalPrice = $totalPrice - $revenue;
            }
            if (isset($data['sale_discount'])) {
                $data['sale_discount'] = str_replace(',', '', $data['sale_discount']);
            }
            if (isset($data['agency_discount'])) {
                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
            }
            if ($data['sale_discount'] == '') {
                $data['sale_discount'] = 0;
            }
            if ($data['agency_discount'] == '') {
                $data['agency_discount'] = 0;
            }
            $totalPrice += $data['sale_discount'];

            $response['price'] = number_format($totalPrice);
            $response['drive_surchage'] = number_format($driveSurchage);
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;

    }

    public function updateTotalPriceHomestay()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('HomeStays');
        $response = ['success' => true, 'messsage' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $homestay = $this->HomeStays->get($data['item_id'], ['contain' => 'PriceHomeStays']);
            $start_date = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $end_date = $this->Util->formatSQLDate(\DateTime::createFromFormat('d/m/Y', $data['end_date'])->modify('-1 day')->format('d/m/Y'), 'd/m/Y');
            $dateArray = $this->Util->_dateRange($start_date, $end_date);
            $totalPrice = 0;
            if ($data['amount'] == "") {
                $amount = 0;
            } else {
                $amount = $data['amount'];
            }
            foreach ($dateArray as $date) {
                $totalPrice += $this->Util->countingHomeStayPrice($date, $homestay);
            }
            $totalPrice = $totalPrice * $amount;
            if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
                $totalPrice = $totalPrice - ($amount * $homestay->price_customer);
            }
            $response['price'] = number_format($totalPrice);
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function updateTotalPriceVoucher()
    {
        $this->loadModel('Vouchers');
        $response = ['success' => true, 'messsage' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $voucher = $this->Vouchers->get($data['item_id']);
            $totalPrice = 0;
            $totalPrice = ($voucher->price + $voucher->trippal_price + $voucher->customer_price) * $data['amount'];
            if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
                $totalPrice = $totalPrice - ($data['amount'] * $voucher->customer_price);
            }
            $response['price'] = number_format($totalPrice);
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function calculateSurcharge()
    {

    }

    public function getSaleBooking()
    {
        $response = ['success' => false];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $booking = $this->Bookings->get($data['booking_id']);
            $booking = $this->Bookings->patchEntity($booking, ['sale_id' => $this->Auth->user('id'), 'user_id' => $this->Auth->user('id')]);
            if ($this->Bookings->save($booking)) {
                $response['success'] = true;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function getVinSaleBooking()
    {
        $this->loadModel('Vinhmsbookings');
        $response = ['success' => false];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $booking = $this->Vinhmsbookings->get($data['booking_id']);
            $booking = $this->Vinhmsbookings->patchEntity($booking, ['sale_id' => $this->Auth->user('id'), 'user_id' => $this->Auth->user('id')]);
            if ($this->Vinhmsbookings->save($booking)) {
                $response['success'] = true;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function addRoomHotelAnotherBooking($hotelId = null)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Hotels');
        $hotel = $this->Hotels->get($hotelId, ['contain' => ['Rooms']]);
        $listRoom = [];
        foreach ($hotel->rooms as $room) {
            $listRoom[$room->id] = $room->name;
        }
        $this->set(compact('listRoom'));
    }

    public function addListChildAgeAnother()
    {
        $this->loadModel('Rooms');
        $response = ['success' => false];
        $this->viewBuilder()->enableAutoLayout(false);
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $key = $firstKey = array_key_first($data['booking_rooms']);
            $roomData = array_values($data['booking_rooms'])[0];
            $room = $this->Rooms->find()->where(['id' => $roomData['room_id']])->first();
            if ($room) {
                if (!$roomData['num_adult']) {
                    $roomData['num_adult'] = 0;
                }
                if (!$roomData['num_room']) {
                    $roomData['num_room'] = 0;
                }
                $roomTotalMaxPeople = $room->max_people * $roomData['num_room'];
                if (($roomData['num_adult'] + $roomData['num_children']) <= $room->max_people) {
                    $numChildren = $roomData['num_children'];
                    $this->set(compact('numChildren', 'room'));
                    $response['data'] = $this->render('add_select_child_age')->body();
                    $response['success'] = true;
                } else {
                    $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                }
            } else {
                $response['errors']['booking_rooms'][$key] = ['num_people' => ['Chưa chọn hạng phòng']];
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function bookingChangeObject()
    {
        $this->loadModel('LandTours');
        $this->loadModel('HomeStays');
        $this->loadModel('Vouchers');
        $this->viewBuilder()->enableAutoLayout(false);
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            if ($data['booking_type'] == LANDTOUR) {
                $object = $this->LandTours->get($data['objectId'], ['contain' => [
                    'LandTourAccessories',
                    'LandTourUserPrices' => function ($q) use ($data) {
                        return $q->where(['user_id' => $data['user_id']]);
                    }]]);

                $infors = json_decode($object->payment_information, true);
                $payment_information = '';
                if ($infors) {
                    foreach ($infors as $infor) {
                        $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                    }
                }
                $this->set(compact('payment_information'));
            }

        }
    }

    public function addLandtourAccessories($landtourId)
    {
        $this->loadModel('LandTourAccessories');
        $this->loadModel('LandTourDrivesurchages');
        $this->viewBuilder()->enableAutoLayout(false);
        if ($this->request->is('ajax')) {
            $landtourAccessories = $this->LandTourAccessories->find()->where(['land_tour_id' => $landtourId]);
            $landtourDriveSurchages = $this->LandTourDrivesurchages->find('list')->where(['land_tour_id' => $landtourId]);
            $this->set(compact('landtourAccessories', 'landtourDriveSurchages'));
        }
    }

    public function changeAgency($id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'email' => ''];
        if ($this->request->is('ajax')) {
            $this->loadModel('Users');
            $user = $this->Users->get($id);
            if ($user && $user->role_id == 3) {
                $response['success'] = true;
                $response['email'] = $user->email;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function manageLandtour()
    {
        $this->viewBuilder()->setLayout('backend');
        $this->loadModel('Bookings');
        $this->loadModel('LandtourPaymentFees');
        $user_id = $this->Auth->user('id');

        if ($this->request->getQuery()) {
            $currentDay = $this->request->getQuery('current_day');
        }
        if (!isset($currentDay)) {
            $currentDay = date("d/m/Y");
        }
        $bookings = $this->Bookings->find()->contain([
            'Users',
            'Sales',
            'LandTours',
            'LandTours.Destinations',
            'BookingLandtours',
            'BookingLandtourAccessories',
            'BookingLandtourAccessories.LandTourAccessories',
            'BookingLandtours.PickUp',
            'BookingLandtours.DropDown',
        ])->where([
            'Bookings.sale_id' => $this->Auth->user('id'),
            'Bookings.type' => LANDTOUR,
            'Bookings.start_date' => $this->Util->formatSQLDate($currentDay, 'd/m/Y'),
        ])->order(['Bookings.start_date' => 'DESC'])->toArray();
        $this->set(compact('currentDay', 'bookings'));
    }

    public function sendVinRequestPayment($bookingId)
    {
        $response = ['success' => false, 'message' => ''];
        $this->loadModel('Vinhmsbookings');
        $vinBooking = $this->Vinhmsbookings->get($bookingId, ['contain' => ['Hotels', 'VinhmsbookingRooms', 'Users', 'Vinpayments']]);
        $resSendMail = $this->_sendVinRequestPayment($vinBooking);
        if ($resSendMail['success']) {
            $response['success'] = true;
            $response['message'] = $resSendMail['message'];
        }

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function searchForVinPackage()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('Vinrooms');
        $this->loadModel('Vinhmsallotments');
        $this->loadModel('Hotels');
        $dataPost = $this->request->getData();
        $hotel = $this->Hotels->get($dataPost['hotel_id']);
        $listAllotments = $this->Vinhmsallotments->find()
            ->where([
                'hotel_id' => $hotel->id,
            ]);
        $allotmentRoom = [];
        foreach ($listAllotments as $k => $singleRoom) {
            if ($singleRoom->vinroom_code == $dataPost['room_id']) {
                if (!isset($allotmentRoom[$singleRoom->code])) {
                    $allotmentRoom[$singleRoom->code][$singleRoom->vinroom_code] = [
                        'sale_revenue_type' => $singleRoom->sale_revenue_type,
                        'sale_revenue' => $singleRoom->sale_revenue,
                        'revenue_type' => $singleRoom->revenue_type,
                        'revenue' => $singleRoom->revenue,
                    ];
                } else {
                    $allotmentRoom[$singleRoom->code][$singleRoom->vinroom_code] = [
                        'sale_revenue_type' => $singleRoom->sale_revenue_type,
                        'sale_revenue' => $singleRoom->sale_revenue,
                        'revenue_type' => $singleRoom->revenue_type,
                        'revenue' => $singleRoom->revenue,
                    ];
                }
            }
        }

        $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dataPost['start_date'])));
        $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dataPost['end_date'])));
        $data = [
            "arrivalDate" => $startDate,
            "departureDate" => $endDate,
            "numberOfRoom" => 1,
            "propertyIds" => [$hotel->vinhms_code],
            "roomOccupancy" => []
        ];
        empty($data['roomOccupancy']);
        $roomOccupancy = [
            'numberOfAdult' => $dataPost['num_adult'],
            'otherOccupancies' => [
                [
                    'otherOccupancyRefCode' => 'child',
                    'quantity' => $dataPost['num_child']
                ],
                [
                    'otherOccupancyRefCode' => 'infant',
                    'quantity' => $dataPost['num_kid']
                ]
            ]
        ];
        $data['roomOccupancy'] = $roomOccupancy;
        $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
        $listRoom = [];
        $dateDiff = date_diff(date_create($startDate), date_create($endDate));
        if (isset($dataApi['isSuccess'])) {
            if (!empty($dataApi['data']['rates'])) {
                foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $k => $singleRoom) {
                    if ($singleRoom['id'] == $dataPost['room_id']) {
                        $dataVinroom = $this->Vinrooms->find()->where(['vin_code' => $singleRoom['id']])->first();
                        if ($dataVinroom) {
                            $json = json_decode($dataVinroom->thumbnail, true);
                            if (empty($json)) {
                                $json[] = '/img/room.png';
                            }
                            $image = $json;
                        } else {
                            $image = ['/img/room.png'];
                            $json[0] = '/img/room.png';
                        }
                        $listRoom[$singleRoom['id']] = [
                            'image' => $image,
                            'information' => [
                                'image' => $json[0],
                                'name' => $singleRoom['name'],
                                'description' => $singleRoom['description'],
                                'maxAdult' => $singleRoom['maxAdult'],
                                'maxChild' => $singleRoom['maxChild'],
                                'squareUnit' => $singleRoom['squareUnit'],
                                'squareUnitType' => isset($singleRoom['squareUnitType']) ? $singleRoom['squareUnitType'] : "",
                                'min_price' => 999999999,
                                'extends' => $dataVinroom && $dataVinroom->extends ? json_decode($dataVinroom->extends, true) : []
                            ]
                        ];
                    }
                }
            }
            if (!empty($dataApi['data']['rates'])) {
                foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
                    $hasSpecialPackage = false;
                    $firstAllotment = $ratePackage['rateAvailablity']['allotments'][0];
                    foreach ($ratePackage['rateAvailablity']['allotments'] as $singleAllotmentCheck) {
                        if ($firstAllotment['quantity'] < $singleAllotmentCheck['quantity']) {
                            $firstAllotment = $singleAllotmentCheck;
                        }
                    }
                    $ratePackage['rateAvailablity']['allotments'][0] = $firstAllotment;
                    if (isset($ratePackage['rateAvailablity']['allotments'][0]) && isset($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']])) {
                        $hasSpecialPackage = true;
                    }

                    if ($hotel->price_agency_type == 0) {
                        $ratePackage['trippal_price'] = $hotel->price_agency * $dateDiff->days;
                    } else {
                        $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $hotel->price_agency / 100);
                    }
                    if ($hotel->price_agency_type == 0) {
                        $ratePackage['customer_price'] = $hotel->price_customer * $dateDiff->days;
                    } else {
                        $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $hotel->price_customer / 100);
                    }

                    $vinRoom = $this->Vinrooms->find()->where(['vin_code' => $ratePackage['roomTypeID'], 'hotel_id' => $hotel->id])->first();
                    if ($vinRoom) {
                        if ($vinRoom->trippal_price_type == 0) {
                            $ratePackage['trippal_price'] = $vinRoom->trippal_price != 0 ? $vinRoom->trippal_price * $dateDiff->days : true;
                        } else {
                            $ratePackage['trippal_price'] = $vinRoom->trippal_price != 0 ? intval(intval($ratePackage['totalAmount']['amount']['amount']) * $vinRoom->trippal_price / 100) : true;
                        }
                        if ($vinRoom->customer_price_type == 0) {
                            $ratePackage['customer_price'] = $vinRoom->customer_price != 0 ? $vinRoom->customer_price * $dateDiff->days : true;
                        } else {
                            $ratePackage['customer_price'] = $vinRoom->customer_price != 0 ? intval(intval($ratePackage['totalAmount']['amount']['amount']) * $vinRoom->customer_price / 100) : true;
                        }
                    }
                    $ratePackage['amount_left'] = $ratePackage['rateAvailablity']['allotments'][0]['quantity'];
                    if ($hasSpecialPackage) {
                        if (!isset($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']])) {

                        } else {
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] != 0) {
                                if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue_type'] == 0) {
                                    $ratePackage['trippal_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] * $dateDiff->days;
                                } else {
                                    $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['sale_revenue'] / 100);
                                }
                            }
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] != 0) {
                                if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue_type'] == 0) {
                                    $ratePackage['customer_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] * $dateDiff->days;
                                } else {
                                    $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleRoom['id']]['revenue'] / 100);
                                }
                            }
                        }
                    }
                    if (isset($listRoom[$ratePackage['roomTypeID']])) {
                        $tmpPrice = $ratePackage['rateAvailablity']['amount'] + $ratePackage['trippal_price'] + $ratePackage['customer_price'];
                        $listRoom[$ratePackage['roomTypeID']]['information']['min_price'] > $tmpPrice ? $listRoom[$ratePackage['roomTypeID']]['information']['min_price'] = $tmpPrice : true;
                        $listRoom[$ratePackage['roomTypeID']]['package'][] = $ratePackage;
                    }
                }
            }
        }
        uasort($listRoom, function ($item1, $item2) {
            return $item1['information']['min_price'] <=> $item2['information']['min_price'];
        });
        $roomIndex = $dataPost['room_index'];
        $this->set(compact('listRoom', 'roomIndex', 'dateDiff'));
        $this->render('search_for_vin_package')->body();
//        $response = [
//            'success' => true,
//            'html' => $this->render('search_for_vin_package')->body()
//        ];

//        $output = $this->response;
//        $output = $output->withType('json');
//        $output = $output->withStringBody(json_encode($response));
//        return $output;
    }

    public function addSearchPackageVin()
    {
        $response = [
            'html_package' => null,
            'room_total' => 0,
            'total_vin_booking_price' => 0,
            'total_vin_booking_revenue' => 0,
            'total_agency_pay_vin_booking' => 0,
        ];
        $this->viewBuilder()->enableAutoLayout(false);
        $listRoom = $this->request->getData();
        $fromDate = $listRoom['start_date'];
        $toDate = $listRoom['end_date'];
        $this->set(compact('listRoom', 'fromDate', 'toDate'));
        $response['html_package'] = $this->render('add_search_package_vin')->body();
        $response['html_package_input'] = $this->render('add_search_package_vin_input')->body();

        $listRoom['packagePrice'] = str_replace(',', '', $listRoom['packagePrice']);
        $listRoom['packagePrice'] = str_replace('.', '', $listRoom['packagePrice']);
        $listRoom['roomIndexPrice'] = str_replace(',', '', $listRoom['roomIndexPrice']);
        $listRoom['roomIndexPrice'] = str_replace('.', '', $listRoom['roomIndexPrice']);
        $listRoom['totalVinBookingPrice'] = str_replace(',', '', $listRoom['totalVinBookingPrice']);
        $listRoom['totalVinBookingPrice'] = str_replace('.', '', $listRoom['totalVinBookingPrice']);
        $listRoom['revenue'] = str_replace(',', '', $listRoom['revenue']);
        $listRoom['revenue'] = str_replace('.', '', $listRoom['revenue']);
        $listRoom['totalVinBookingRevenue'] = str_replace(',', '', $listRoom['totalVinBookingRevenue']);
        $listRoom['totalVinBookingRevenue'] = str_replace('.', '', $listRoom['totalVinBookingRevenue']);
        $listRoom['totalAgencyPayVinBooking'] = str_replace(',', '', $listRoom['totalAgencyPayVinBooking']);
        $listRoom['totalAgencyPayVinBooking'] = str_replace('.', '', $listRoom['totalAgencyPayVinBooking']);


        $response['room_total'] = number_format($listRoom['packagePrice'] + $listRoom['roomIndexPrice']);
        $response['total_vin_booking_price'] = number_format($listRoom['packagePrice'] + $listRoom['totalVinBookingPrice']);
        $response['total_vin_booking_revenue'] = number_format($listRoom['revenue'] + $listRoom['totalVinBookingRevenue']);
        $response['total_agency_pay_vin_booking'] = number_format($listRoom['packagePrice'] - $listRoom['revenue'] + $listRoom['totalAgencyPayVinBooking']);

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function _sendVinRequestPayment($booking)
    {
        $this->loadComponent('Email');
        $this->loadModel('Users');
        $bodyEmail = 'Đơn hàng thanh toán cho booking: ' . $booking->code;

        $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng và đề nghị thanh toán - " . $booking->hotel->name . " - " . $booking->first_name . " " . $booking->sur_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        $data_sendEmail = [
            'to' => $booking->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $sale = $this->Users->get($booking->sale_id);
        $response = $this->Email->sendVinRequestPayment($data_sendEmail, $sale->email, $sale->email_access_code);

        return $response;
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 2 || $user['role_id'] === 5)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }

    private function codeToId($text)
    {
        while ($text[0] == 0) {
            $text = substr($text, 1, strlen($text) - 1);
        }
        return $text;
    }

    public function editVinpearl($id)
    {
        $this->loadModel('Vinhmsbookings');
        $booking = $this->Vinhmsbookings->find()->where(['id' => $id])->first();
        $this->set(compact('booking'));
    }

    public function saveEditVinpearl()
    {
        $this->loadModel('Vinhmsbookings');
        $data = $this->request->getQuery();
        $listName = [];
        foreach ($data['name'] as $key => $name) {
            $roomName = [];
            foreach ($name as $k => $item) {
                $text['name'] = $item;
                $text['birthday'] = $data['birthday'][$key][$k];
                $roomName[] = $text;
            }
            $listName[] = $roomName;
        }
        $vinInfo = json_encode($listName);
        $sData = [];
        $sData['first_name'] = $data['first_name'];
        $sData['sur_name'] = $data['sur_name'];
        $sData['phone'] = $data['phone'];
        $sData['nationality'] = $data['nationality'];
        $sData['nation'] = $data['nation'];
        $sData['email'] = $data['email'];
        $sData['agency_discount'] = $data['agency_discount'];
        $sData['change_price'] = $data['change_price'];
        $sData['note'] = $data['note'];
        $sData['vin_information'] = $vinInfo;
        $booking = $this->Vinhmsbookings->find()->where(['id' => $data['id']])->first();
        $booking = $this->Vinhmsbookings->patchEntity($booking, $sData);
        $this->Vinhmsbookings->save($booking);
        $this->set(compact('booking'));
        return $this->render('editVinpearl');

    }

    public function saveCommentLogs() {
        $this->loadModel ('BookingLogs');
        $lData = $this->request->getQuery();
//        dd($lData);
        $userLogs = $this->Auth->user();
        $title = [];
        switch (intval($lData['title'])){
            case 1:
                $title = 'Comment Booking';
                break;
            case 2:
                $title = 'Edit Booking';
                break;
            case 3:
                $title = 'Sửa giá, UNC';
        }
        if(isset($lData['cmt'])){
            // create log booking
            $dataLog = [];
            $dataLog['user_id'] = $userLogs['id'];
            $dataLog['booking_id'] = intval($lData['id']);
            $dataLog['code'] = $lData['code'];
            $dataLog['title'] = $title;
            $dataLog['comment'] = $lData['cmt'];
            $dataLog['type'] = 1;
            $dataLog['status'] = 1;
//            dd($dataLog);
            $bookingLogs = $this->BookingLogs->newEntity();
            $bookingLogs = $this->BookingLogs->patchEntity($bookingLogs, $dataLog);
            $this->BookingLogs->save($bookingLogs);
//            var_dump('save');
//            dd('save');
            // end create log booking
            return $this->render('view');
        }
    }

    public function indexBooking()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
            $confirmAgencyPay = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Date(Bookings.start_date) >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Date(Bookings.end_date) <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        $condition['Bookings.status IN'] = [0, 1, 2, 3, 4, 5];
        $condition['Bookings.type !='] = LANDTOUR;
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'BookingSurcharges',
            'BookingRooms',
        ])->where($condition)->order(['Bookings.created' => 'DESC'])->limit(15);
        $bookings = $this->paginate($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            $booking->payment = $payment;
        }
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function indexBookingDatatable()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getData();
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Date(Bookings.start_date) >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Date(Bookings.end_date) <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
        }
        if (isset($data['query']['Type']) && $data['query']['Type']) {
            // do condition here
        }
        $condition['Bookings.status IN'] = [0, 1, 2, 3, 4, 5];
        $condition['Bookings.type !='] = LANDTOUR;
        if (isset($data['query'])) {
            $keyword = $data['query'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'BookingSurcharges',
            'BookingRooms',
        ])->where($condition)->order(['Bookings.created' => 'DESC'])->limit(15);
        $bookings = $this->paginate($bookings);

//        dd($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            $booking->created = date_format($booking->created, 'd/m/Y');
            $status = "";
            $statuscls = "";
            switch ($booking->status){
                case 0:
                    $status = "Đại lý mới đặt";
                    $statuscls = "label-light-primary";
                    break;
                case 1:
                    $status = "Chờ KS mail XN, gửi mail XN và ĐNTT";
                    $statuscls = "label-light-default";
                    break;
                case 2:
                    if ($booking->mail_type == 0){
                        if($booking->payment && $booking->payment->images){
                            $status = "ĐL đã TT, chờ KT xác nhận tiền nổi";
                        } else {
                            $status = "Đã gửi mail xác nhận và đề nghị TT, Chờ ĐL thanh toán";
                        }
                    } else {
                        $status = "ĐL đã TT, chờ KT TT";
                    }
                    $statuscls = "label-light-warning";
                    break;
                case 3:
                    $status = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? "Hoàn thành" : "Hoàn thành";
                    $statuscls = "label-light-danger";
                   break;
                case 4:
                    $status = "Hoàn thành";
                    $statuscls = "label-light-danger";
                    break;
                case 5:
                    $status = "Đã hủy";
                    $statuscls = "label-light-danger";
                    break;
            }
            $loginID = $this->request->session()->read('Auth.User.role_id');
            $booking->statustr = $status;
            $booking->statuscls = $statuscls;
            $booking->payment = $payment;
            $booking->loginID = $loginID;
        }
        $response = [
            'meta' => [
                "page" => 1,
                "pages" => 35,
                "perpage" => 15,
                "total" => 350,
            ],
            'data' => $bookings
        ];
//        dd($bookings);
        $this->set([
            'my_response' => $response,
            '_serialize' => 'my_response',
        ]);
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function indexBookingVin()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Vinpayments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
            $confirmAgencyPay = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Date(Vinhmsbookings.start_date) >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Date(Bookings.end_date) <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        $condition['Vinhmsbookings.status IN'] = [0, 1, 2, 3, 4, 5];
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Vinhmsbookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'VinhmsbookingRooms',
        ])->where($condition)->order(['Vinhmsbookings.created' => 'DESC'])->limit(15);
        $bookings = $this->paginate($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Vinpayments->find()->where(['booking_id' => $booking->id])->first();
            $booking->payment = $payment;
        }
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function indexBookingVinDatatable()
    {
        $this->viewBuilder()->setLayout('backend_new');;
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Vinpayments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getData();
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Date(Vinhmsbookings.start_date) >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Date(Vinhmsbookings.end_date) <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
        }
        if (isset($data['query']['Type']) && $data['query']['Type']) {
            // do condition here
        }
        $condition['Vinhmsbookings.status IN'] = [0, 1, 2, 3, 4, 5];
        if (isset($data['query'])) {
            $keyword = $data['query'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
//                    'full_name LIKE' => '%' . $keyword . '%',
//                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Vinhmsbookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'VinhmsbookingRooms',
        ])->where($condition)->order(['Vinhmsbookings.created' => 'DESC'])->limit(15);
        $bookings = $this->paginate($bookings);

//        dd($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Vinpayments->find()->where(['booking_id' => $booking->id])->first();
            $booking->created = date_format($booking->created, 'd/m/Y');
            $status = "";
            $statuscls = "";
            switch ($booking->status){
                case 0:
                    $status = "Đại lý mới đặt";
                    $statuscls = "label-light-primary";
                    break;
                case 1:
                    $status = "Chờ KS mail XN, gửi mail XN và ĐNTT";
                    $statuscls = "label-light-default";
                    break;
                case 2:
                    if ($booking->mail_type == 0){
                        if($booking->payment && $booking->payment->images){
                            $status = "ĐL đã TT, chờ KT xác nhận tiền nổi";
                        } else {
                            $status = "Đã gửi mail xác nhận và đề nghị TT, Chờ ĐL thanh toán";
                        }
                    } else {
                        $status = "ĐL đã TT, chờ KT TT";
                    }
                    $statuscls = "label-light-warning";
                    break;
                case 3:
                    $status = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? "Hoàn thành" : "Hoàn thành";
                    $statuscls = "label-light-danger";
                    break;
                case 4:
                    $status = "Hoàn thành";
                    $statuscls = "label-light-danger";
                    break;
                case 5:
                    $status = "Đã hủy";
                    $statuscls = "label-light-danger";
                    break;
            }
            $loginID = $this->request->session()->read('Auth.User.role_id');
            $booking->statustr = $status;
            $booking->statuscls = $statuscls;
            $booking->payment = $payment;
            $booking->loginID = $loginID;
        }
        $response = [
            'meta' => [
                "page" => 1,
                "pages" => 35,
                "perpage" => 15,
                "total" => 350,
            ],
            'data' => $bookings
        ];
//        dd($bookings);
        $this->set([
            'my_response' => $response,
            '_serialize' => 'my_response',
        ]);
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function addNewV2()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $this->loadModel('Hotels');
        $this->loadModel('Payments');
        $this->loadModel('HotelSurcharges');
        $this->loadModel('BookingSurcharges');
        $this->loadModel('Rooms');
        $sale_id = $this->Auth->user('id');
        $users = $this->Users->find()->where(['role_id' => 3, 'parent_id' => $sale_id])->all();
        $hotels = $this->Hotels->find()->where(['is_vinhms' => 0])->all();
            $booking_type = [
            SYSTEM_BOOKING => 'Booking thuộc hệ thống',
        ];
        $status = [
            '0' => 'Chưa thanh toán',
            '1' => 'Đã thanh toán'
        ];
        $method = [
            CUSTOMER_PAY => 'Khách hàng chuyển trực tiếp',
            AGENCY_PAY => 'CTV sẽ thu tiền hộ'
        ];
        $booking = $this->Bookings->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $isAllow = true;
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople < ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        $isAllow = false;
                        $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.'));
                        break;
                        if ($roomTotalMaxAdult < $booking_room['num_adult']) {
                            $isAllow = false;
                            $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người.'));
                            break;
                        }
                    }
                }
            }
            if ($isAllow) {
                $item_id = $data['item_id'];
//            $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
//            $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
                $data['sale_id'] = $this->Auth->user('id');
//            if (isset($data['adult_fee'])) {
//                $data['adult_fee'] = str_replace(',', '', $data['adult_fee']);
//            }
//            if (isset($data['children_fee'])) {
//                $data['children_fee'] = str_replace(',', '', $data['children_fee']);
//            }
//            if (isset($data['holiday_fee'])) {
//                $data['holiday_fee'] = str_replace(',', '', $data['holiday_fee']);
//            }
//            if (isset($data['other_fee'])) {
//                $data['other_fee'] = str_replace(',', '', $data['other_fee']);
//            }

                if (isset($data['payment_deadline'])) {
                    $data['payment_deadline'] = $this->Util->formatSQLDate($data['payment_deadline'], 'd/m/Y');
                }
                $data['customer_deposit'] = str_replace(',', '', $data['customer_deposit']);
                $data['is_send_notice'] = 1;
                if (isset($data['sale_discount'])) {
                    $data['sale_discount'] = str_replace(',', '', $data['sale_discount']);
                }
                if (isset($data['agency_discount'])) {
                    $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                }
                $data['booking_type'] == SYSTEM_BOOKING;
                if ($data['booking_type'] == SYSTEM_BOOKING) {
                    switch ($data['type']) {
                        case HOMESTAY:
                            $dataBooking = $this->_buildSaveHomestay($data);
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
                    if ($dataBooking['data'] != null) {
                        if (!isset($dataBooking['data']['sale_discount'])) {
                            $dataBooking['data']['sale_discount'] = 0;
                        }
                        if (!isset($dataBooking['data']['agency_discount'])) {
                            $dataBooking['data']['agency_discount'] = 0;
                        }
                        if ($dataBooking['data']['type'] == LANDTOUR) {
                            if ($dataBooking['data']['payment_method'] == AGENCY_PAY) {
                                $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                                $dataBooking['data']['revenue'] = 0;
                            }
                            if ($dataBooking['data']['payment_method'] == MUSTGO_DEPOSIT) {
                                $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                                $dataBooking['data']['revenue'] = ($dataBooking['data']['mustgo_deposit'] + $dataBooking['data']['customer_deposit']) - $dataBooking['data']['price'];
                            }
                            $dataBooking['data']['price'] += $dataBooking['data']['sale_discount'];
                            $dataBooking['data']['revenue'] -= $dataBooking['data']['agency_discount'];
                        } else {
                            $dataBooking['data']['sale_revenue'] = isset($dataBooking['data']['sale_revenue']) ? $dataBooking['data']['sale_revenue'] + $dataBooking['data']['sale_discount'] : $dataBooking['data']['sale_discount'];
                            if (isset($dataBooking['sale_id']) && isset($dataBooking['user_id']) && $dataBooking['sale_id'] != $dataBooking['user_id']) {
                                $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                                $dataBooking['data']['revenue'] = 0;
                                $dataBooking['data']['payment_method'] = AGENCY_PAY;
                            } else {
                                $dataBooking['data']['payment_method'] = AGENCY_PAY;
                            }
                        }
                        $dataBooking['data']['price'] -= $dataBooking['data']['agency_discount'];
                        $dataBooking['data']['status'] = 0;
                        if (isset($dataBooking['errors']) && $dataBooking['errors']) {
                            $dataBooking['success'] = false;
                            $dataBooking['message'] = $dataBooking['errors']['incorrect_info']['message'];

                        }
                    }
                } else if ($data['booking_type'] == ANOTHER_BOOKING) {
                    $dataBooking = $this->_buildSaveAnother($data);
                }
                if ($dataBooking['success']) {
                    if (!isset($booking['data']['creator_type'])) {
                        $booking['data']['creator_type'] = 1;
                    }
                    $booking = $this->Bookings->patchEntity($booking, $dataBooking['data']);
                    if ($this->Bookings->save($booking)) {
                        $newBooking = $this->Bookings->get($booking->id);
                        if ($booking->type != LANDTOUR) {
                            $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                        } else {
                            $booking = $this->Bookings->patchEntity($booking, ['code' => "MPQ" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                        }
                        $this->Bookings->save($booking);

                        $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
                        if (!$payment) {
                            $payment = $this->Payments->newEntity();
                            $paymentData['booking_id'] = $booking->id;
                        }
                        if (isset($data['payment_type'])) {
                            $paymentData['type'] = $data['payment_type'];
                        }
                        if (isset($data['payment_invoice'])) {
                            $paymentData['invoice'] = $data['payment_invoice'];
                        }
                        if (isset($data['payment_invoice_information'])) {
                            $paymentData['invoice_information'] = $data['payment_invoice_information'];
                        }
                        if (isset($data['payment_address'])) {
                            $paymentData['address'] = $data['payment_address'];
                        }
                        if (isset($data['media'])) {
                            $paymentData['images'] = $data['media'];
                        }
                        if (isset($data['pay_object'])) {
                            $paymentData['pay_object'] = $data['pay_object'];
                            if ($data['pay_object'] == PAY_HOTEL) {
                                $paymentData['check_type'] = $data['check_type'];
                                $paymentData['partner_information'] = '';
                            } else {
                                $paymentData['check_type'] = 0;
                                $paymentData['partner_information'] = [
                                    'name' => $data['partner_name'],
                                    'number' => $data['partner_number'],
                                    'bank' => $data['partner_bank'],
                                    'email' => $data['partner_email']
                                ];
                                $paymentData['partner_information'] = json_encode($paymentData['partner_information'], JSON_UNESCAPED_UNICODE);
                            }
                        }
                        $payment = $this->Payments->patchEntity($payment, $paymentData);
                        $this->Payments->save($payment);
                        $this->Flash->success(__('The booking has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The booking could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__($dataBooking['message']));
                }
            }
        }
        $list_images = '';
        $this->set(compact('booking','users', 'hotels', 'list_images'));
    }

    function viewAdd()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Users');
        $data = $this->request->getQuery();

        $users = $this->Users->find()->where(['role_id' => 0])->toArray();

        $this->set(compact('data' ));
    }

    public function showListRoomsV2()
    {
//        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Hotels');
        $this->viewBuilder()->enableAutoLayout(false);
        if ($this->request->is('ajax')) {
            $id = $this->request->getQuery('hotel_id');
            $hotel = $this->Hotels->get($id, ['contain' => 'Rooms']);
            $listRoom = [];
            foreach ($hotel->rooms as $room) {
                $listRoom[$room->id] = $room->name;
            }
//            dd($listRoom);
            $this->set(compact('hotel', 'listRoom'));
        }
    }

    public function calBookingHotelPriceV2()
    {
        $this->loadModel('Rooms');
        $this->loadModel('HotelSurcharges');
        $this->loadModel('Hotels');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'errors' => [], 'data_auto_surcharge' => '', 'data_surcharge_price' => [], 'data_booking_rooms' => [], 'total_price' => 0, 'total_revenue' => 0];
        $data = $this->getRequest()->getQuery();
        foreach ($data['booking_rooms'] as $key => $booking_room){
            $data['booking_rooms'][$key]['num_children'] = 0;
            $data['booking_rooms'][$key]['child_ages'][0] = '';
            for ($x = 1 ; $x <= intval($data['booking_rooms'][$key]['num_children_0_6']); $x++){
                $data['booking_rooms'][$key]['child_ages'][$x - 1] = '5';
                $data['booking_rooms'][$key]['num_children'] += 1;
            }
            for (  $y = ($x + 1) ; $y <= ($x + intval($data['booking_rooms'][$key]['num_children_7_12'])); $y++){
                $data['booking_rooms'][$key]['child_ages'][$y - 2] = '9';
                $data['booking_rooms'][$key]['num_children'] += 1;
            }
        }
        $data['booking_rooms'][$key]['num_children'] = strval($data['booking_rooms'][$key]['num_children']);
//        dd($data);
        $data['sale_id'] = $this->Auth->user('id');
        $validate = $this->Bookings->newEntity($data, ['validate' => 'calBookingHotel', 'associated' => ['BookingRooms']]);
        if ($validate->getErrors()) {
            $response['errors'] = $validate->getErrors();
        } else {
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $data['booking_rooms'][$key]['start_date'] = \DateTime::createFromFormat('d/m/Y', $booking_room['start_date'])->format('d-m-Y');
                    $data['booking_rooms'][$key]['end_date'] = \DateTime::createFromFormat('d/m/Y', $booking_room['end_date'])->format('d-m-Y');
                }
            }

            $hotel = $this->Hotels->get($data['item_id'], ['contain' => ['HotelSurcharges']]);
            $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
            $hotelSurchargeLists = Hash::extract($hotel->hotel_surcharges, '{n}.surcharge_type');
            $hotelAutoSurcharges = array_values(array_intersect($constantAutoSurcharge, $hotelSurchargeLists));
            $total_price = $revenue = 0;
            $bookingStr = 'Khách sạn ' . $hotel->name . '. ';
            $data_booking_rooms = [];
            $isAllow = true;
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                            if ($booking_room['start_date'] && $booking_room['end_date']) {
                                $calSDate = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                                $calEDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                                $dates = $this->Util->_dateRange($calSDate, $calEDate);
                            } else {
                                $dates[] = date('Y-m-d');
                            }
                            if ($booking_room['num_room']) {
                                $num_room = $booking_room['num_room'];
                            } else {
                                $num_room = 1;
                            }
                            $bookingRoomPrice = $tmpRevenue = 0;
                            $singleRoomPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $calSDate);
                            $calDateRevenue = $this->Util->calculateHotelRevenue($hotel, $room->id, $calSDate);
                            foreach ($dates as $date) {
                                $resPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $date);
                                if ($resPrice['status']) {
                                    $bookingRoomPrice += $resPrice['price'];
                                    $tmpRevenue += $this->Util->calculateHotelRevenue($hotel, $room->id, $date);
                                } else {
                                    $response['success'] = false;
                                    $response['errors']['incorrect_info'] = ['message' => $resPrice['message']];
                                    break;
                                }
                            }
                            $total_price += $bookingRoomPrice * $num_room;
                            $totalBookingRoomPrice = $bookingRoomPrice * $num_room;
                            $totalRevenue = $tmpRevenue * $num_room;
                            $revenue += $totalRevenue;
                            if ($data['sale_id'] != $data['user_id']) {
                                $singleRoomPrice['price'] -= $calDateRevenue;
                                $totalBookingRoomPrice -= $totalRevenue;
                            }
                            $bookingStr .= 'Hạng phòng ' . $room->name . ', checkin ' . $booking_room['start_date'] . ', check out ' . $booking_room['end_date'] . ', ';
                            $bookingStr .= $booking_room['num_adult'] . ' người lớn, ' . $booking_room['num_children'] . ' trẻ em.';
                            $data_booking_rooms[$key]['room_single_price'] = $singleRoomPrice['price'];
                            $data_booking_rooms[$key]['room_total_price'] = $totalBookingRoomPrice;
                            $data_booking_rooms[$key]['num_children'] = $booking_room['num_children'];
                        } else {
                            $response['success'] = false;
                            $isAllow = false;
                            $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người.']];
                        }
                    } else {
                        $response['success'] = false;
                        $isAllow = false;
                        $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                    }
                }
            }
            if ($isAllow) {
                $data_surcharges = [];
                foreach ($hotelAutoSurcharges as $surcharge_id) {
                    if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                        $priceCalculated = $this->Util->calHotelSurcharge($hotel, $data['booking_rooms'], $surcharge_id, 0, 0);
                        $data_surcharges[$this->Util->getSurchargeId($surcharge_id)] = $priceCalculated;
                        $data_surcharges[$this->Util->getSurchargeId($surcharge_id, false)] = $priceCalculated;
                        $total_price += $priceCalculated;
                    }
                }
                if (isset($data['booking_surcharges']) && !empty($data['booking_surcharges'])) {
                    foreach ($data['booking_surcharges'] as $key => $booking_surcharge) {
                        if (isset($booking_surcharge['quantity']) && !empty($booking_surcharge['quantity'])) {
                            $quantity = (isset($booking_surcharge['quantity'])) ? $booking_surcharge['quantity'] : 0;
                            if ($booking_surcharge['surcharge_type'] == SUR_OTHER) {
                                $hotel_surcharge = $this->HotelSurcharges->get($booking_surcharge['id']);
                                $other_id = $hotel_surcharge->other_slug;
                            } else {
                                $other_id = '';
                            }
                            $priceCalculated = $this->Util->calHotelSurcharge($hotel, $data['booking_rooms'], $booking_surcharge['surcharge_type'], $quantity, $booking_surcharge['id']);
                            $data_surcharges[$this->Util->getSurchargeId($booking_surcharge['surcharge_type'], $other_id)] = $priceCalculated;
                            $data_surcharges[$this->Util->getSurchargeId($booking_surcharge['surcharge_type'], $other_id, false)] = $priceCalculated;
                            $total_price += $priceCalculated;
                        }
                    }
                }
                $response['data_surcharge_price'] = $data_surcharges;
                $response['total_price'] = $total_price;
//                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                $data['agency_discount'] = 0;

                if ($data['sale_id'] != $data['user_id']) {
                    $response['total_price'] -= $revenue;
                }
                $response['total_price'] -= intval($data['agency_discount']);
                if ($response['total_price'] >= 0) {
                    $response['success'] = true;
                }
                $response['total_revenue'] = $revenue;
                $response['booking_str'] = $bookingStr;
                $response['data_booking_rooms'] = $data_booking_rooms;
            }
        }
        $output = $this->getResponse();
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function ChangeHotelSurchargeV2() {
        $this->loadModel('HotelSurcharges');
        $this->loadModel('BookingSurcharges');
        $this->loadModel('Hotels');
        $this->viewBuilder()->enableAutoLayout(false);
        $data = $this->getRequest()->getQuery();
        $hotel_id = $data['hotel_id'];
        $booking_id = $this->getRequest()->getQuery('booking_id');
        $arr_booking_surcharges = [];
        if ($booking_id) {
            $booking_surcharges = $this->BookingSurcharges->find()->where(['booking_id' => $booking_id])->toArray();
            foreach ($booking_surcharges as $booking_surcharge) {
                $arr_booking_surcharges[$booking_surcharge['surcharge_type']] = $booking_surcharge;
            }
        }

        $hotel = $this->Hotels->get($hotel_id);
        $infors = json_decode($hotel->payment_information, true);
        $payment_information = '';
        if ($infors) {
            foreach ($infors as $infor) {
                $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
            }
        }

        $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
        $normalSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type NOT IN' => $constantAutoSurcharge, 'hotel_id' => $hotel_id]);
        $autoSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type IN' => $constantAutoSurcharge, 'hotel_id' => $hotel_id]);
        $this->set(compact('normalSurcharges', 'autoSurcharges', 'arr_booking_surcharges', 'payment_information'));

        $response['surcharge'] = $this->render('change_hotel_surcharge_v2')->body();
        $response['payment_information'] = $this->render('payment_information')->body();

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function editV2($id = null)
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->loadModel('Rooms');
        $this->loadModel('LandTours');
        $this->loadModel('BookingLogs');
        $this->loadModel('Hotels');
        $this->loadModel('Rooms');
        $this->loadModel('BookingRooms');
        $this->loadModel('HotelSurcharges');
        $this->loadModel('BookingSurcharges');
        $booking = $this->Bookings->get($id, [
            'contain' => ['BookingSurcharges', 'BookingRooms', 'BookingLandtours', 'BookingLandtourAccessories']
        ]);
//        dd(2);
        if ($booking->status == 3 || $booking->status == 4) {
            $this->redirect(['action' => 'index']);
        }
        $referer = $this->referer();
        $url_components = parse_url($referer);
        if (isset($url_components['query'])) {
            parse_str($url_components['query'], $indexParams);
        } else {
            $indexParams = [];
        }
//        if ($booking->status < 4) {
        $status = [
            '0' => 'Chưa thanh toán',
            '1' => 'Đã thanh toán'
        ];

        $booking_type = [
            SYSTEM_BOOKING => 'Booking thuộc hệ thống',
        ];

        $method = [
            CUSTOMER_PAY => 'Khách hàng chuyển trực tiếp',
            AGENCY_PAY => 'CTV sẽ thu tiền hộ'
        ];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['booking_id'] = $id;
            $isAllow = true;
            $indexParams = json_decode($data['indexParams'], true);
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople < ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        $isAllow = false;
                        $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.'));
                        break;
                        if ($roomTotalMaxAdult < $booking_room['num_adult']) {
                            $isAllow = false;
                            $this->Flash->error(__('Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người.'));
                            break;
                        }
                    }
                }
            }
            if ($isAllow) {
                $item_id = $data['item_id'];
                if (isset($data['payment_deadline'])) {
                    $data['payment_deadline'] = $this->Util->formatSQLDate($data['payment_deadline'], 'd/m/Y');
                }
                $data['customer_deposit'] = str_replace(',', '', $data['customer_deposit']);
                $data['sale_discount'] = 0;
                $data['agency_discount'] = 0;
//                $data['sale_discount'] = str_replace(',', '', $data['sale_discount']);
//                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                if ($data['booking_type'] == SYSTEM_BOOKING) {
                    switch ($data['type']) {
                        case HOMESTAY:
                            $dataBooking = $this->_buildSaveHomestay($data);
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
                    if ($dataBooking['data']['type'] != LANDTOUR) {
                        $dataBooking['data']['sale_revenue'] = (isset($dataBooking['data']['sale_revenue']) ? $dataBooking['data']['sale_revenue'] : 0) + $dataBooking['data']['sale_discount'];
                        if ($dataBooking['data']['user_id'] != $dataBooking['data']['sale_id']) {
                            $dataBooking['data']['price'] = (isset($dataBooking['data']['price']) ? $dataBooking['data']['price'] : 0) - $dataBooking['data']['revenue'];
                            $dataBooking['data']['revenue'] = 0;
                        }
                        $dataBooking['data']['price'] -= $dataBooking['data']['agency_discount'];
                    } else {
                        if ($dataBooking['data']['payment_method'] == AGENCY_PAY) {
                            $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                            $dataBooking['data']['revenue'] = 0;
                        }
                        if ($dataBooking['data']['payment_method'] == MUSTGO_DEPOSIT) {
                            $dataBooking['data']['price'] = $dataBooking['data']['price'] - $dataBooking['data']['revenue'];
                            $dataBooking['data']['revenue'] = $dataBooking['data']['mustgo_deposit'] - $dataBooking['data']['price'];
                        }
                        $dataBooking['data']['price'] += $dataBooking['data']['sale_discount'];
                        $dataBooking['data']['revenue'] -= $dataBooking['data']['agency_discount'];
                    }
                } else if ($data['booking_type'] == ANOTHER_BOOKING) {
                    $dataBooking = $this->_buildSaveAnother($data);
                }
                if ($dataBooking['success']) {
                    $priceOld = $this->Bookings->find()->where(['id' => $booking->id])->first();
                    $priceNew = intval(str_replace(',', '', $data['price']));
                    if ($priceNew != $priceOld->price)
                    {
                        $booking['status'] = 0;
                    }
                    $timestampDeadline = strtotime(str_replace('/', '-', $dataBooking['data']['paymentDeadline']));
                    $paymentDeadline = date('Y-m-d', $timestampDeadline);
                    $dataBooking['data']['payment_deadline'] = $paymentDeadline;
                    $booking = $this->Bookings->patchEntity($booking, $dataBooking['data']);
                    if ($this->Bookings->save($booking)) {
                        $this->Flash->success(__('The booking has been saved.'));
                        $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
                        if (!$payment) {
                            $payment = $this->Payments->newEntity();
                            $paymentData['booking_id'] = $booking->id;
                        }
                        if (isset($data['payment_type'])) {
                            $paymentData['type'] = $data['payment_type'];
                        }
                        if (isset($data['payment_invoice'])) {
                            $paymentData['invoice'] = $data['payment_invoice'];
                        }
                        if (isset($data['payment_invoice_information'])) {
                            $paymentData['invoice_information'] = $data['payment_invoice_information'];
                        }
                        if (isset($data['payment_address'])) {
                            $paymentData['address'] = $data['payment_address'];
                        }
                        if (isset($data['media'])) {
                            $paymentData['images'] = $data['media'];
                        }
                        if (isset($data['pay_object'])) {
                            $paymentData['pay_object'] = $data['pay_object'];
                            if ($data['pay_object'] == PAY_HOTEL) {
                                $paymentData['check_type'] = $data['check_type'];
                                $paymentData['partner_information'] = '';
                            } else {
                                $paymentData['check_type'] = 0;
                                $paymentData['partner_information'] = [
                                    'name' => $data['partner_name'],
                                    'number' => $data['partner_number'],
                                    'bank' => $data['partner_bank'],
                                    'email' => $data['partner_email']
                                ];
                                $paymentData['partner_information'] = json_encode($paymentData['partner_information'], JSON_UNESCAPED_UNICODE);
                            }
                        }
                        $payment = $this->Payments->patchEntity($payment, $paymentData);
                        $this->Payments->save($payment);
//                            return $this->redirect(['action' => 'index', '?' => $indexParams]);
                        return $this->redirect($dataBooking['data']['previous_url']);
                    }
                    $this->Flash->error(__('The booking could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__($dataBooking['message']));
                }
            }
        }
//        if ($this->Auth->user('role_id') == 5) {
//            $object_types = [
//                LANDTOUR => 'Landtour'
//            ];
//            $querys = $this->Users->find()->where(['role_id' => 3, 'landtour_parent_id' => $this->Auth->user('id')])->toArray();
//        } elseif ($this->Auth->user('role_id') == 2) {
//            $object_types = [
//                HOMESTAY => 'Homestay',
//                VOUCHER => 'Voucher',
//                HOTEL => 'Hotel'
//            ];
//            $querys = $this->Users->find()->where(['role_id' => 3, 'parent_id' => $this->Auth->user('id')])->toArray();
//        }
//        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
//        $objects = [];
//        $objects[$this->Auth->user('id')] = 'Khách lẻ';
//        foreach ($querys as $query) {
//            $objects[$query['id']] = $query['screen_name'];
//        }

        $sale_id = $this->Auth->user('id');
        $users = $this->Users->find()->where(['role_id' => 3, 'parent_id' => $sale_id])->all();
        $hotels = $this->Hotels->find()->where(['is_vinhms' => 0])->all();
        $querysV2 = $this->Users->find()->where(['role_id' => 3])->toArray();
        $objectsV2 = [];
        $objectsV2[$this->Auth->user('id')] = 'Khách lẻ';
        foreach ($querysV2 as $queryV2) {
            $objectsV2[$queryV2['id']] = $queryV2['screen_name'];
        }
        $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
        $list_images = [];
        if ($payment) {
            if ($payment->images) {
                $list_images = $payment->images;
            }
        }
        $list_room = $this->Rooms->find()->where(['hotel_id' => $booking->item_id])->toArray();
        $rooms = $this->BookingRooms->find()->where(['booking_id' => $booking->id])->toArray();

//        dd($rooms , $booking);
        $userLogs = $this->Auth->user();
        $bookingLogs = $this->BookingLogs->find()
            ->join([
                'u' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'u.id = BookingLogs.user_id',
                ]
            ])
            ->where(['booking_id' => $booking->id])
            ->select(['u.screen_name', 'BookingLogs.id',  'BookingLogs.comment', 'BookingLogs.title', 'BookingLogs.created'])
            ->toArray();
//        dd($list_images);
        $this->set(compact('booking', 'users', 'hotels' ,'status', 'method', 'booking_type', 'objectsV2', 'payment', 'list_images', 'indexParams', 'referer', 'bookingLogs', 'userLogs', 'list_room', 'rooms'));
//        } else {
//            return $this->redirect(['action' => 'view', $id]);
//        }
    }

    public function addNewVinpearlV2()
    {
        $this->loadModel('Hotels');
        $this->loadModel('Users');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('VinhmsbookingRooms');
        $this->loadModel('Vinpayments');
        $listVinpearl = $this->Hotels->find()->where(['is_vinhms' => 1]);
        $qs = $this->Users->find()->where(['parent_id' => $this->Auth->user('id'), 'role_id' => 3]);
        $listAgency = [];
        $listAgency[$this->Auth->user('id')] = "Khách lẻ";
        foreach ($qs as $q) {
            $listAgency[$q->id] = $q->screen_name;
        }
        $listVinpearlHotel = [];
        foreach ($listVinpearl as $vinpearl) {
            $listVinpearlHotel[$vinpearl->id] = $vinpearl->name;
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $booking = $this->Vinhmsbookings->newEntity();
            $dateRange = explode(' - ', $data['daterange']);
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
            if (isset($data['agency_discount'])) {
                $data['agency_discount'] = str_replace(' ', '', $data['agency_discount']);
                $data['agency_discount'] = str_replace(',', '', $data['agency_discount']);
                $data['agency_discount'] = str_replace('.', '', $data['agency_discount']);
            }
            if (isset($data['change_price'])) {
                $data['change_price'] = str_replace(' ', '', $data['change_price']);
                $data['change_price'] = str_replace(',', '', $data['change_price']);
                $data['change_price'] = str_replace('.', '', $data['change_price']);
            }
            $bookingData = [
                'user_id' => $data['user_id'],
                'sale_id' => $this->Auth->user('id'),
                'hotel_id' => $data['hotel_id'],
                'first_name' => $data['first_name'],
                'sur_name' => $data['sur_name'],
                'nationality' => $data['nationality'],
                'nation' => $data['nation'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'note' => $data['note'],
                'change_price' => $data['change_price'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'vin_information' => json_encode($data['vin_information'], JSON_UNESCAPED_SLASHES),
                'sale_discount' => isset($data['sale_discount']) ? $data['sale_discount'] : 0,
                'agency_discount' => isset($data['agency_discount']) ? $data['agency_discount'] : 0,
                'status' => 1,
                'creator_type' => 1
            ];
            $booking = $this->Vinhmsbookings->patchEntity($booking, $bookingData);
            $this->Vinhmsbookings->save($booking);

            $booking = $this->Vinhmsbookings->patchEntity($booking, ['code' => "MVP" . str_pad($booking->id, 9, '0', STR_PAD_LEFT)]);
            $this->Vinhmsbookings->save($booking);

            $totalPrice = 0;
            $totalRevenue = 0;
            $totalSaleRevenue = 0;
            foreach ($data['vin_room'] as $k => $room) {
                foreach ($room['package'] as $kP => $package) {
                    $vinBookingRoom = $this->VinhmsbookingRooms->newEntity();
                    $singleRoomData = [
                        'room_index' => $k,
                        'vinhms_name' => $room['name'],
                        'vinhmsbooking_id' => $booking->id,
                        'vinhms_package_id' => $package['package_id'],
                        'vinhms_package_code' => $package['code'],
                        'vinhms_package_name' => $package['package_name'],
                        'vinhms_room_id' => $room['id'],
                        'vinhms_rateplan_id' => $package['rateplan_id'],
                        'vinhms_allotment_id' => $package['allotment_id'],
                        'vinhms_room_type_code' => $room['room_type_code'],
                        'vinhms_rateplan_code' => $package['rateplan_code'],
                        'room_id' => $room['id'],
                        'checkin' => date('Y-m-d', strtotime(str_replace('/', '-', $package['start_date']))),
                        'checkout' => date('Y-m-d', strtotime(str_replace('/', '-', $package['end_date']))),
                        'num_adult' => $room['num_adult'],
                        'num_kid' => $room['num_kid'],
                        'num_child' => $room['num_child'],
                        'customer_note' => '',
                        'detail_by_day' => '',
                        'price' => str_replace(',', '', $package['default_price']),
                        'revenue' => $package['revenue'],
                        'sale_revenue' => $package['sale_revenue']
                    ];
                    $totalPrice += str_replace(',', '', $package['price']);
                    $totalRevenue += $package['revenue'];
                    $totalSaleRevenue += $package['sale_revenue'];
                    $vinBookingRoom = $this->VinhmsbookingRooms->patchEntity($vinBookingRoom, $singleRoomData);
                    $this->VinhmsbookingRooms->save($vinBookingRoom);
                }
            }

            $booking = $this->Vinhmsbookings->patchEntity($booking, [
                'price' => $totalPrice + $data['change_price'],
                'price_default' => $totalPrice + $data['change_price'],
                'revenue' => $totalRevenue,
                'sale_revenue' => $totalSaleRevenue,
                'sale_revenue_default' => $totalSaleRevenue,
            ]);
            $this->Vinhmsbookings->save($booking);

            if (isset($data['payment']) && !empty($data['payment']) && (count($data['payment'])) > 1) {
                $vinpayment = $this->Vinpayments->newEntity();
                $dataPayment = [
                    'booking_id' => $booking->id,
                    'type' => $data['payment']['payment_type'],
                    'invoice' => $data['payment']['payment_invoice'],
                    'invoice_information' => isset($data['payment']['payment_invoice_information']) ? $data['payment']['payment_invoice_information'] : '',
                    'images' => isset($data['media']) ? $data['media'] : ''
                ];
                $vinpayment = $this->Vinpayments->patchEntity($vinpayment, $dataPayment);
                $this->Vinpayments->save($vinpayment);
            }

            $this->redirect(['controller' => 'bookings', 'action' => 'indexVin']);
        }
        $this->set(compact('listAgency', 'listVinpearlHotel'));
    }
}
