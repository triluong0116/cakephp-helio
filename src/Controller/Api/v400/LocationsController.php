<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 4/10/2019
 * Time: 7:42 PM
 */

namespace App\Controller\Api\v400;
use App\Controller\Api\AppController;

/**
 * Locations Controller
 *
 * @property \App\Model\Table\LocationsTable $Locations
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\RoomsTable $Rooms
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */

class LocationsController extends AppController {

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail', 'feature']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function lists() {
        $locations = $this->Locations->find()->toArray();
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $locations,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function feature() {
        $locations = $this->Locations->find()->where(['is_featured' => 1]);
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $locations,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id) {
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');
        $this->loadModel('Vouchers');
        $this->loadModel('Combos');
        $this->loadModel('HomeStays');
        $this->loadModel('Rooms');
        $location = $this->Locations->get($id);
        $hotels = $this->Hotels->find()->where(['location_id' => $location->id])->limit(6);
        $landtours = $this->LandTours->find()->where(['destination_id' => $location->id])->limit(6);
        $vouchers = $this->Vouchers->find()->where(['destination_id' => $location->id])->limit(6);
        $homestays = $this->HomeStays->find()->contain(['PriceHomeStays'])->where(['location_id' => $location->id])->limit(6);

        foreach ($hotels as $hotel) {
            $rooms = $this->Rooms->find()->contain(['PriceRooms'])->where(['hotel_id' => $hotel->id])->toArray();
            $singlePrice = 0;
            if (isset($rooms[0]) && !empty($rooms[0])) {
                $firstRoom = $rooms[0];
                $today = date('Y-m-d');
                $singlePrice = $this->Util->calculateHotelPrice($hotel, $firstRoom->id, $today);
            }
            $hotel->singlePrice = $singlePrice['price'];
        }
        foreach ($landtours as $landtour) {
            $landtour->singlePrice = $landtour->price + $landtour->trippal_price + $landtour->customer_price;
        }
        foreach ($vouchers as $voucher) {
            $voucher->singlePrice = $voucher->price + $voucher->trippal_price + $voucher->customer_price;
        }
        foreach ($homestays as $homestay) {
            $today = date('Y-m-d');
            $singlePrice = $this->Util->countingHomeStayPrice($today, $homestay);
            $homestay->singlePrice = $singlePrice;
            unset($homestay->price_home_stays);
        }

        $location->hotels = $hotels;
        $location->landtours = $landtours;
        $location->vouchers = $vouchers;
        $location->hometays = $homestays;
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $location,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }



    /**
     * View method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $this->loadModel('Combos');
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');

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
        $price_condition = [];
        if ($filterPrice) {
//            dd($filterPrice);
            foreach ($listPrice as $key => $price) {
                $price_arr = explode('-', $price);
                if (count($price_arr) == 2) {
                    $price_condition[$key][] = $price_arr[0];
                    $price_condition[$key][] = $price_arr[1];
//                    dd($price_condition[$key]);
                } else {
                    $price_condition[$key][] = $price_arr[0];
                }
            }
        }
        // do query

        $today = date('Y-m-d');

        $tmpHotels = $this->Hotels->find()->contain(['Locations', 'PriceHotels'])->where($conditionHotel)->order($order)->toArray();
        $countHotels = $this->Hotels->find()->contain(['Locations', 'PriceHotels'])->where($conditionHotel)->count();

        $hotels = [];


        $tmpLandTours = $this->LandTours->find()->contain(['Destinations', 'Departures'])->where($condition)->order($order)->toArray();
        $countLandtours = $this->LandTours->find()->contain(['Destinations', 'Departures'])->where($condition)->count();
        $landTours = [];

        $tmpCombos = $this->Combos->find()->contain([
            'Destinations',
            'Departures',
            'Hotels',
            'Hotels.PriceHotels'
        ])->where($condition)->order($order)->toArray();
        $countCombos = $this->Combos->find()->contain([
            'Destinations',
            'Departures',
            'Hotels',
            'Hotels.PriceHotels'
        ])->where($condition)->count();


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

        if ($price_condition) {
            foreach ($tmpCombos as $combo) {
                $check = false;
                for ($i = 0; $i < count($price_condition); $i++) {
                    if (count($price_condition[$i]) == 2) {
//                        echo 1; die;
                        if ($price_condition[$i][0] <= $combo->totalPrice && $price_condition[$i][1] >= $combo->totalPrice) {
                            $check = true;
                            break;
                        }
                    } else {
                        if ($combo->totalPrice < 2000000 && $price_condition[$i][0] == 2000000) {
                            $check = true;
                            break;
                        }
                        if ($combo->totalPrice > 10000000 && $price_condition[$i][0] == 10000000) {
                            $check = true;
                            break;
                        }
                    }
                }
                if ($check) {
                    $combos[] = $combo;
                }
            }

            foreach ($tmpHotels as $hotel) {
                $check = false;
                for ($i = 0; $i < count($price_condition); $i++) {
                    if (count($price_condition[$i]) == 2) {
                        if ($price_condition[$i][0] <= $hotel->totalPrice && $price_condition[$i][1] >= $hotel->totalPrice) {
                            $check = true;
                            break;
                        }
                    } else {
                        if ($hotel->totalPrice < 2000000 && $price_condition[$i][0] == 2000000) {
                            $check = true;
                            break;
                        }
                        if ($hotel->totalPrice > 10000000 && $price_condition[$i][0] == 10000000) {
                            $check = true;
                            break;
                        }
                    }
                }
                if ($check) {
                    $hotels[] = $hotel;
                }
            }
            foreach ($tmpLandTours as $landTour) {
                $check = false;
                for ($i = 0; $i < count($price_condition); $i++) {
                    if (count($price_condition[$i]) == 2) {
                        if ($price_condition[$i][0] <= $landTour->totalPrice && $price_condition[$i][1] >= $landTour->totalPrice) {
                            $check = true;
                            break;
                        }
                    } else {
                        if ($landTour->totalPrice < 2000000 && $price_condition[$i][0] == 2000000) {
                            $check = true;
                            break;
                        }
                        if ($landTour->totalPrice > 10000000 && $price_condition[$i][0] == 10000000) {
                            $check = true;
                            break;
                        }
                    }
                }
                if ($check) {
                    $landTours[] = $landTour;
                }
            }
        } else {
            $combos = $tmpCombos;
            $hotels = $tmpHotels;
            $landTours = $tmpLandTours;
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
//        $locations = $this->Locations->find();
        $title = $location->name;
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Địa điểm', 'href' => \Cake\Routing\Router::url('/danh-sach-diem-den')],
            ['title' => $location->name, 'href' => '#']
        ];
//        $this->set('location', $location);
        $this->set(compact('title', 'combos', 'hotels', 'landTours', 'headerType', 'breadcrumbs', 'location', 'sortPrice', 'listLocation', 'listPrice', 'listRating', 'countHotels', 'countLandtours', 'countCombos'));
    }

    private function sortByPriceAsc($a, $b) {
        return $a->totalPrice > $b->totalPrice;
    }

    private function sortByPriceDsc($a, $b) {
        return $a->totalPrice < $b->totalPrice;
    }
}
