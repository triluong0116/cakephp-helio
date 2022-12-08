<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 4/10/2019
 * Time: 7:42 PM
 */

namespace App\Controller\Api\v400;
use App\Controller\Api\AppController;

use Cake\Utility\Hash;

/**
 * Locations Controller
 *
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HomeStaysTable $HomeStays
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HotelsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail', 'calPrice', 'search', 'calPriceBooking', 'searchSuggest']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function lists()
    {
        $this->paginate = [
            'limit' => 10
        ];
        $location_id = $this->getRequest()->getQuery('location_id');
        $rating = $this->getRequest()->getQuery('rating');
        $price = $this->getRequest()->getQuery('price');
        $clientId = $this->getRequest()->getQuery('clientId');
        $fromDate = $this->getRequest()->getQuery('fromDate');
        $toDate = $this->getRequest()->getQuery('toDate');
        $keyword = $this->getRequest()->getQuery('keyword');
        $page = $this->getRequest()->getQuery('page');
        $this->loadModel('Rooms');
        $this->loadModel('HotelSearchs');
        $condition = [];
        $today = date('Y-m-d');
        $condition['single_day'] = $today;
        $outputSlider = '';

//        $data['location_id'] = $location_id;
//        $data['rating'] = $rating;
//        $data['price'] = $price;
//        $data['clientId'] = $clientId;
//        $data['page'] = $page;
//        $data = $this->getRequest()->getQuery();
//        $this->Util->writeLogFile($data, HOTEL);

        if ($location_id) {
            $condition['location_id'] = $location_id;
        } else {
            if ($keyword) {
                $condition['HotelSearchs.name LIKE '] = '%' . $keyword . '%';
            }
        }
        if ($price) {
            $listPrice[] = $price;
            $sliderArray = explode('-', $price);
            $outputSlider = implode(',', $sliderArray);
            if ($sliderArray) {
                $condition['price_day >= '] = $sliderArray[0];
                $condition['price_day <= '] = $sliderArray[1];
            }
        }

        if ($rating) {
            $condition['rating IN'] = $rating;
        }
        $hotels = $this->HotelSearchs->find()->contain([
            'Favourites' => function ($q) use ($clientId) {
                return $q->where(['clientId' => $clientId]);
            },
            'Rooms'])
            ->where([$condition])->toArray();
        foreach ($hotels as $hotel) {
            if ($hotel->favourites) {
                $hotel->is_favourite = true;
            } else {
                $hotel->is_favourite = false;
            }
            unset($hotel->favourites);
        }
        if ($hotels) {
            $hotelIds = Hash::extract($hotels, '{n}.id');
        } else {
            $hotelIds[] = 0;
        }
        $missingHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'Hotels.name LIKE' => '%' . $keyword . '%', 'Hotels.location_id' => $location_id])->toArray();
        foreach ($missingHotels as $key => $item) {
            $item->price_day = 0;
            if ($item->location) {
                $item->location_name = $item->location->name;
            } else {
                $item->location_name = '';
            }
            $hotels[] = $item;
        }
        foreach ($hotels as $hotel) {
            $hotel->singlePrice = $hotel->price_day;
        }
        $hotels = array_slice($hotels, 10 * ($page - 1), 10);
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $hotels,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id)
    {
        $this->loadModel('HotelSurcharges');
        $this->loadComponent('Util');
        $clientId = $this->getRequest()->getQuery('clientId');
        $hotel = $this->Hotels->get($id, ['contain' => [
            'Categories' => function ($q) {
                return $q->select(['icon', 'name']);
            },
            'Rooms',
            'Favourites' => function ($q) use ($clientId) {
                return $q->where(['clientId' => $clientId]);
            }]]);
        $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
        $normalSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type NOT IN' => $constantAutoSurcharge, 'hotel_id' => $hotel->id]);
        $autoSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type IN' => $constantAutoSurcharge, 'hotel_id' => $hotel->id]);
        $surcharges = array_merge($autoSurcharges->toArray(), $normalSurcharges->toArray());
        foreach ($surcharges as $key => $surcharge) {
            if ($surcharge->surcharge_type != SUR_OTHER) {
                $surcharge->other_name = $this->Util->getSurchargeName($surcharge['surcharge_type']);
            }
            $surcharge->surcharge_id_price = $this->Util->getSurchargeId($surcharge['surcharge_type'], $surcharge['other_slug']);
        }
        $hotel->hotel_surcharges = [];
        $hotel->hotel_surcharges = $surcharges;
        $hotel->long = $hotel->lon;
        $hotel->isSpecial = $hotel->is_special;
        unset($hotel->lon);
        $listCaption = json_decode($hotel->caption, true);
        foreach ($listCaption as $key => $caption) {
            $listCaption[$key]['content'] = strip_tags(html_entity_decode($caption['content']));
        }
        $hotel->caption = $listCaption;
        $hotel->media = json_decode($hotel->media, true);
        $hotel->term = json_decode($hotel->term, true);
        if ($hotel->favourites) {
            $hotel->is_favourite = true;
        } else {
            $hotel->is_favourite = false;
        }
        unset($hotel->favourites);

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $hotel,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function calPrice()
    {
        $this->loadModel('Rooms');

        $data = $this->getRequest()->getQuery();
        $hotel = $this->Hotels->get($data['hotel_id']);
        $rooms = $this->Rooms->find()->where(['hotel_id' => $data['hotel_id']])->toArray();
        $numb_room = $data['numRoom'];
//        $numb_adult = $data['numAdult'];
//        $numb_children = $data['numChildren'];

        $calSDate = $data['fromDate'];
        $calEDate = date('d-m-Y', strtotime($data['toDate'] . "-1 days"));
        $dates = $this->Util->_dateRange($calSDate, $calEDate);

        $dataRoomPrices = $this->Util->calculateHotelPriceByListRoomIds($hotel, $rooms, $dates);

        foreach ($rooms as $room) {
            if (isset($dataRoomPrices[$room['id']])) {
                $room->singlePrice = $dataRoomPrices[$room['id']]['singlePrice'];
                $room->revenue = $dataRoomPrices[$room['id']]['revenue'] * $numb_room;
                $room->totalPrice = $dataRoomPrices[$room['id']]['totalPrice'] * $numb_room;
                $room->available_count = $dataRoomPrices[$room['id']]['available_count'];
            }
            $response[] = $room;
        }

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $response,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

//    public function calPrice()
//    {
//        $this->loadModel('Rooms');
//
//        $data = $this->getRequest()->getQuery();
//        $hotel = $this->Hotels->get($data['hotel_id']);
//        $rooms = $this->Rooms->find()->where(['hotel_id' => $data['hotel_id']])->toArray();
//        $numb_room = $data['numRoom'];
////        $numb_adult = $data['numAdult'];
////        $numb_children = $data['numChildren'];
//
//        $calSDate = $data['fromDate'];
//        $calEDate = date('d-m-Y', strtotime($data['toDate'] . "-1 days"));
//        $dates = $this->Util->_dateRange($calSDate, $calEDate);
//        $response = [];
//
//        foreach ($rooms as $room) {
//            $single_price = 0;
//            $available_count = 0;
//            $price = 0;
//            $profit = 0;
//            foreach ($dates as $key => $date) {
//                $resPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $date);
//                $tmpPrice = $resPrice['price'];
//                if ($key == 0) {
//                    $single_price = $tmpPrice;
//                    $available_count = $resPrice['available_count'];
//                }
//                $price += $tmpPrice * $numb_room;
//                $profit += $resPrice['revenue'] * $numb_room;
////                $profit += $this->Util->calculateHotelRevenue($hotel, $room->id, $date) * $numb_room;
//            }
//            $room->singlePrice = $single_price;
//            $room->revenue = $profit;
//            $room->totalPrice = $price;
//            $room->available_count = $available_count;
//            $response[] = $room;
//        }
//
//        $this->set([
//            'status' => STT_SUCCESS,
//            'message' => 'Success',
//            'data' => $response,
//            '_serialize' => ['status', 'message', 'data']
//        ]);
//    }

    public function calPriceBooking()
    {
        $this->loadComponent('Util');
        $this->loadModel('Rooms');
        $this->loadModel('HotelSurcharges');
        $res = ['status' => STT_SUCCESS, 'message' => 'Thành công'];

        $response = ['success' => true, 'data_surcharge_price' => [], 'total_price' => 0, 'total_revenue' => 0];
        $data = $this->getRequest()->getData();
//        $this->Util->writeLogFile($data, HOTEL);
        $hotel = $this->Hotels->get($data['item_id'], ['contain' => ['HotelSurcharges']]);

        $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
        $hotelSurchargeLists = Hash::extract($hotel->hotel_surcharges, '{n}.surcharge_type');
        $hotelAutoSurcharges = array_values(array_intersect($constantAutoSurcharge, $hotelSurchargeLists));

        $total_price = $revenue = 0;
        $bookingStr = 'Khách sạn ' . $hotel->name . '. ';
        $isAllow = true;
        foreach ($data['booking_rooms'] as $key => $booking_room) {
            $room = $this->Rooms->get($booking_room['room_id']);
            $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
            $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
            $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
            if (($booking_room['num_adult'] + $booking_room['num_children']) <= $roomTotalMaxPeople) {
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
                $bookingRoomPrice = 0;
                foreach ($dates as $date) {
                    $resPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $date);
                    if ($resPrice['status']) {
                        $bookingRoomPrice += $resPrice['price'];
                        $revenue += $this->Util->calculateHotelRevenue($hotel, $room->id, $date);
                    } else {
                        $response['success'] = false;
                        $response['errors']['incorrect_info'] = ['message' => $resPrice['message']];
                        break;
                    }
                }
                $total_price += $bookingRoomPrice * $num_room;
                $revenue = $revenue * $num_room;
                $bookingStr .= 'Hạng phòng ' . $room->name . ', checkin ' . $booking_room['start_date'] . ', check out ' . $booking_room['end_date'] . ', ';
                $bookingStr .= $booking_room['num_adult'] . ' người lớn, ' . $booking_room['num_children'] . ' trẻ em.';
            } else {
                $isAllow = false;
                $res['status'] = STT_INVALID;
                $res['message'] = 'Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.';
            }
        }

        $data_surcharges = [];
        if ($isAllow) {

            foreach ($hotelAutoSurcharges as $surcharge_id) {
                $priceCalculated = $this->Util->calHotelSurcharge($hotel, $data['booking_rooms'], $surcharge_id, 0, 0);
                $data_surcharges[$this->Util->getSurchargeId($surcharge_id)] = $priceCalculated;
                $total_price += $priceCalculated;
            }

            if (isset($data['booking_surcharges'])) {
                foreach ($data['booking_surcharges'] as $key => $booking_surcharge) {
                    if (!in_array($booking_surcharge['surcharge_type'], $constantAutoSurcharge)) {
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
                            $total_price += $priceCalculated;
                        }
                    }
                }
            }

            if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
                $total_price = $total_price - $revenue;
                $revenue = 0;
            }
        }

        $response['data_surcharge_price'] = $data_surcharges;
        $response['total_price'] = $total_price;
        $response['total_revenue'] = $revenue;
        $response['booking_str'] = $bookingStr;

        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => $response,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function search()
    {
        $today = date('Y-m-d');
        $this->loadModel('Locations');
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');
        $this->loadModel('HotelSearchs');
        $this->loadModel('HomeStays');
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $keyword = $this->getRequest()->getQuery('keyword');
        $page = $this->getRequest()->getQuery('page');
        if ($keyword) {
            $res['status'] = STT_SUCCESS;
            $location = $this->Locations->find()->where(['name ' => $keyword])->first();

            if ($location) {

                $hotels = $this->HotelSearchs->find()->where(['name LIKE' => '%' . $keyword . '%', 'single_day' => $today])->limit(4)->toArray();
                $hotel_count = $this->Hotels->find()->contain(['Rooms', 'Rooms.PriceRooms'])->where(['location_id' => $location->id])->count();

                $land_tours = $this->LandTours->find()->where(['destination_id' => $location->id])->limit(4)->toArray();

                foreach ($land_tours as $key => $landTour) {
                    $land_tours[$key]->totalPrice = $landTour->price + $landTour->trippal_price + $landTour->customer_price;
                }
                $landtour_count = $this->LandTours->find()->where(['destination_id' => $location->id])->count();

                $homestays = $this->HomeStays->find()->contain(['PriceHomeStays'])->where(['location_id' => $location->id])->limit(4)->toArray();
                foreach ($homestays as $key => $homestay) {
                    $homestays[$key]->totalPrice = $this->Util->countingHomeStayPrice($today, $homestay);
                }
                $homestay_count = $this->HomeStays->find()->contain(['PriceHomeStays'])->where(['location_id' => $location->id])->count();

                $data = [
                    'type' => 1,
                    'location_id' => $location->id,
                    'location_name' => $location->name,
                    'hotel_count' => $hotel_count,
                    'landtour_count' => $landtour_count,
                    'homestay_count' => $homestay_count,
                    'items' => [
                        'hotels' => $hotels,
                        'land_tours' => $land_tours,
                        'homestays' => $homestays
                    ]
                ];
                $res['data'] = $data;
            } else {
                $hotels = $this->HotelSearchs->find()->where(['name LIKE' => '%' . $keyword . '%', 'single_day' => $today])->toArray();
                if ($hotels) {
                    $hotelIds = Hash::extract($hotels, '{n}.id');
                } else {
                    $hotelIds[] = 0;
                }
                $missingHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'Hotels.name LIKE' => '%' . $keyword . '%'])->toArray();
                foreach ($missingHotels as $key => $item) {
                    $item->price_day = 0;
                    if ($item->location) {
                        $item->location_name = $item->location->name;
                    } else {
                        $item->location_name = '';
                    }
                    $hotels[] = $item;
                }
                $hotels = array_slice($hotels, 10 * ($page - 1), 10);
                $homestays = $this->HomeStays->find()->contain(['PriceHomeStays'])->where(['HomeStays.name LIKE' => '%' . $keyword . '%'])->toArray();
                foreach ($homestays as $key => $homestay) {
                    $homestays[$key]->totalPrice = $this->Util->countingHomeStayPrice($today, $homestay);
                }
                $homestays = array_slice($homestays, 10 * ($page - 1), 10);
                $landTours = $this->LandTours->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                foreach ($landTours as $k => $landTour) {
                    $landTours[$k]->totalPrice = $landTour['price'] + $landTour['trippal_price'] + $landTour['customer_price'];
                }
                $landTours = array_slice($landTours, 10 * ($page - 1), 10);
                $data = [
                    'type' => 2,
                    'data' => [
                        'hotels' => $hotels,
                        'homestays' => $homestays,
                        'landtours' => $landTours
                    ]
                ];
                $res['data'] = $data;
            }
        } else {
            $res['message'] = 'Phải nhập thông tin';
        }

        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => $res['data'],
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
    public function view($id = null)
    {
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

    public function searchSuggest()
    {
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');
        $this->loadModel('HomeStays');
        $this->loadModel('Vouchers');
        $this->loadModel('Locations');
        $this->loadModel('Combos');
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');

        $keyword = $this->getRequest()->getQuery('keyword');
        $type = $this->getRequest()->getQuery('type');
        $response = ['success' => false, 'message' => ''];
        $results = [];
        if (strlen($keyword) >= 3) {
            $listHotels = $this->Hotels->find()->select(['id', 'name', 'slug', 'thumbnail', 'address', 'type' => HOTEL])->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
            $listHomeStays = $this->HomeStays->find()->select(['id', 'name', 'slug', 'thumbnail', 'address', 'type' => HOMESTAY])->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
            $listLandTours = $this->LandTours->find()->contain([
                'Destinations' => function ($q) {
                    return $q->autoFields(false)
                        ->select(['address' => 'Destinations.name']);
                }
            ])->select(['id', 'name', 'slug', 'thumbnail', 'type' => LANDTOUR])->where(['LandTours.name LIKE' => '%' . $keyword . '%'])->toArray();

            if (isset($listHotels)) {
                $results = array_merge($results, $listHotels);
            }
            if (isset($listHomeStays)) {
                $results = array_merge($results, $listHomeStays);
            }
            if (isset($listLandTours)) {
                $results = array_merge($results, $listLandTours);
            }
            foreach ($results as $k => $result) {
                $results[$k]['type'] = intval($result['type']);
            }

            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'Thành công',
                'data' => $results,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_INVALID,
                'message' => 'Nhập ít nhất 3 kí tự',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    private function sortByPriceAsc($a, $b)
    {
        return $a->totalPrice > $b->totalPrice;
    }

    private function sortByPriceDsc($a, $b)
    {
        return $a->totalPrice < $b->totalPrice;
    }
}
