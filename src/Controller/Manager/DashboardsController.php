<?php

namespace App\Controller\Manager;

use App\Controller\AppController;

/**
 * Dashboards Controller
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\LandtourPaymentFeesTable $LandtourPaymentFees
 *
 * @method \App\Model\Entity\Dashboard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DashboardsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('backend');
        $this->loadModel('Bookings');
        $this->loadModel('LandtourPaymentFees');
        $user_id = $this->Auth->user('id');
        if ($this->request->getQuery()) {
            $currentDay = $this->request->getQuery('current_day');
        }
        if(!isset($currentDay)) {
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
        ])->order(['Bookings.created' => 'ASC'])->toArray();
//        foreach ($bookings as $key => $booking) {
//            $listBooking[date_format($booking->start_date, 'Y-m-d')][] = $booking;
//        }
        $month = date('m', strtotime($currentDay));
        $feeArray = [];
        $landtourPaymentFees = $this->LandtourPaymentFees->find()->where(['date' => $this->Util->formatSQLDate($currentDay, 'd/m/Y')])->orderAsc('Date')->toArray();
        foreach ($landtourPaymentFees as $k => $fee) {
            if(!isset($feeArray[date_format($fee->date, 'd-m')])){
                $feeArray[date_format($fee->date, 'd-m')]['count'] = 0;
                $feeArray[date_format($fee->date, 'd-m')]['partner'] = [];
                if(!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            } else {
                if(!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            }
        }


        $this->set(compact('currentDay', 'bookings', 'landtourPaymentFees', 'feeArray'));
    }

    public function listPaymentFee(){
        $this->loadModel('LandtourPaymentFees');
        if ($this->request->getQuery()) {
            $startDay = $this->request->getQuery('start_day');
            $endDay = $this->request->getQuery('end_day');
            $partner = $this->request->getQuery('partner');
        }
        if(!isset($startDay)) {
            $startDay = date("d/m/Y");
        }
        if(!isset($endDay)) {
            $endDay = date("d/m/Y");
        }
        $condition = [];
        $condition['date >='] = $this->Util->formatSQLDate($startDay, 'd/m/Y');
        $condition['date <='] = $this->Util->formatSQLDate($endDay, 'd/m/Y');
        if(isset($partner) && !empty($partner)){
            $condition['partner_name'] = $partner;
            $selectedPartner = $partner;
        } else {
            $selectedPartner = '';
        }
//        dd($condition);

        $listPartner[''] = 'Chọn đối tác';
        $query = $this->LandtourPaymentFees->find()->select('partner_name')->distinct('partner_name')->groupBy('partner_name')->toArray();
        foreach ($query as $k => $value){
            $listPartner[$k] = $k;
        }
        $landtourPaymentFees = $this->LandtourPaymentFees->find()->where($condition)->orderAsc('Date')->toArray();
        $feeArray = [];
        foreach ($landtourPaymentFees as $k => $fee) {
            if(!isset($feeArray[date_format($fee->date, 'd-m')])){
                $feeArray[date_format($fee->date, 'd-m')]['count'] = 0;
                $feeArray[date_format($fee->date, 'd-m')]['partner'] = [];
                if(!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            } else {
                if(!isset($feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name])) {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                } else {
                    $feeArray[date_format($fee->date, 'd-m')]['partner'][$fee->partner_name][] = $fee;
                }
                $feeArray[date_format($fee->date, 'd-m')]['count'] += 1;
            }
        }

        $this->set(compact('feeArray', 'startDay', 'endDay', 'listPartner', 'selectedPartner'));
    }

    public function controlPayment() {
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
        if($this->request->getData()){
            $data = $this->request->getData();
            $data['single_price'] = str_replace(',', '', $data['single_price']);
            $data['total'] = str_replace(',', '', $data['total']);
            $data['date'] = $this->Util->formatSQLDate($data['date'], 'd/m/Y');
            $landtourPaymentFee = $this->LandtourPaymentFees->newEntity();
            $landtourPaymentFee = $this->LandtourPaymentFees->patchEntity($landtourPaymentFee, $data);
            if($this->LandtourPaymentFees->save($landtourPaymentFee)){
                $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('paymentStatus', 'paymentType'));
    }

    public function editControlPayment($id) {
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
        if($this->request->getData()){
            $data = $this->request->getData();
            $data['single_price'] = str_replace(',', '', $data['single_price']);
            $data['total'] = str_replace(',', '', $data['total']);
            $data['date'] = $this->Util->formatSQLDate($data['date'], 'd/m/Y');
            $fee = $this->LandtourPaymentFees->patchEntity($fee, $data);
            if($this->LandtourPaymentFees->save($fee)){
                $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('paymentStatus', 'paymentType', 'fee'));
    }

    /**
     * View method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dashboard = $this->Dashboards->get($id, [
            'contain' => []
        ]);

        $this->set('dashboard', $dashboard);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dashboard = $this->Dashboards->newEntity();
        if ($this->request->is('post')) {
            $dashboard = $this->Dashboards->patchEntity($dashboard, $this->request->getData());
            if ($this->Dashboards->save($dashboard)) {
                $this->Flash->success(__('The dashboard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dashboard could not be saved. Please, try again.'));
        }
        $this->set(compact('dashboard'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dashboard = $this->Dashboards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dashboard = $this->Dashboards->patchEntity($dashboard, $this->request->getData());
            if ($this->Dashboards->save($dashboard)) {
                $this->Flash->success(__('The dashboard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dashboard could not be saved. Please, try again.'));
        }
        $this->set(compact('dashboard'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

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

    public function viewBooking($id = null){
        $this->loadModel('Bookings');
        $this->loadModel('Payments');
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Hotels', 'Vouchers', 'HomeStays', 'LandTours', 'Hotels.Locations', 'Vouchers.Hotels', 'Vouchers.Hotels.Locations', 'LandTours.Destinations', 'HomeStays.Locations', 'BookingRooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'BookingRooms.Rooms', 'BookingSurcharges']
        ]);

        $this->set('booking', $booking);
        $payment = $this->Payments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
        $this->set('payment', $payment);
    }

    public function finish($id = null)
    {
        $this->loadModel('Users');
        $this->loadModel('Bookings');
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
            return $this->redirect($this->referer());
        }
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 6)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }
}
