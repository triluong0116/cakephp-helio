<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * LandTours Controller
 *
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\BookingsTable $Bookings
 *
 * @method \App\Model\Entity\LandTour[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LandToursController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Departures', 'Destinations']
        ];
        $landTours = $this->paginate($this->LandTours);

        $this->set(compact('landTours'));
    }

    /**
     * View method
     *
     * @param string|null $id Land Tour id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $landTour = $this->LandTours->get($id, [
            'contain' => ['Users', 'Departures', 'Destinations']
        ]);
        $this->set('landTour', $landTour);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $landTour = $this->LandTours->newEntity();
        if ($this->request->is('post')) {
            $landTour = $this->LandTours->patchEntity($landTour, $this->request->getData());
            if ($this->LandTours->save($landTour)) {
                $this->Flash->success(__('The land tour has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The land tour could not be saved. Please, try again.'));
        }
        $users = $this->LandTours->Users->find('list', ['limit' => 200]);
        $departures = $this->LandTours->Departures->find('list', ['limit' => 200]);
        $destinations = $this->LandTours->Destinations->find('list', ['limit' => 200]);
        $this->set(compact('landTour', 'users', 'departures', 'destinations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Land Tour id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $landTour = $this->LandTours->get($id, [
            'contain' => []
        ]);
        $images = [];
        if ($landTour->media) {
            $medias = json_decode($landTour->media, true);
            foreach ($medias as $media) {
                $obj['name'] = basename($media);
                $obj['size'] = filesize($media);
                $images[] = $obj;
            }
        }
        $list_images = json_encode($images);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (isset($data['list_caption']) && count($data['list_caption']) > 0) {
                if (isset($data['list_email']) && is_array($data['list_email'])) {
                    $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                    $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                    $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    if (isset($data['list_payment'])) {
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                    }

                    $data['price'] = str_replace(',', '', $data['price']);
                    $data['trippal_price'] = str_replace(',', '', $data['trippal_price']);
                    $data['customer_price'] = str_replace(',', '', $data['customer_price']);

//            dd($data);
                    if ($data['contract_file']['error'] == 0) {
                        $contract = $this->Upload->uploadSingle($data['contract_file']);
                        $data['contract_file'] = $contract;
                    } else {
                        $data['contract_file'] = $data['contract_file_edit'];
                    }

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = $data['thumbnail_edit'];
                    }
                    $landTour = $this->LandTours->patchEntity($landTour, $data);
                    if ($this->LandTours->save($landTour)) {
                        $this->Flash->success(__('The land tour has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The land tour could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            }
        }

        $users = $this->LandTours->Users->find('list', ['limit' => 200]);
        $departures = $this->LandTours->Departures->find('list', ['limit' => 200]);
        $destinations = $this->LandTours->Destinations->find('list', ['limit' => 200]);
        $this->set(compact('landTour', 'users', 'departures', 'destinations', 'list_images'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Land Tour id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $landTour = $this->LandTours->get($id);
        if ($this->LandTours->delete($landTour)) {
            $this->Flash->success(__('The land tour has been deleted.'));
        } else {
            $this->Flash->error(__('The land tour could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function processLandtourPrice()
    {
        $this->loadModel('LandTours');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => true, 'price' => 0, 'result' => '', 'data' => '', 'profit' => 0];
        $data = $this->getRequest()->getQuery();
        $landTour = $this->LandTours->get($data['landtour_id']);
        $numb_people = $data['numPeople'];
        $data = $this->getRequest()->getQuery();
        $profit = $numb_people * $landTour->customer_price;
        $price = ($landTour->price + $landTour->customer_price + $landTour->trippal_price) * $data['numPeople'];

        $response['price'] = number_format($price) . ' VNĐ';
        $response['profit'] = number_format($profit) . ' VNĐ';
        $response['result'] = 'Land Tour "' . $landTour->name . '", đi ngày ' . $data['fromDate'] . ', ' . $data['numPeople'] . ' người';

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        $this->set(compact('landTour', 'price', 'profit', 'numb_people'));
        $response['data'] = $this->render('process_landtour_price')->body();
        return $output;
    }

    public function analytic()
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
//        $listBooking = [];
//        $currentMonth = 1;
//        $currentYear = 2021;

//        for ($d = 1; $d <= 31; $d++) {
//            $time = mktime(12, 0, 0, $currentMonth, $d, $currentYear);
//            if (date('m', $time) == $currentMonth)
//                $listBooking[date('Y-m-d', $time)] = [];
//        }
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
            'Bookings.type' => LANDTOUR,
            'Bookings.start_date' => $this->Util->formatSQLDate($currentDay, 'd/m/Y'),
        ])->order(['Bookings.start_date' => 'DESC'])->toArray();
        $month = date('m', strtotime($currentDay));
        $feeArray = [];
        $landtourPaymentFees = $this->LandtourPaymentFees->find()->where(['date' => $this->Util->formatSQLDate($currentDay, 'd/m/Y')])->orderAsc('Date')->toArray();
        foreach ($landtourPaymentFees as $k => $fee) {
            if (!isset($feeArray[date_format($fee->date, 'd-m')])) {
                $feeArray[date_format($fee->date, 'd-m')]['count'] = 0;
                $feeArray[date_format($fee->date, 'd-m')]['partner'] = [];
                if (!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            } else {
                if (!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            }
        }


        $this->set(compact('currentDay', 'bookings', 'landtourPaymentFees', 'feeArray'));
    }

    public function listPaymentFee()
    {
        $this->loadModel('LandtourPaymentFees');
        if ($this->request->getQuery()) {
            $startDay = $this->request->getQuery('start_day');
            $endDay = $this->request->getQuery('end_day');
            $partner = $this->request->getQuery('partner');
        }
        if (!isset($startDay)) {
            $startDay = date("d/m/Y");
        }
        if (!isset($endDay)) {
            $endDay = date("d/m/Y");
        }
        $condition = [];
        $condition['date >='] = $this->Util->formatSQLDate($startDay, 'd/m/Y');
        $condition['date <='] = $this->Util->formatSQLDate($endDay, 'd/m/Y');
        if (isset($partner) && !empty($partner)) {
            $condition['partner_name'] = $partner;
            $selectedPartner = $partner;
        } else {
            $selectedPartner = '';
        }
//        dd($condition);

        $listPartner[''] = 'Chọn đối tác';
        $query = $this->LandtourPaymentFees->find()->select('partner_name')->distinct('partner_name')->groupBy('partner_name')->toArray();
        foreach ($query as $k => $value) {
            $listPartner[$k] = $k;
        }
        $landtourPaymentFees = $this->LandtourPaymentFees->find()->where($condition)->orderAsc('Date')->toArray();
        $feeArray = [];
        foreach ($landtourPaymentFees as $k => $fee) {
            if (!isset($feeArray[date_format($fee->date, 'd-m')])) {
                $feeArray[date_format($fee->date, 'd-m')]['count'] = 0;
                $feeArray[date_format($fee->date, 'd-m')]['partner'] = [];
                if (!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            } else {
                if (!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            }
        }

        $this->set(compact('feeArray', 'startDay', 'endDay', 'listPartner', 'selectedPartner'));
    }

    public function controlPayment()
    {
        $this->loadModel('LandtourPaymentFees');
        $paymentStatus = [
            0 => "Chưa",
            1 => "Rồi",
        ];
        $paymentType = [
            1 => "Chuyển khoản",
            2 => "Tiền mặt",
            3 => "Công nợ",
        ];
        if ($this->request->getData()) {
            $data = $this->request->getData();
            $data['single_price'] = str_replace(',', '', $data['single_price']);
            $data['total'] = str_replace(',', '', $data['total']);
            $data['date'] = $this->Util->formatSQLDate($data['date'], 'd/m/Y');
            $landtourPaymentFee = $this->LandtourPaymentFees->newEntity();
            $landtourPaymentFee = $this->LandtourPaymentFees->patchEntity($landtourPaymentFee, $data);
            if ($this->LandtourPaymentFees->save($landtourPaymentFee)) {
                $this->redirect(['action' => 'analytic']);
            }
        }
        $this->set(compact('paymentStatus', 'paymentType'));
    }

    public function editControlPayment($id)
    {
        $this->loadModel('LandtourPaymentFees');
        $paymentStatus = [
            0 => "Chưa",
            1 => "Rồi",
        ];
        $paymentType = [
            1 => "Chuyển khoản",
            2 => "Tiền mặt",
            3 => "Công nợ",
        ];
        $fee = $this->LandtourPaymentFees->get($id);
        if ($this->request->getData()) {
            $data = $this->request->getData();
            $data['single_price'] = str_replace(',', '', $data['single_price']);
            $data['total'] = str_replace(',', '', $data['total']);
            $data['date'] = $this->Util->formatSQLDate($data['date'], 'd/m/Y');
            $fee = $this->LandtourPaymentFees->patchEntity($fee, $data);
            if ($this->LandtourPaymentFees->save($fee)) {
                $this->redirect(['action' => 'analytic']);
            }
        }
        $this->set(compact('paymentStatus', 'paymentType', 'fee'));
    }

    public function viewBooking($id = null)
    {
        $this->loadModel('Bookings');
        $this->loadModel('Payments');
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Hotels', 'Vouchers', 'HomeStays', 'LandTours', 'Hotels.Locations', 'Vouchers.Hotels', 'Vouchers.Hotels.Locations', 'LandTours.Destinations', 'HomeStays.Locations', 'BookingRooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'BookingRooms.Rooms', 'BookingSurcharges']
        ]);

        $this->set('booking', $booking);
        $payment = $this->Payments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
        $this->set('payment', $payment);
    }

    public function deleteFee($id = null)
    {
        $this->loadModel('LandtourPaymentFees');
        $fee = $this->LandtourPaymentFees->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if($this->LandtourPaymentFees->delete($fee)){
            $this->Flash->success(__('Xóa thành công'));
        } else {
            $this->Flash->error(__('Xóa thất bại, vui lòng thử lại'));
        }
        return $this->redirect(['action' => 'listPaymentFee']);
    }
}
