<?php

namespace App\Controller;

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
//        dd($sortPrice);
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
//            $listPrice = implode(',', $filterPriceArr);
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
        $listFeaturedlocations = $this->Locations->find()->orderDesc('is_featured')->limit(10);
        $locations = $this->Locations->find()->where(['id NOT IN' => $listFeaturedlocations->extract('id')->toArray()]);
        $this->set(compact('combos', 'title', 'breadcrumbs','sortPrice', 'listLocation', 'listPrice', 'listRating', 'locations', 'headerType', 'fromDate', 'toDate', 'amountItems','outputSlider', 'listFeaturedlocations'));
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
        $slug = $this->request->getParam('slug');
        $homeStay = $this->HomeStays->find()
            ->contain(['Locations', 'PriceHomeStays', 'Categories'])
            ->where(['HomeStays.slug' => $slug])->first();

        $currentDay = date('d-m-Y');
        $nextDay = date('d-m-Y', strtotime('+1 day'));

        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            $ref = $user['ref_code'];
        }
        $title = $homeStay->name;
        $headerType = 1;
        $this->set('homeStay', $homeStay);
        $this->set(compact('title', 'headerType', 'currentDay', 'nextDay','ref'));
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
        $homeStay = $this->HomeStays->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $homeStay = $this->HomeStays->patchEntity($homeStay, $this->request->getData());
            if ($this->HomeStays->save($homeStay)) {
                $this->Flash->success(__('The home stay has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The home stay could not be saved. Please, try again.'));
        }
        $this->set(compact('homeStay'));
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

    public function getHomestayPrice()
    {
        $this->loadModel('HomeStays');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => true, 'price' => 0, 'result' => '', 'data' => '','profit' => 0];
        $data = $this->getRequest()->getQuery();
        $homestay = $this->HomeStays->get($data['item_id'], ['contain' => 'PriceHomeStays']);
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $calSDate = $data['start_date'];
        $numb_people = isset($data['numPeople']) ? $data['numPeople'] : 0;
        $calEDate = date('d-m-Y', strtotime($data['end_date']. "-1 days"));
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
        if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
            $totalPrice = $totalPrice - $profit;
            $profit = 0;
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
    public function booking()
    {
        if ($this->Auth->user() && $this->Auth->user()['role_id'] == 2) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        } else {
            $title = 'Mustgo Booking HomeStay';
            $headerType = 1;
            $slug = $this->getRequest()->getParam('slug');
            $fromDate = $this->getRequest()->getQuery('fromDate');
            if (!$fromDate)
            {
                $fromDate = date('d-m-Y');
            };
            $toDate = $this->getRequest()->getQuery('fromDate');
            if (!$toDate)
            {
                $toDate = date('d-m-Y', strtotime('+1 day'));
            };
            $homeStay = $this->HomeStays->find()
                ->where(['HomeStays.slug' => $slug])->first();
            if (!$homeStay) {
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }
            $this->set(compact('headerType', 'title', 'homeStay', 'fromDate', 'toDate'));
        }
    }
}
