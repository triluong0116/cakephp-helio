<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

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
        $tmpVouchers = $this->Vouchers->find()->contain(['Destinations', 'Departures'])->where($condition)->toArray();


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
                foreach($filterPriceArr as $priceArr){
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
    public function view($id = null) {
        $voucher = $this->Vouchers->get($id, [
            'contain' => ['Users', 'Departures', 'Destinations']
        ]);
        $this->set('voucher', $voucher);
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
    public function edit($id = null) {
        $voucher = $this->Vouchers->get($id, [
            'contain' => ['Hotels']
        ]);
        $images = [];
        if ($voucher->media) {
            $medias = json_decode($voucher->media, true);
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
                $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                if(isset($data['list_payment'])){
                    $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                }
                $data['price'] = str_replace(',', '', $data['price']);
                $data['trippal_price'] = str_replace(',', '', $data['trippal_price']);
                $data['customer_price'] = str_replace(',', '', $data['customer_price']);
                if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                    $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
                } else {
                    $data['icon_list'] = json_encode([]);
                }
                $date_array = explode(' - ', $data['reservation']);
                $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

                if ($data['thumbnail']['error'] == 0) {
                    $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                    $data['thumbnail'] = $thumbnail;
                } else {
                    $data['thumbnail'] = $data['thumbnail_edit'];
                }
                $data['user_id'] = $this->Auth->user('id');
//                        dd($data);
                $voucher = $this->Vouchers->patchEntity($voucher, $data);
                if ($this->Vouchers->save($voucher)) {
                    $this->Flash->success(__('The voucher has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('Phải nhập ít nhất 1 mô tả'));
            }
        }
        $users = $this->Vouchers->Users->find('list', ['limit' => 200]);
        $departures = $this->Vouchers->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Vouchers->Destinations->find('list', ['limit' => 200]);
        $hotels = $this->Vouchers->Hotels->find('list');
        $this->set(compact('voucher', 'users', 'departures', 'destinations', 'rooms', 'list_images', 'hotels'));
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
}
