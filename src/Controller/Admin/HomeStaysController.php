<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * HomeStays Controller
 *
 *
 * @method \App\Model\Entity\HomeStay[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HomeStaysController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Locations');
        $this->loadModel('Categories');
        $limit = 12;
        $page = $this->request->getQuery('p');
        if(!$page){
            $page = 1;
        }

        $location_id = $this->request->getQuery('location_id');
        $fromDate = $this->request->getQuery('fromDate');
        $toDate = $this->request->getQuery('toDate');
        if ($fromDate) {
            $fromDate = date('Y-m-d', strtotime($fromDate));
        }
        if ($toDate) {
            $toDate = date('Y-m-d', strtotime($toDate));
        }

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

        $condition = $order = [];

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
//                    $price_condition[$key]['totalPrice >='] = $price_arr[0];
//                    $price_condition[$key]['totalPrice <='] = $price_arr[1];
//                } else {
//                    if ($price == '2000000') {
//                        $price_condition[$key]['totalPrice < '] = $price;
//                    }
//                    if ($price == '10000000') {
//                        $price_condition[$key]['totalPrice > '] = $price;
//                    }
//                }
//            }
//            $condition['OR'] = $price_condition;
//        }

        $title = "HomeStay";
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Home Stay', 'href' => '']
        ];
        $today = date('Y-m-d');
//        dd($condition);
        $tmpCombos = $this->HomeStays->find()->contain([
            'Locations',
            'PriceHomeStays',])->where($condition)->order($order)->toArray();
        $combos = [];
        foreach ($tmpCombos as $key => $combo) {
            $tmpCombos[$key]->totalPrice = $this->Util->countingHomeStayPrice($today, $combo);
        }
        if ($filterPrice) {
            $filterPriceArr = explode('-', $filterPrice);
            foreach ($tmpCombos as $combo) {
                $count = count($filterPriceArr);
               if($count == 2){
                if ($combo->totalPrice >= $filterPriceArr[0] && $combo->totalPrice <= $filterPriceArr[1]) {
                    $combos[] = $combo;
                }
               } else {
                   if($filterPriceArr[0] == '2000000') {
                       if ($combo->totalPrice < $filterPriceArr[0]) {
                           $combos[] = $combo;
                       }
                   } else {
                       if ($combo->totalPrice > $filterPriceArr[0]) {
                           $combos[] = $combo;
                       }
                   }
               }
            }
            $listPrice = implode(',', $filterPriceArr);
        } else {
            $combos = $tmpCombos;
            $listPrice = [];
        }
        if ($filterSlider) {
            $combos = [];
            $filterPriceArr = explode('-', $filterSlider);
            foreach ($tmpCombos as $combo) {
                if(count($filterPriceArr) == 2){
                    if ($combo->totalPrice >= $filterPriceArr[0] && $combo->totalPrice <= $filterPriceArr[1]) {
                        $combos[] = $combo;
                    }
                }
            }
//            $listPrice = implode(',', $filterPriceArr);
        }
        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'desc');
            }
        }

        $amountItems = count($combos);
        $combos = array_slice($combos, ($page - 1) * $limit, $page * $limit);
        $locations = $this->Locations->find();
        $this->set(compact('combos', 'title', 'breadcrumbs','sortPrice', 'listLocation', 'listPrice', 'listRating', 'locations', 'headerType', 'fromDate', 'toDate', 'amountItems','outputSlider'));
    }

    /**
     * View method
     *
     * @param string|null $id Home Stay id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('HomeStays');
        $homeStay = $this->HomeStays->get($id, [
            'contain' => ['Locations', 'Categories', 'PriceHomeStays']
        ]);

        $this->set('homeStay', $homeStay);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

    }

    /**
     * Edit method
     *
     * @param string|null $id Home Stay id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('HomeStays');
        $this->loadModel('Categories');
        $this->loadModel('PriceHomeStays');
        $homestay = $this->HomeStays->get($id, [
            'contain' => ['Locations', 'Categories', 'PriceHomeStays']
        ]);
        $listHouse = [APARTMENT => 'Chung cư', VILLA => 'Biệt thự', HOME => 'Nhà riêng', BUNGALOW => 'Bungalow'];
        $typeHouse = [SINGLE_ROOM => 'Phòng riêng', WHOLE_HOUSE => 'Nguyên căn'];

//        dd($homestay);
        $images = [];
        if ($homestay->media) {
            $medias = json_decode($homestay->media, true);
            foreach ($medias as $media) {
                if (file_exists($media)) {
                    $obj['name'] = basename($media);
                    $obj['size'] = filesize($media);
                    $images[] = $obj;
                }
            }
        }
        $list_images = json_encode($images);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (isset($data['list_email']) && is_array($data['list_email'])) {
                if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                    $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
                } else {
                    $data['icon_list'] = json_encode([]);
                }
                if (!isset($data['is_special'])) {
                    $data['is_special'] = 0;
                }
                $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                if($data['list_payment']){
                    $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                }
                unset($data['reservation']);

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
                $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                $data['price_customer'] = str_replace(',', '', $data['price_customer']);
                $homestay = $this->HomeStays->patchEntity($homestay, $data);
                if ($this->HomeStays->save($homestay)) {

                    $priceHomeStays = $this->PriceHomeStays->find()->where(['home_stay_id' => $homestay->id]);
                    foreach ($priceHomeStays as $price) {
                        if ($price->type == WEEK_DAY) {
                            $price = $this->PriceHomeStays->patchEntity($price, ['price' => str_replace(',', '', $data['weekday_price'])
                                , 'description' => $data['weekday_price_description']]);
                            $this->PriceHomeStays->save($price);
                        } else if ($price->type == WEEK_END) {
                            $price = $this->PriceHomeStays->patchEntity($price, ['price' => str_replace(',', '', $data['weekend_price'])
                                , 'description' => $data['weekend_price_description']]);
                            $this->PriceHomeStays->save($price);
                        }
                    }

                    $this->Flash->success(__('The hotel has been saved.'));
                    return $this->redirect(['controller' => 'home_stays', 'action' => 'index']);
                }
                $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
            }
        }


        $locations = $this->HomeStays->Locations->find('list', ['limit' => 200]);
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
        $this->set(compact('homestay', 'locations', 'ultilities', 'list_images', 'listHouse', 'typeHouse'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Home Stay id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $homeStay = $this->HomeStays->get($id);
        if ($this->HomeStays->delete($homeStay)) {
            $this->Flash->success(__('The home stay has been deleted.'));
        } else {
            $this->Flash->error(__('The home stay could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function processHomestayPrice()
    {
        $this->loadModel('HomeStays');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => true, 'price' => 0, 'result' => '', 'data' => '','profit' => 0];
        $data = $this->getRequest()->getQuery();
        $homestay = $this->HomeStays->get($data['homestay_id'], ['contain' => 'PriceHomeStays']);
        $startDate = $data['fromDate'];
        $endDate = $data['toDate'];
        $numb_people = $data['numPeople'];
        $calSDate = $data['fromDate'];
        $calEDate = date('d-m-Y', strtotime($data['toDate']. "-1 days"));
        $dates = $this->Util->_dateRange($calSDate, $calEDate);

        $profit = (sizeof($dates)) * $homestay->price_customer ;

        $priceWeekDay = $priceWeekEnd = 0;
        foreach ($homestay->price_home_stays as $price_home_stay) {
            if ($price_home_stay->type == WEEK_DAY) {
                $priceWeekDay = $price_home_stay->price;
            } else if ($price_home_stay->type == WEEK_END) {
                $priceWeekEnd = $price_home_stay->price;
            }
        }
        $calSDate = $data['fromDate'];
        $calEDate = date('d-m-Y', strtotime($data['toDate']. "-1 days"));
        $arrayDate = $this->Util->_dateRange($calSDate, $calEDate);
        $arrayWeek = [];
        $totalPrice = 0;
        foreach ($arrayDate as $date) {
            $unixTimestamp = strtotime($date);
            $weekday = date("l", $unixTimestamp);
            if ($weekday == 'Monday' || $weekday == 'Tuesday' || $weekday == 'Wednesday' || $weekday == 'Thursday') {
                $arrayWeek[$weekday] = WEEK_DAY;
                $totalPrice += $priceWeekDay + $homestay->price_agency + $homestay->price_customer;
            } else {
                $arrayWeek[$weekday] = WEEK_END;
                $totalPrice += $priceWeekEnd  + $homestay->price_agency + $homestay->price_customer;
            }
        }

        $result = $homestay->name.', check in '.$startDate.' check out '.$endDate;
        $response['price'] = number_format($totalPrice). ' VNĐ';
        $response['result'] = $result;
        $response['profit'] = number_format($profit). ' VNĐ';


        $this->set(compact('homestay', 'arrayWeek', 'endDate', 'startDate', 'totalPrice','profit','numb_people'));
        $response['data'] = $this->render('process_homestay_price')->body();
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }
}
