<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;

/**
 * Locations Controller
 *
 * @property \App\Model\Table\LocationsTable $Locations
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HomeStaysTable $HomeStays
 * @property \App\Model\Table\HotelSearchsTable $HotelSearchs
 * @property \App\Model\Table\HotelsTable $Hotels
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LocationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Combos');
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');
        $keyword = $this->getRequest()->getQuery('keyword');
        $locations = $this->Locations->find()->where(['name LIKE ' => '%' . $keyword . '%'])->extract('id')->toArray();

        if ($locations) {
            $locationIds = implode(',', $locations);
            return $this->redirect(\Cake\Routing\Router::url(['_name' => 'location.all', 'locationIds' => $locationIds], true));
        }

        $slug = $this->request->getParam('slug');

        // build condition & sort
        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $listLocation = explode(',', $filterLocation);
        $listRating = explode(',', $filterRating);

        $condition = $conditionHotel = $order = [];
        $condition['destination_id'] = $location->id;
        $conditionHotel['location_id'] = $location->id;
        if ($filterLocation) {
            $condition['destination_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
            $conditionHotel['rating IN'] = $listRating;
        }
        // do query

        $today = date('Y-m-d');

        $tmpHotels = $this->Hotels->find()->contain(['Locations', 'PriceHotels'])->where($conditionHotel)->order(['is_feature' => 'DESC'])->toArray();

        $hotels = [];

        $tmpLandTours = $this->LandTours->find()->contain(['Destinations', 'Departures'])->where($condition)->order(['is_feature' => 'DESC'])->toArray();
        $landTours = [];

        $tmpCombos = $this->Combos->find()->contain([
            'Destinations',
            'Departures',
            'Hotels',
            'Hotels.PriceHotels'
        ])->where($condition)->order(['is_feature' => 'DESC'])->toArray();

        foreach ($tmpCombos as $key => $combo) {
            $tmpCombos[$key]->totalPrice = $this->Util->countingComboPrice($today, $combo);
        }

        foreach ($tmpHotels as $key => $hotel) {
            $tmpHotels[$key]->totalPrice = $this->Util->countingHotelPrice($today, $hotel);
        }

        foreach ($tmpLandTours as $key => $landTour) {
            $tmpLandTours[$key]->totalPrice = $landTour->price + $landTour->trippal_price + $landTour->customer_price;
        }

        $combos = [];

        if ($filterPrice) {
            $lists = explode(',', $filterPrice);
            $filterPriceArr = [];
            foreach ($lists as $key => $singleList) {
                $arrray = explode('-', $singleList);
                $filterPriceArr[] = $arrray;
            }
            foreach ($tmpCombos as $combo) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($combo->totalPrice >= $priceArr[0] && $combo->totalPrice <= $priceArr[1]) {
                            $combos[] = $combo;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($combo->totalPrice <= $priceArr[0]) {
                                $combos[] = $combo;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($combo->totalPrice >= $priceArr[0]) {
                                $combos[] = $combo;
                            }
                        }
                    }
                }
            }

            foreach ($tmpHotels as $hotel) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($hotel->totalPrice >= $priceArr[0] && $hotel->totalPrice <= $priceArr[1]) {
                            $hotels[] = $hotel;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($hotel->totalPrice <= $priceArr[0]) {
                                $hotels[] = $hotel;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($hotel->totalPrice >= $priceArr[0]) {
                                $hotels[] = $hotel;
                            }
                        }
                    }
                }
            }
            foreach ($tmpLandTours as $landTour) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($landTour->totalPrice >= $priceArr[0] && $landTour->totalPrice <= $priceArr[1]) {
                            $landTours[] = $landTour;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($landTour->totalPrice <= $priceArr[0]) {
                                $landTours[] = $landTour;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($landTour->totalPrice >= $priceArr[0]) {
                                $landTours[] = $landTour;
                            }
                        }
                    }
                }
            }
            $listPrice = implode(',', $filterPriceArr);
        } else {
            $combos = $tmpCombos;
            $hotels = $tmpHotels;
            $landTours = $tmpLandTours;
            $listPrice = '';
        }

        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'asc');
                $hotels = \Cake\Utility\Hash::sort($hotels, '{n}.totalPrice', 'asc');
                $landTours = \Cake\Utility\Hash::sort($landTours, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'desc');
                $hotels = \Cake\Utility\Hash::sort($hotels, '{n}.totalPrice', 'desc');
                $landTours = \Cake\Utility\Hash::sort($landTours, '{n}.totalPrice', 'desc');
            }
        }

        $countCombos = count($combos);
        $combos = array_slice($combos, 0, 9);

        $countHotels = count($hotels);
        $hotels = array_slice($hotels, 0, 9);

        $countLandtours = count($landTours);
        $landTours = array_slice($landTours, 0, 9);

//        dd($combos);


        $locations = $this->Locations->find();
        $title = $location->name;
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Địa điểm', 'href' => \Cake\Routing\Router::url('/danh-sach-diem-den')],
            ['title' => $location->name, 'href' => '#']
        ];
        $this->set('location', $location);
        $this->set(compact('title', 'combos', 'hotels', 'landTours', 'headerType', 'breadcrumbs', 'locations', 'sortPrice', 'listLocation', 'listPrice', 'listRating', 'countHotels', 'countLandtours', 'countCombos'));
    }

    /**
     * View method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $testUrl = $this->viewVars['testUrl'];
//        $var = $this->Util->getListHotel($testUrl, 1, 100);
//        $var = $this->Util->getDetailHotel($testUrl, '37e46185-b3ec-42f6-bcc7-ba98e230e7b0');
//        dd($var);
        $this->loadModel('Combos');
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');
        $this->loadModel('HomeStays');
        $this->loadModel('Rooms');
        $this->loadModel('HotelSearchs');

        $slug = $this->request->getParam('slug');
        $location = $this->Locations->find()->where(['slug' => $slug])->first();


        // build condition & sort
        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $listLocation = explode(',', $filterLocation);
        $listPrice = explode(',', $filterPrice);
        $listRating = explode(',', $filterRating);
        $filterSlider = $this->request->getQuery('slider-price');

        $condition = $conditionHotel = $order = [];
        $condition['destination_id'] = $location->id;
        $conditionHotel['location_id'] = $location->id;

        $outputSlider = '';
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }
        if ($filterLocation) {
            $condition['destination_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
            $conditionHotel['rating IN'] = $listRating;
        }

        // do query

        $today = date('Y-m-d');

        $tmpHomestays = $this->HomeStays->find()->contain(['Locations', 'PriceHomeStays'])->where($conditionHotel)->order(['HomeStays.is_feature' => 'DESC'])->toArray();
        $homestays = [];


        $today = date('Y-m-d');
        $tmpHotels = $this->HotelSearchs->find()->where(['single_day' => $today, $conditionHotel])->order(['is_feature' => 'DESC'])->toArray();
        if ($tmpHotels) {
            $locationIds = Hash::extract($tmpHotels, '{n}.location_id');
            $hotelIds = Hash::extract($tmpHotels, '{n}.id');
        } else {
            $locationIds[] = $location->id;
            $hotelIds[] = 0;
        }
        $missinHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'location_id IN' => $locationIds, $conditionHotel])->toArray();
        foreach ($missinHotels as $key => $item) {
            $item->price_day = 0;
            if ($item->location) {
                $item->location_name = $item->location->name;
            } else {
                $item->location_name = '';
            }
            $tmpHotels[] = $item;
        }


        $hotels = [];

        if ($this->Auth->user('role_id') == 3) {
            $agencyUserId = $this->Auth->user('id');
        } else {
            $agencyUserId = 0;
        }
        $tmpLandTours = $this->LandTours->find()->contain(['Destinations', 'Departures', 'LandTourAccessories', 'LandTourUserPrices' => function ($q) use ($agencyUserId) {
            return $q->where(['user_id' => $agencyUserId]);
        }])->where($condition)->order(['LandTours.is_feature' => 'ASC'])->toArray();
        $landTours = [];

        foreach ($tmpLandTours as $key => $landTour) {
            if (count($landTour['land_tour_user_prices']) > 0) {
                $tmpLandTours[$key]->totalPrice = $landTour->price + $landTour->customer_price + $landTour['land_tour_user_prices'][0]['price'];
            } else {
                $tmpLandTours[$key]->totalPrice = $landTour->price + $landTour->trippal_price + $landTour->customer_price;
            }
        }
        foreach ($tmpLandTours as $key => $landTour) {
            if (count($landTour['land_tour_accessories']) > 0) {
                foreach ($landTour['land_tour_accessories'] as $k => $accessory) {
                    $tmpLandTours[$key]->totalPrice += $accessory->adult_price;
                }
            }
        }
        foreach ($tmpHomestays as $key => $homestay) {
            $tmpHomestays[$key]->totalPrice = $this->Util->countingHomeStayPrice($today, $homestay);
        }

        $combos = [];

        if ($filterPrice) {
            $lists = explode(',', $filterPrice);
            $filterPriceArr = [];
            foreach ($lists as $key => $singleList) {
                $arrray = explode('-', $singleList);
                $filterPriceArr[] = $arrray;
            }

            foreach ($tmpHotels as $key => $hotel) {
                if (!$hotel->price_day) {
                    unset($tmpHotels[$key]);
                    continue;
                }
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($hotel->price_day >= $priceArr[0] && $hotel->price_day <= $priceArr[1]) {
                            $hotels[] = $hotel;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($hotel->price_day <= $priceArr[0]) {
                                $hotels[] = $hotel;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($hotel->price_day >= $priceArr[0]) {
                                $hotels[] = $hotel;
                            }
                        }
                    }
                }
            }

            foreach ($tmpHomestays as $homestay) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($homestay->totalPrice >= $priceArr[0] && $homestay->totalPrice <= $priceArr[1]) {
                            $homestays[] = $homestay;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($homestay->totalPrice <= $priceArr[0]) {
                                $homestays[] = $homestay;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($homestay->totalPrice >= $priceArr[0]) {
                                $homestays[] = $homestay;
                            }
                        }
                    }
                }
            }

            foreach ($tmpLandTours as $landTour) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($landTour->totalPrice >= $priceArr[0] && $landTour->totalPrice <= $priceArr[1]) {
                            $landTours[] = $landTour;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($landTour->totalPrice <= $priceArr[0]) {
                                $landTours[] = $landTour;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($landTour->totalPrice >= $priceArr[0]) {
                                $landTours[] = $landTour;
                            }
                        }
                    }
                }
            }
        } else {
            $hotels = $tmpHotels;
            $homestays = $tmpHomestays;
            $landTours = $tmpLandTours;
            $listPrice = '';
        }
        if ($filterSlider) {
            $combos = [];
            $landTours = [];
            $homestays = [];
            $hotels = [];
            $filterPriceArr = explode('-', $filterSlider);
            foreach ($tmpHomestays as $homestay) {
                if (count($filterPriceArr) == 2) {
                    if ($homestay->totalPrice >= $filterPriceArr[0] && $homestay->totalPrice <= $filterPriceArr[1]) {
                        $homestays[] = $homestay;
                    }
                }
            }
            foreach ($tmpLandTours as $landTour) {
                if (count($filterPriceArr) == 2) {
                    if ($landTour->totalPrice >= $filterPriceArr[0] && $landTour->totalPrice <= $filterPriceArr[1]) {
                        $landTours[] = $landTour;
                    }
                }
            }
            foreach ($tmpHotels as $key => $hotel) {
                if (!$hotel->price_day) {
                    unset($tmpHotels[$key]);
                    continue;
                }
                if (count($filterPriceArr) == 2) {
                    if ($hotel->price_day >= $filterPriceArr[0] && $hotel->price_day <= $filterPriceArr[1]) {
                        $hotels[] = $hotel;
                    }
                }
            }
//            $listPrice = implode(',', $filterPriceArr);
        }

        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'asc');
                $hotels = \Cake\Utility\Hash::sort($hotels, '{n}.price_day', 'asc');
                $homestays = \Cake\Utility\Hash::sort($homestays, '{n}.totalPrice', 'asc');
                $landTours = \Cake\Utility\Hash::sort($landTours, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'desc');
                $hotels = \Cake\Utility\Hash::sort($hotels, '{n}.price_day', 'desc');
                $homestays = \Cake\Utility\Hash::sort($homestays, '{n}.totalPrice', 'desc');
                $landTours = \Cake\Utility\Hash::sort($landTours, '{n}.totalPrice', 'desc');
            }
        }

        $countHotels = count($hotels);
        $hotels = array_slice($hotels, 0, 9);

        $countHomeStays = count($homestays);
        $homestays = array_slice($homestays, 0, 9);

        $countLandtours = count($landTours);
        $landTours = array_slice($landTours, 0, 9);

        $title = $location->name;
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Địa điểm', 'href' => \Cake\Routing\Router::url('/danh-sach-diem-den')],
            ['title' => $location->name, 'href' => '#']
        ];
//        $this->set('location', $location);
        $listPrice = explode(',', $filterPrice);
//        dd($listPrice);
        $this->set(compact('title', 'countHomeStays', 'homestays', 'hotels', 'landTours', 'headerType', 'breadcrumbs', 'location', 'sortPrice', 'listLocation', 'listPrice', 'listRating', 'countHotels', 'countLandtours', 'outputSlider'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $location = $this->Locations->patchEntity($location, $this->request->getData());
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $this->set(compact('location'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $location = $this->Locations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $location = $this->Locations->patchEntity($location, $this->request->getData());
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $this->set(compact('location'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $location = $this->Locations->get($id);
        if ($this->Locations->delete($location)) {
            $this->Flash->success(__('The location has been deleted.'));
        } else {
            $this->Flash->error(__('The location could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function hotVoucher($id = null)
    {
        $this->loadModel('Vouchers');

        $slug = $this->request->getParam('slug');
        $location = $this->Locations->find()->where(['slug' => $slug])->first();

        $this->paginate = [
            'limit' => 12
        ];


        $sortPrice = $this->request->getQuery('sort');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');


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

        if ($listRating) {
            $condition['rating IN'] = $listRating;
        }
        $condition['destination_id'] = $location->id;

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
        }


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
            ['title' => 'Địa điểm', 'href' => ''],
            ['title' => $location->name, 'href' => '']
        ];
//        dd($vouchers);


        $this->set(compact('location', 'vouchers', 'title', 'breadcrumbs', 'sortPrice', 'listPrice', 'listRating', 'headerType', 'outputSlider'));
    }

    private function sortByPriceAsc($a, $b)
    {
        return $a->totalPrice > $b->totalPrice;
    }

    private function sortByPriceDsc($a, $b)
    {
        return $a->totalPrice < $b->totalPrice;
    }

    public function combo()
    {
        $this->loadModel('Combos');
        $slug = $this->request->getParam('slug');
        $location = $this->Locations->find()->where(['slug' => $slug])->first();
//        dd($location);
        $departure_id = $this->request->getQuery('departure_id');
        $destination_id = $this->request->getQuery('destination_id');
        $fromDate = $this->request->getQuery('fromDate');
        $toDate = $this->request->getQuery('toDate');

        $slug = $this->request->getParam('slug');

        // build condition & sort
        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $listLocation = explode(',', $filterLocation);
        $listRating = explode(',', $filterRating);

        $condition = $conditionHotel = $order = [];
        if ($filterLocation) {
            $condition['destination_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
        }

        $today = date('Y-m-d');

        $tmpCombos = $this->Combos->find()->contain([
            'Destinations',
            'Departures',
            'Hotels',
            'Hotels.PriceHotels'
        ])->where($condition)->order($order)->toArray();
        $combos = [];

        foreach ($tmpCombos as $key => $combo) {
            $tmpCombos[$key]->totalPrice = $this->Util->countingComboPrice($today, $combo);
        }

        if ($filterPrice) {
            $filterPriceArr = explode('-', $filterPrice);
            foreach ($tmpCombos as $combo) {
                if ($combo->totalPrice >= $filterPriceArr[0] && $combo->totalPrice <= $filterPriceArr[1]) {
                    $combos[] = $combo;
                }
            }
            $listPrice = implode(',', $filterPriceArr);
        } else {
            $combos = $tmpCombos;
            $listPrice = '';
        }

        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'desc');
            }
        }

        $title = "Combo tại " . $location->name;
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Địa điểm', 'href' => \Cake\Routing\Router::url('/danh-sach-diem-den')],
            ['title' => $location->name, 'href' => '#']
        ];
        $this->set('location', $location);
        $this->set(compact('title', 'combos', 'headerType', 'breadcrumbs', 'sortPrice', 'listPrice', 'listRating', 'countCombos'));
    }

    public function hotel()
    {
        $this->loadModel('Hotels');
        $this->loadModel('Rooms');
        $this->loadModel('HotelSearchs');
        $this->loadModel('Vinrooms');
        $slug = $this->request->getParam('slug');
        $location = $this->Locations->find()->where(['slug' => $slug])->first();
        $testUrl = $this->viewVars['testUrl'];

        $departure_id = $this->request->getQuery('departure_id');
        $destination_id = $this->request->getQuery('destination_id');
        $fromDate = $this->request->getQuery('fromDate');
        $toDate = $this->request->getQuery('toDate');

        // build condition & sort
        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');
        $listLocation = explode(',', $filterLocation);
        $listRating = explode(',', $filterRating);

        $condition = $conditionHotel = $order = [];
        $condition['destination_id'] = $location->id;
        $conditionHotel['location_id'] = $location->id;
        $today = date('Y-m-d');
//        $conditionHotel['single_day'] = $today;
        $outputSlider = '';
        $listPrice = [];
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }

        if ($filterLocation) {
            $condition['destination_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
            $conditionHotel['rating IN'] = $listRating;
        }
        //$tmpHotels = $this->Hotels->find()->contain(['Locations', 'Rooms'] )->where($conditionHotel)->order($order)->toArray();
        $tmpHotelSearchs = $this->HotelSearchs->find()->where([
            $conditionHotel,
            'single_day' => $today,
            'is_vinhms' => 0
        ])->order($order)->toArray();
        if ($tmpHotelSearchs) {
            $locationIds = Hash::extract($tmpHotelSearchs, '{n}.location_id');
            $hotelIds = Hash::extract($tmpHotelSearchs, '{n}.id');
        } else {
            $locationIds[] = $location->id;
            $hotelIds[] = 0;
        }
        $missinHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'location_id IN' => $locationIds, 'is_vinhms' => 0, $conditionHotel])->toArray();
        foreach ($missinHotels as $key => $item) {
            $item->price_day = 0;
            if ($item->location) {
                $item->location_name = $item->location->name;
            } else {
                $item->location_name = '';
            }
            $tmpHotelSearchs[] = $item;
        }

        $vinHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'location_id IN' => $locationIds, 'is_vinhms' => 1, $conditionHotel])->toArray();
        foreach ($vinHotels as $key => $item) {
            $data = [
                "arrivalDate" => date('Y-m-d', strtotime('tomorrow')),
                "departureDate" => date('Y-m-d', strtotime('tomorrow + 1 day')),
                "numberOfRoom" => 1,
                "propertyIds" => [$item->vinhms_code],
                "roomOccupancy" => []
            ];
            $roomOccupancy = [
                'numberOfAdult' => 1,
                'otherOccupancies' => [
                    [
                        'otherOccupancyRefCode' => 'child',
                        'quantity' => 0
                    ],
                    [
                        'otherOccupancyRefCode' => 'infant',
                        'quantity' => 0
                    ]
                ]
            ];
            $data['roomOccupancy'] = $roomOccupancy;
            $hotelAvailableAPI = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
            if (isset($hotelAvailableAPI['isSuccess'])) {
                if (!empty($hotelAvailableAPI['data']['rates'])) {
                    $item->price_day = $hotelAvailableAPI['data']['rates'][0]['rates'][0]['totalAmount']['amount']['amount'];
                    $vinroom = $this->Vinrooms->find()->where(['hotel_id' => $item->id, 'vin_code' => $hotelAvailableAPI['data']['rates'][0]['rates'][0]['roomTypeID']])->first();
                } else {
                    $vinroom = null;
                }
            } else {
                $item->price_day = 0;
                $vinroom = null;
            }
            if ($vinroom) {
                if ($this->Auth->user('role_id') == 3) {
                    $item->price_day += ($vinroom->trippal_price != 0 ? $vinroom->trippal_price : $item->price_agency);
                } else {
                    $item->price_day += ($vinroom->trippal_price != 0 ? $vinroom->trippal_price : $item->price_agency) + ($vinroom->customer_price != 0 ? $vinroom->customer_price : $item->price_customer);
                }
            } else {
                if ($this->Auth->user('role_id') == 3) {
                    $item->price_day += $item->price_agency;
                } else {
                    $item->price_day += $item->price_agency + $item->price_customer;
                }
            }
            if ($item->location) {
                $item->location_name = $item->location->name;
            } else {
                $item->location_name = '';
            }
            $tmpHotelSearchs[] = $item;
        }

        $hotels = [];
//        foreach ($tmpHotels as $key => $hotel) {
//            if (!empty($hotel['rooms'])) {
//                $resPrice = $this->Util->calculateHotelPrice($hotel, $hotel['rooms'][0]->id);
//                $tmpHotels[$key]->priceByDay = $resPrice['price'];
//            }
//        }
        if ($filterPrice) {
            if (!$filterPrice) {
                $filterPrice = [];
            }
            $lists = explode(',', $filterPrice);
            $listPrice = array_merge($lists, $listPrice);

        }
        $filterPriceArr = [];
        foreach ($listPrice as $key => $singleList) {
            $arrray = explode('-', $singleList);
            $filterPriceArr[] = $arrray;
        }
        if ($filterPriceArr) {
            foreach ($tmpHotelSearchs as $key => $hotel) {
                if ($hotel->price_day == 0) {
                    unset($tmpHotelSearchs[$key]);
                    continue;
                }
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($hotel->price_day >= $priceArr[0] && $hotel->price_day <= $priceArr[1]) {
                            $hotels[] = $hotel;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($hotel->price_day <= $priceArr[0]) {
                                $hotels[] = $hotel;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($hotel->price_day >= $priceArr[0]) {
                                $hotels[] = $hotel;
                            }
                        }
                    }
                }
            }
        } else {
            $hotels = $tmpHotelSearchs;
            $listPrice = '';
        }

        $hotels = \Cake\Utility\Hash::sort($hotels, '{n}.id', 'asc');

        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $hotels = \Cake\Utility\Hash::sort($hotels, '{n}.price_day', 'asc');
            }
            if ($sortPrice == "DESC") {
                $hotels = \Cake\Utility\Hash::sort($hotels, '{n}.price_day', 'desc');
            }
        }
        $title = $location->name;
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Địa điểm', 'href' => \Cake\Routing\Router::url('/danh-sach-diem-den')],
            ['title' => $location->name, 'href' => '#']
        ];
        $listPrice = explode(',', $filterPrice);
        $this->set('location', $location);
        $this->set(compact('title', 'hotels', 'headerType', 'breadcrumbs', 'location', 'sortPrice', 'listPrice', 'listRating', 'outputSlider'));
    }

    public function homestay()
    {
        $this->loadModel('HomeStays');
        $slug = $this->request->getParam('slug');
        $location = $this->Locations->find()->where(['slug' => $slug])->first();

        $departure_id = $this->request->getQuery('departure_id');
        $destination_id = $this->request->getQuery('destination_id');
        $fromDate = $this->request->getQuery('fromDate');
        $toDate = $this->request->getQuery('toDate');

        // build condition & sort
        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');
        $listLocation = explode(',', $filterLocation);
        $listRating = explode(',', $filterRating);

        $condition = $conditionHomeStay = $order = [];
        $condition['destination_id'] = $location->id;
        $conditionHomeStay['location_id'] = $location->id;
        $outputSlider = '';
        $listPrice = [];
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }
        if ($filterLocation) {
            $condition['destination_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
            $conditionHomeStay['rating IN'] = $listRating;
        }


        $today = date('Y-m-d');

        $tmpHomeStays = $this->HomeStays->find()->contain(['Locations', 'PriceHomeStays'])->where($conditionHomeStay)->order($order)->toArray();


        $homestays = [];

        foreach ($tmpHomeStays as $key => $homestay) {
            $tmpHomeStays[$key]->totalPrice = $this->Util->countingHomeStayPrice($today, $homestay);
        }

        if ($filterPrice) {
            if (!$filterPrice) {
                $filterPrice = [];
            }
            $lists = explode(',', $filterPrice);
            $listPrice = array_merge($lists, $listPrice);

        }
        $filterPriceArr = [];
        foreach ($listPrice as $key => $singleList) {
            $arrray = explode('-', $singleList);
            $filterPriceArr[] = $arrray;
        }
        if ($filterPriceArr) {
            foreach ($tmpHomeStays as $homeStay) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($homeStay->totalPrice >= $priceArr[0] && $homeStay->totalPrice <= $priceArr[1]) {
                            $homestays[] = $homeStay;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($homeStay->totalPrice <= $priceArr[0]) {
                                $homestays[] = $homeStay;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($homeStay->totalPrice >= $priceArr[0]) {
                                $homestays[] = $homeStay;
                            }
                        }
                    }
                }
            }
        } else {
            $homestays = $tmpHomeStays;
            $listPrice = '';
        }

        if ($filterSlider) {
            $homestays = [];
            $filterPriceArr = explode('-', $filterSlider);
            foreach ($tmpHomeStays as $homeStay) {
                if (count($filterPriceArr) == 2) {
                    if ($homeStay->totalPrice >= $filterPriceArr[0] && $homeStay->totalPrice <= $filterPriceArr[1]) {
                        $homestays[] = $homeStay;
                    }
                }
            }
//            $listPrice = implode(',', $filterPriceArr);
        }

        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $homestays = \Cake\Utility\Hash::sort($homestays, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $homestays = \Cake\Utility\Hash::sort($homestays, '{n}.totalPrice', 'desc');
            }
        }

        $title = $location->name;
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Địa điểm', 'href' => \Cake\Routing\Router::url('/danh-sach-diem-den')],
            ['title' => $location->name, 'href' => '#']
        ];
        $listPrice = explode(',', $filterPrice);
        $this->set(compact('title', 'homestays', 'headerType', 'breadcrumbs', 'location', 'sortPrice', 'listPrice', 'listRating', 'outputSlider'));
    }

    public function landtour()
    {
        $this->loadModel('LandTours');

        $departure_id = $this->request->getQuery('departure_id');
        $destination_id = $this->request->getQuery('destination_id');
        $fromDate = $this->request->getQuery('fromDate');
        $toDate = $this->request->getQuery('toDate');
        $slug = $this->request->getParam('slug');
        $location = $this->Locations->find()->where(['slug' => $slug])->first();

        // build condition & sort
        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');
        $listLocation = explode(',', $filterLocation);
        $listRating = explode(',', $filterRating);

        $condition = $conditionHotel = $order = [];
        $condition['destination_id'] = $location->id;
        $conditionHotel['location_id'] = $location->id;
        $outputSlider = '';
        $listPrice = [];
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }

        if ($filterLocation) {
            $condition['destination_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
            $conditionHotel['rating IN'] = $listRating;
        }
        if ($this->Auth->user('role_id') == 3) {
            $agencyUserId = $this->Auth->user('id');
        } else {
            $agencyUserId = 0;
        }
        $today = date('Y-m-d');

        $tmpLandTours = $this->LandTours->find()->contain(['Destinations', 'Departures', 'LandTourAccessories', 'LandTourUserPrices' => function ($q) use ($agencyUserId) {
            return $q->where(['user_id' => $agencyUserId]);
        }])
            ->where($condition)
            ->order($order)
            ->toArray();
        $landTours = [];

        foreach ($tmpLandTours as $key => $landTour) {
            if (count($landTour['land_tour_user_prices']) > 0) {
                $tmpLandTours[$key]->totalPrice = $landTour->price + $landTour->customer_price + $landTour['land_tour_user_prices'][0]['price'];
            } else {
                $tmpLandTours[$key]->totalPrice = $landTour->price + $landTour->trippal_price + $landTour->customer_price;
            }
        }
        foreach ($tmpLandTours as $key => $landTour) {
            if (count($landTour['land_tour_accessories']) > 0) {
                foreach ($landTour['land_tour_accessories'] as $k => $accessory) {
                    $tmpLandTours[$key]->totalPrice += $accessory->adult_price;
                }
            }
        }

        if ($filterPrice) {
            if (!$filterPrice) {
                $filterPrice = [];
            }
            $lists = explode(',', $filterPrice);
            $listPrice = array_merge($lists, $listPrice);
        }
        $filterPriceArr = [];
        foreach ($listPrice as $key => $singleList) {
            $arrray = explode('-', $singleList);
            $filterPriceArr[] = $arrray;
        }
        if ($filterPriceArr) {
            foreach ($listPrice as $key => $singleList) {
                $arrray = explode('-', $singleList);
                $filterPriceArr[] = $arrray;
            }

            foreach ($tmpLandTours as $landTour) {
                foreach ($filterPriceArr as $priceArr) {
                    if (count($priceArr) == 2) {
                        if ($landTour->totalPrice >= $priceArr[0] && $landTour->totalPrice <= $priceArr[1]) {
                            $landTours[] = $landTour;
                        }
                    } else {
                        if ($priceArr[0] == '2000000') {
                            if ($landTour->totalPrice <= $priceArr[0]) {
                                $landTours[] = $landTour;
                            }
                        }
                        if ($priceArr[0] == '10000000') {
                            if ($landTour->totalPrice >= $priceArr[0]) {
                                $landTours[] = $landTour;
                            }
                        }
                    }
                }
            }
        } else {
            $landTours = $tmpLandTours;
            $listPrice = '';
        }


        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $landTours = \Cake\Utility\Hash::sort($landTours, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $landTours = \Cake\Utility\Hash::sort($landTours, '{n}.totalPrice', 'desc');
            }
        }

        $countLandtours = count($landTours);
        $locations = $this->Locations->find();
        $title = $location->name;
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Địa điểm', 'href' => \Cake\Routing\Router::url('/danh-sach-diem-den')],
            ['title' => $location->name, 'href' => '#']
        ];
        $listPrice = explode(',', $filterPrice);
        $this->set('location', $location);
        $this->set(compact('title', 'landTours', 'headerType', 'breadcrumbs', 'outputSlider', 'locations', 'sortPrice', 'listLocation', 'listPrice', 'listRating', 'countLandtours'));
    }

}
