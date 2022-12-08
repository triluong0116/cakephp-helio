<?php

namespace App\Controller;

use App\Controller\AppController;
use function GuzzleHttp\Psr7\str;

/**
 * Vouchers Controller
 *
 * @property \App\Model\Table\VouchersTable $Vouchers
 *
 * @method \App\Model\Entity\Voucher[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VouchersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Locations');
        $limit = 12;
        $page = $this->request->getQuery('p');

        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');


        $listLocation = explode(',', $filterLocation);
        $listLocation = array_filter($listLocation);

        $listPrice = explode(',', $filterPrice);
        $listPrice = array_filter($listPrice);


        $listRating = explode(',', $filterRating);
        $listRating = array_filter($listRating);

        $outputSlider = '';
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }

        $condition = [];

        if ($listLocation) {
            $condition['location_id IN'] = $listLocation;
        }
        if ($listRating) {
            $condition['rating IN'] = $listRating;
        }

//        if ($listPrice) {
//            $price_condition = [];
//            foreach ($listPrice as $key => $price) {
//                $price_arr = explode('-', $price);
//                if (count($price_arr) == 2) {
//                    $price_condition[$key]['price + trippal_price + customer_price >='] = $price_arr[0];
//                    $price_condition[$key]['price + trippal_price + customer_price <='] = $price_arr[1];
//                } else {
//                    if ($price == '2000000') {
//                        $price_condition[$key]['price + trippal_price + customer_price < '] = $price;
//                    }
//                    if ($price == '10000000') {
//                        $price_condition[$key]['price + trippal_price + customer_price > '] = $price;
//                    }
//                }
//            }
//            $condition['OR'] = $price_condition;
//        }
//        dd($condition);


        $locations = $this->Locations->find()->where(['is_featured' => 1])->limit(8)->toArray();
        $countLocations = count($locations);
        $tmpVouchers = $this->Vouchers->find()->contain(['Destinations', 'Departures', 'Hotels'])->where($condition)->toArray();


        $vouchers = [];

        foreach ($tmpVouchers as $key => $voucher) {
            $tmpVouchers[$key]->totalPrice = $voucher->price + $voucher->trippal_price + $voucher->customer_price;
        }


        if ($filterPrice) {
            $lists = explode(',', $filterPrice);
            $filterPriceArr = [];
            foreach ($lists as $key => $singleList) {
                $arrray = explode('-', $singleList);
                $filterPriceArr[] = $arrray;
            }

            foreach ($tmpVouchers as $voucher) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($voucher->totalPrice >= $priceArr[0] && $voucher->totalPrice <= $priceArr[1]) {
                            $vouchers[] = $voucher;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($voucher->totalPrice <= $priceArr[0]) {
                                $vouchers[] = $voucher;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($voucher->totalPrice >= $priceArr[0]) {
                                $vouchers[] = $voucher;
                            }
                        }
                    }
                }
            }
//            $listPrice = implode(',', $filterPriceArr);
        } else {
            $vouchers = $tmpVouchers;
            $listPrice = [];
        }
        if ($filterSlider) {
            $vouchers = [];
            $filterPriceArr = explode('-', $filterSlider);
            foreach ($tmpVouchers as $voucher) {
                if (count($filterPriceArr) == 2) {
                    if ($voucher->totalPrice >= $filterPriceArr[0] && $voucher->totalPrice <= $filterPriceArr[1]) {
                        $vouchers[] = $voucher;
                    }
                }
            }
//            $listPrice = implode(',', $filterPriceArr);
        }
//        dd($listPrice);

        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $vouchers = \Cake\Utility\Hash::sort($vouchers, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $vouchers = \Cake\Utility\Hash::sort($vouchers, '{n}.totalPrice', 'desc');
            }
        }

        $title = "VOUCHER";
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Voucher', 'href' => ''],
        ];
//        dd($vouchers);


        $this->set(compact('vouchers', 'title', 'breadcrumbs', 'sortPrice', 'listLocation', 'listPrice', 'listRating', 'locations', 'headerType', 'countLocations', 'outputSlider'));
    }

    /**
     * View method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('Users');
        $slug = $this->request->getParam('slug');
        $voucher = $this->Vouchers->find()
            ->contain(['Departures', 'Destinations', 'Hotels', 'Hotels.Categories'])
            ->where(['Vouchers.slug' => $slug])->first();
//        dd($voucher);
        if (!$voucher) {
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
        $voucher->date_start = $voucher->start_date;
        $voucher->date_end = $voucher->end_date;
//        dd($combo);
        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            $ref = $user['ref_code'];
        } else {
            $ref = null;
        }
        $title = $voucher->name;
        $headerType = 1;
        $this->set('voucher', $voucher);
        $this->set(compact('title', 'headerType', 'ref'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $voucher = $this->Vouchers->newEntity();
        if ($this->request->is('post')) {
            $voucher = $this->Vouchers->patchEntity($voucher, $this->request->getData());
            if ($this->Vouchers->save($voucher)) {
                $this->Flash->success(__('The voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
        }
        $users = $this->Vouchers->Users->find('list', ['limit' => 200]);
        $departures = $this->Vouchers->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Vouchers->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Vouchers->Rooms->find('list', ['limit' => 200]);
        $this->set(compact('voucher', 'users', 'departures', 'destinations', 'rooms'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $voucher = $this->Vouchers->get($id, [
            'contain' => ['Rooms']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $voucher = $this->Vouchers->patchEntity($voucher, $this->request->getData());
            if ($this->Vouchers->save($voucher)) {
                $this->Flash->success(__('The voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
        }
        $users = $this->Vouchers->Users->find('list', ['limit' => 200]);
        $departures = $this->Vouchers->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Vouchers->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Vouchers->Rooms->find('list', ['limit' => 200]);
        $this->set(compact('voucher', 'users', 'departures', 'destinations', 'rooms'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $voucher = $this->Vouchers->get($id);
        if ($this->Vouchers->delete($voucher)) {
            $this->Flash->success(__('The voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function processVoucherPrice()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => true, 'price' => '', 'result' => '', 'data' => '', 'profit' => ''];

        $data = $this->getRequest()->getQuery();

        $voucher = $this->Vouchers->get($data['voucher_id'], ['contain' => ['Hotels']]);
        $price = ($voucher->price + $voucher->customer_price + $voucher->trippal_price) * $data['numVoucher'];
        $profit = $voucher->customer_price * $data['numVoucher'];
        $startDate = $data['fromDate'];
        $endDate = date('d-m-Y', strtotime($data['fromDate'] . "+{$voucher->days_attended} days"));

        $response['price'] = number_format($price) . ' VNĐ';
        $response['profit'] = number_format($profit) . ' VNĐ';
        $response['result'] = 'Voucher "' . $voucher->name . '", tại khách sạn ' . $voucher->hotel->name . ', ';
        $response['result'] .= 'check in ngày ' . $startDate . ', check out ngày ' . $endDate;

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function booking()
    {
        if($this->Auth->user('role_id') == 2){
            $this->redirect($this->referer());
        } else {
            $title = 'Mustgo Booking Voucher';
            $headerType = 1;
            $slug = $this->request->getParam('slug');
            $voucher = $this->Vouchers->find()->contain('Hotels')->where(['Vouchers.slug' => $slug])->first();
            $price = $voucher->price + $voucher->trippal_price + $voucher->customer_price;
            if ($this->request->is('post')) {
                $data = $this->getRequest()->getData();
            }

            $this->set(compact('voucher', 'data', 'title', 'headerType'));
        }
    }

    public function getVoucherPrice()
    {
        $this->viewBuilder()->enableAutoLayout('false');
        $response = ['success' => false, 'message' => '', 'price' => 0, 'result' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $voucher = $this->Vouchers->get($data['item_id'], ['contain' => 'Hotels']);
            $price = ($voucher->price + $voucher->trippal_price + $voucher->customer_price) * $data['amount'];
            $profit = $voucher->customer_price* $data['amount'];
            if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
                $price = $price - $profit;
                $profit = 0;
            }
            $response['price'] = number_format($price);
            $response['profit'] = number_format($profit);

            $response['success'] = true;
            $startDate = $data['start_date'];
            $endDate = date('d-m-Y', strtotime("+" . $voucher->days_attended . " days", strtotime($startDate)));
            $response['result'] = 'Voucher "' . $voucher->name . '", tại khách sạn ' . $voucher->hotel->name . ', ';
            $response['result'] .= 'check in ngày ' . $startDate . ', check out ngày ' . $endDate;
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }
}
