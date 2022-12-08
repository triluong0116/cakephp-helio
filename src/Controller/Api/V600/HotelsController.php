<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 4/10/2019
 * Time: 7:42 PM
 */

namespace App\Controller\Api\V600;

use Cake\Log\Log;
use Cake\Utility\Hash;

/**
 * Locations Controller
 *
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HomeStaysTable $HomeStays
 * @property \App\Controller\Component\UtilComponent $Util
 * @property \App\Model\Table\HotelSearchsTable $HotelSearchs
 * @property \App\Model\Table\VinroomsTable $Vinrooms
 * @property \App\Model\Table\LocationsTable $Locations
 * @property \App\Model\Table\FavouritesTable $Favourites
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HotelsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['listVinpearlRoom', 'suggestSearch', 'lists', 'detail', 'calPrice', 'search', 'calPriceBooking', 'searchSuggest', 'commitLocation', 'detailCommitLocation', 'suggestSearchVinpearl', 'listVinpearl', 'detailVinpearl', 'searchForVinPackage']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function lists()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
//            $this->paginate = [
//                'limit' => 10
//            ];
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
                    $condition['price_day_app >= '] = $sliderArray[0];
                    $condition['price_day_app <= '] = $sliderArray[1];
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
                $hotel->singlePrice = $hotel->price_day_app;
                $hotel->price_day = $hotel->price_day_app;
            }

//            $hotels = array_slice($hotels, 10 * ($page - 1), 10);
            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'Success',
                'data' => $hotels,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }

    }

    public function detail($id)
    {
        $status = STT_ERROR;
        $message = "";
        $data = [];
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $this->loadModel('HotelSurcharges');
            $this->loadModel('HotelSearchs');
            $this->loadModel('Favourites');
            $this->loadComponent('Util');
            $clientId = $this->getRequest()->getQuery('clientId');
            $hotel = $this->Hotels->get($id, ['contain' => [
                'Categories' => function ($q) {
                    return $q->select(['icon', 'name']);
                },
                'Rooms']]);
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
            $hotel->contact_text = 'Lưu ý: Đại lý gọi điện cho khách sạn xin gặp bộ phận sale đặt phòng, giới thiệu mình gọi từ Mustgo.vn check tình trạng phòng trống (hỏi tên sale làm việc để đối chiếu thông tin về sau).';
//            if ($hotel->favourites) {
//                $hotel->is_favourite = true;
//            } else {
//                $hotel->is_favourite = false;
//            }
//            unset($hotel->favourites);
            $hotel->hotline = explode('/', $hotel->hotline);
            $fav = $this->Favourites->find()->where(['object_type' => HOTEL, 'object_id' => $hotel->id, 'clientId' => $clientId])->first();
            if ($fav) {
                $hotel->is_favourite = true;
            } else {
                $hotel->is_favourite = false;
            }
            $tmpHotelSearch = $this->HotelSearchs->find()->where([
                'id' => $id,
                'single_day' => date('Y-m-d'),
                'is_vinhms' => 0
            ])->first();
            $hotel->min_price = $tmpHotelSearch && $tmpHotelSearch->price_day != 0 ? number_format($tmpHotelSearch->price_day) : 'Đang cập nhật';
            $hotel['term'] = "";
            if ($hotel['term'] == "") {
                $hotel['term'] = [];
            }
            $data = $hotel;
            $status = STT_SUCCESS;
            $message = "Success";
        } else {
            $status = STT_NOT_LOGIN;
            $message = "Not Log In";
        }

        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
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
                $room->singlePrice = $dataRoomPrices[$room['id']]['singlePrice'] - $dataRoomPrices[$room['id']]['singleRevenue'];
//                $room->revenue = $dataRoomPrices[$room['id']]['revenue'] * $numb_room;
                $room->revenue = 0;
                $room->totalPrice = $dataRoomPrices[$room['id']]['totalPrice'] * $numb_room - $dataRoomPrices[$room['id']]['revenue'] * $numb_room;
                $room->available_count = $dataRoomPrices[$room['id']]['available_count'];
            }
            $room['media'] = json_decode($room['media'], true);
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

//            if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
//                $total_price = $total_price - $revenue;
//                $revenue = 0;
//            }
        }

        $response['data_surcharge_price'] = $data_surcharges;
        $response['total_price'] = $total_price - $revenue;
        $response['total_revenue'] = 0;
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
        $this->loadModel('Vinrooms');
        $param = $this->request->getQuery();
        $keyword = $param['keyword'];
        $page = $param['page'];
        $status = STT_ERROR;
        $message = "";
        $data = [];

        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            if ($keyword) {
                if (!isset($param['fromDate']) || !$param['fromDate']) {
                    $param['fromDate'] = date('Y-m-d');
                }
                if (!isset($param['endDate']) || !$param['endDate']) {
                    $param['endDate'] = date('Y-m-d', strtotime(' +1 day'));
                }
                $paramRoom = $param['room'];
                $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $param['fromDate'])));
                $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $param['endDate'])));
                $dateDiff = date_diff(date_create($startDate), date_create($endDate));
                $testUrl = 'https://premium-api.product.cloudhms.io';
                $hotels = $this->Hotels->find()->contain(['Locations'])->where(['Hotels.name LIKE' => "%" . $keyword . "%"])->offset(10 * ($page - 1))->limit(10);
                $normalHotelIds = [];
                $vinHotelIds = [];
                foreach ($hotels as $hotel) {
                    if ($hotel->is_vinhms == 1) {
                        $vinHotelIds[] = $hotel->vinhms_code;
                        $data[$hotel->vinhms_code] = [
                            'id' => $hotel->id,
                            'is_vinpearl' => true,
                            'is_landtour' => false,
                            'name' => $hotel->name,
                            'rating' => 5,
                            'location' => $hotel->location->name,
                            'singlePrice' => 'Đang cập nhật',
                            'thumbnail' => $hotel->thumbnail,
                            'extends' => $hotel->extends ? json_decode($hotel->extends, true) : [],
                            'show_price' => 0,
                            'price_agency_type' => $hotel->price_agency_type,
                            'price_customer_type' => $hotel->price_customer_type,
                            'price_agency' => $hotel->price_agency,
                            'price_customer' => $hotel->price_customer,
                        ];
                    } else {
                        $normalHotelIds[] = $hotel->id;
                        $data[$hotel->id] = [
                            'id' => $hotel->id,
                            'is_vinpearl' => false,
                            'is_landtour' => false,
                            'name' => $hotel->name,
                            'rating' => $hotel->rating,
                            'location' => $hotel->location->name,
                            'singlePrice' => 'Đang cập nhật',
                            'thumbnail' => $hotel->thumbnail,
                        ];
                    }
                }
                if (count($normalHotelIds) > 0) {
                    $hotelSearchs = $this->HotelSearchs->find()->where([
                        'id IN' => $normalHotelIds,
                        'single_day' => date('Y-m-d', strtotime($startDate))
                    ])->toArray();
                    foreach ($hotelSearchs as $singleHotel) {
                        $data[$singleHotel->id]['singlePrice'] = $singleHotel->price_day;
                    }
                }

                $getData = [
                    "arrivalDate" => $startDate,
                    "departureDate" => $endDate,
                    "numberOfRoom" => 1,
                    "propertyIds" => $vinHotelIds,
                    "roomOccupancy" => []
                ];
                $roomOccupancy = [
                    'numberOfAdult' => $paramRoom[0]['num_adult'],
                    'otherOccupancies' => [
                        [
                            'otherOccupancyRefCode' => 'child',
                            'quantity' => $paramRoom[0]['num_child']
                        ],
                        [
                            'otherOccupancyRefCode' => 'infant',
                            'quantity' => $paramRoom[0]['num_kid']
                        ]
                    ]
                ];
                $getData['roomOccupancy'] = $roomOccupancy;
                $hotelAvailableAPI = $this->Util->SearchHotelHmsAvailability($testUrl, $getData);
                if (isset($hotelAvailableAPI['isSuccess'])) {
                    if (!empty($hotelAvailableAPI['data']['rates'])) {
                        foreach ($hotelAvailableAPI['data']['rates'] as $singleHotel) {
                            $data[$singleHotel['property']['id']]['show_price'] = $singleHotel['rates'][0]['totalAmount']['amount']['amount'];
                            $data[$singleHotel['property']['id']]['show_price'] = intval($data[$singleHotel['property']['id']]['show_price']);
                            $vinroom = $this->Vinrooms->find()->where(['hotel_id' => $data[$singleHotel['property']['id']]['id'], 'vin_code' => $singleHotel['rates'][0]['roomTypeID']])->first();
                            if ($data[$singleHotel['property']['id']]['show_price'] != 0) {
                                if (!$data[$singleHotel['property']['id']]['price_agency_type'] == 0) {
                                    $data[$singleHotel['property']['id']]['price_agency'] = $data[$singleHotel['property']['id']]['show_price'] * $data[$singleHotel['property']['id']]['price_agency'] / 100;
                                } else {
                                    $data[$singleHotel['property']['id']]['price_agency'] = $data[$singleHotel['property']['id']]['price_agency'] * $dateDiff->days;
                                }
                                if (!$data[$singleHotel['property']['id']]['price_customer_type'] == 0) {
                                    $data[$singleHotel['property']['id']]['price_customer'] = $data[$singleHotel['property']['id']]['show_price'] * $data[$singleHotel['property']['id']]['price_customer'] / 100;
                                } else {
                                    $data[$singleHotel['property']['id']]['price_customer'] = $data[$singleHotel['property']['id']]['price_customer'] * $dateDiff->days;
                                }
                                if ($vinroom) {
                                    if ($vinroom->trippal_price != 0) {
                                        if ($vinroom->trippal_price_type == 0) {
                                            $data[$singleHotel['property']['id']]['price_agency'] = $vinroom->trippal_price * $dateDiff->days;
                                        } else {
                                            $data[$singleHotel['property']['id']]['price_agency'] = $data[$singleHotel['property']['id']]['show_price'] * $vinroom->trippal_price / 100;
                                        }
                                    }
                                    if ($vinroom->customer_price != 0) {
                                        if ($vinroom->customer_price_type == 0) {
                                            $data[$singleHotel['property']['id']]['price_customer'] = $vinroom->customer_price * $dateDiff->days;
                                        } else {
                                            $data[$singleHotel['property']['id']]['price_customer'] = $data[$singleHotel['property']['id']]['show_price'] * $vinroom->customer_price / 100;
                                        }
                                    }
                                }
                                $data[$singleHotel['property']['id']]['singlePrice'] = $data[$singleHotel['property']['id']]['show_price'] + $data[$singleHotel['property']['id']]['price_agency'];
                            }
                        }
                    }
                }
                $status = STT_SUCCESS;
                $message = "SUCCESS";
                $data = array_values($data);
                foreach ($data as $k => $singleHotel) {
                    unset($data[$k]['show_price']);
                    unset($data[$k]['price_agency_type']);
                    unset($data[$k]['price_customer_type']);
                    unset($data[$k]['price_agency']);
                    unset($data[$k]['price_customer']);
                }

            } else {
                $status = STT_INVALID;
                $message = "Phải nhập từ khóa tìm kiếm";
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = "Not Log In";
        }

        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
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

    public function commitLocation()
    {
        $this->loadModel('Hotels');
        $this->loadModel('Locations');
        $check = $this->Api->checkLoginApi();

        if ($check['status']) {
            $listHotels = $this->Hotels->find()->where(['is_commit' => 1])->orderAsc('location_id')->toArray();
            $listLocation = [];
            foreach ($listHotels as $key => $hotel) {
                $listLocation[] = $hotel->location_id;
            }
            $listLocation = array_unique($listLocation);
            $locations = [];
            foreach ($listLocation as $key => $location) {
                $location = intval($location);
                $locations[] = $this->Locations->find()->where(['id' => $location])->first();
            }
            $arr = [];
            foreach ($locations as $location) {
                $data = [];
                $data['id'] = $location['id'];
                $data['name'] = $location['name'];
                $arr[] = $data;
            }

            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'Thành công',
                'data' => $arr,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => $check['message'],
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }

    }

    public function detailCommitLocation($id)
    {
        $this->loadModel('Hotels');
        $this->loadModel('Locations');
        $this->loadModel('Vinrooms');
        $this->loadModel('HotelSearchs');

        $check = $this->Api->checkLoginApi();

        if ($check['status']) {
            $testUrl = $this->viewVars['testUrl'];
            $location_id = $id;
            $rating = $this->request->getQuery('rating');
            $price = $this->request->getQuery('price');
            $keywork = $this->request->getQuery('keyword');
            $condition = [];
            $condition['is_commit'] = "1";
            if (!empty($location_id)) {
                $condition['location_id'] = $location_id;
            }
            if (!empty($rating)) {
                $condition['rating'] = $rating;
            }
            if (isset($keywork)) {
                $condition['Hotels.name Like'] = '%' . $keywork . '%';
            }
            $hotels = $this->Hotels->find()->where($condition)->toArray();
            $today = date('Y-m-d');
            $conditionPrice['single_day'] = $today;
            $listCommit = [];
            foreach ($hotels as $hotel) {
                if ($hotel->is_vinhms == 1) {
                    $data = [
                        "arrivalDate" => date('Y-m-d', strtotime('tomorrow')),
                        "departureDate" => date('Y-m-d', strtotime('tomorrow + 1 day')),
                        "numberOfRoom" => 1,
                        "propertyIds" => [$hotel->vinhms_code],
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
                            $hotel->price_day = $hotelAvailableAPI['data']['rates'][0]['rates'][0]['totalAmount']['amount']['amount'];
                            $vinroom = $this->Vinrooms->find()->where(['hotel_id' => $hotel->id, 'vin_code' => $hotelAvailableAPI['data']['rates'][0]['rates'][0]['roomTypeID']])->first();
                        } else {
                            $vinroom = null;
                        }
                    } else {
                        $hotel->price_day = 0;
                        $vinroom = null;
                    }
                    if ($vinroom) {
                        if ($hotel->price_day != 0) {
                            if ($this->Auth->user('role_id') == 3) {
                                $hotel->price_day += ($vinroom->trippal_price != 0 ? $vinroom->trippal_price : $hotel->price_agency);
                            } else {
                                $hotel->price_day += ($vinroom->trippal_price != 0 ? $vinroom->trippal_price : $hotel->price_agency) + ($vinroom->customer_price != 0 ? $vinroom->customer_price : $hotel->price_customer);
                            }
                        }
                    } else {
                        if ($hotel->price_day != 0) {
                            if ($this->Auth->user('role_id') == 3) {
                                $hotel->price_day += $hotel->price_agency;
                            } else {
                                $hotel->price_day += $hotel->price_agency + $hotel->price_customer;
                            }
                        }
                    }
                    if ($hotel->location) {
                        $hotel->location_name = $hotel->location->name;
                    } else {
                        $hotel->location_name = '';
                    }
                    $hotel->singlePrice = intval($hotel->price_day);
                    if ($price) {

                        $listPrice[] = $price;
                        $sliderArray = explode('-', $price);
                        $outputSlider = implode(',', $sliderArray);
                        if ($sliderArray[0] <= $hotel->singlePrice && $sliderArray[1] >= $hotel->singlePrice) {
                            $hotel['singlePrice'] = number_format($hotel->singlePrice);
                            $listCommit[] = $hotel;
                        }
                    } else {
                        $listCommit[] = $hotel;
                    }
                } else {
                    $conditionPrice['id'] = $hotel->id;
                    $priceDay = $this->HotelSearchs->find()->where($conditionPrice)->first();
                    if ($priceDay) {
                        $hotel->singlePrice = $priceDay->price_day_app;
                    }
                    if ($price) {
                        $listPrice[] = $price;
                        $sliderArray = explode('-', $price);
                        $outputSlider = implode(',', $sliderArray);
                        if ($sliderArray[0] <= $hotel->singlePrice && $sliderArray[1] >= $hotel->singlePrice) {
                            $hotel['singlePrice'] = number_format($hotel->singlePrice);
                            $listCommit[] = $hotel;
                        }
                    } else {
                        $listCommit[] = $hotel;
                    }


                }
            }

            $list = [];
            foreach ($listCommit as $commit) {
                $item = [];
                $item['id'] = $commit['id'];
                $item['name'] = $commit['name'];
                $item['thumbnail'] = $commit['thumbnail'];
                $item['rating'] = $commit['rating'];
                $item['singlePrice'] = $commit['singlePrice'];
                $item['location_id'] = $commit['location_id'];
                $item['is_commit'] = $commit['is_commit'];
                $list[] = $item;
            }
            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'Thành công',
                'data' => $list,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => $check['message'],
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }


    }

    public function suggestSearchVinpearl()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $this->loadModel('Hotels');
            $this->loadModel('Locations');
            $keyword = $this->getRequest()->getQuery('keyword');
            if (strlen($keyword) >= 3) {
                $localtions = $this->Locations->find()->select(['id', 'name'])->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                $listHotels = $this->Hotels->find()->select(['id', 'name'])->where(['name LIKE' => '%' . $keyword . '%'])->where(['is_vinpearl' => '1'])->toArray();
                $results = [];
                if (isset($listHotels)) {
                    $results['hotels'] = $listHotels;
                }
                if (isset($localtions)) {
                    $results['localtions'] = $localtions;
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
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function listVinpearl()
    {
        Log::write('debug', 'start function:' . date('H:i:s') . ' timestamp: ' . strtotime(date('H:i:s')));
        $check = $this->Api->checkLoginApi();
        $totalPage = 0;
        if ($check['status']) {
            $this->loadModel('Locations');
            $this->loadModel('Vinrooms');
            // build condition & sort

            $filterLocation = $this->request->getQuery('location_id');
            $page = $this->getRequest()->getQuery('page') ? $this->getRequest()->getQuery('page') : 1;


            $condition = [];
            $data = $this->request->getQuery();
            $dataVinRoom = $data['vin_room'];
            if (isset($data['keyword'])) {
                $condition['Hotels.name Like'] = '%' . $data['keyword'] . '%';
            }

            if ($filterLocation) {
                $condition['location_id'] = $filterLocation;
            }
            $countAllVin = $this->Hotels->find()->where([
                'is_vinhms' => 1,
                'vinhms_code !=' => '',
                $condition
            ])->count();
            $totalPage = intval($countAllVin / 10) + ($countAllVin % 10 == 0 ? 0 : 1);
            $listVinperlHotels = $this->Hotels->find()->contain('Locations')->where([
                'is_vinhms' => 1,
                'vinhms_code !=' => '',
                $condition
            ])->offset(10 * ($page - 1))->limit(10);
//        $roomData = explode('-', $data['num_people']);
//        $numRoom = str_replace(' Phòng', '', $roomData[0]);
//        $numAdult = str_replace('NL', '', $roomData[1]);
//        $numChild = str_replace('TE', '', $roomData[2]);
//        $numKid = str_replace('EB', '', $roomData[3]);
            if (!isset($data['fromDate']) || !$data['fromDate']) {
                $data['fromDate'] = date('Y-m-d');
            }
            if (!isset($data['endDate']) || !$data['endDate']) {
                $data['endDate'] = date('Y-m-d', strtotime(' +1 day'));
            }
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $data['fromDate'])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $data['endDate'])));
            $dateDiff = date_diff(date_create($startDate), date_create($endDate));
            $testUrl = 'https://premium-api.product.cloudhms.io';
            $vinIds = [];
            $listVinInfor = [];
            foreach ($listVinperlHotels as $hotel) {
                $vinIds[] = $hotel->vinhms_code;
                $listVinInfor[$hotel->vinhms_code] = [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'rating' => 5,
                    'location' => $hotel->location->name,
                    'singlePrice' => 0,
                    'thumbnail' => $hotel->thumbnail,
                    'extends' => $hotel->extends ? json_decode($hotel->extends, true) : [],
                    'show_price' => 0,
                    'price_agency_type' => $hotel->price_agency_type,
                    'price_customer_type' => $hotel->price_customer_type,
                    'price_agency' => $hotel->price_agency,
                    'price_customer' => $hotel->price_customer,
                ];
            }
//                $detailHotelAPI = $this->Util->getDetailHotel($testUrl, $hotel->vinhms_code);
            $data = [
                "arrivalDate" => $startDate,
                "departureDate" => $endDate,
                "numberOfRoom" => 1,
                "propertyIds" => $vinIds,
                "roomOccupancy" => []
            ];
            $roomOccupancy = [
                'numberOfAdult' => $dataVinRoom[0]['num_adult'],
                'otherOccupancies' => [
                    [
                        'otherOccupancyRefCode' => 'child',
                        'quantity' => $dataVinRoom[0]['num_child']
                    ],
                    [
                        'otherOccupancyRefCode' => 'infant',
                        'quantity' => $dataVinRoom[0]['num_kid']
                    ]
                ]
            ];
            $data['roomOccupancy'] = $roomOccupancy;
            Log::write('debug', 'start call vin api: ' . date('H:i:s') . ' timestamp: ' . strtotime(date('H:i:s')));
            $hotelAvailableAPI = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
            Log::write('debug', 'end call vin api: ' . date('H:i:s') . ' timestamp: ' . strtotime(date('H:i:s')));
            if (!isset($hotelAvailableAPI['isSuccess'])) {
                $dataSendVin = $data;
                $hotelAvailableAPIs = [];
                foreach ($data['propertyIds'] as $val) {
                    $dataSendVin['propertyIds'] = [];
                    $dataSendVin['propertyIds'][] = $val;
                    $hotelAvailableAPILimit5 = $this->Util->SearchHotelHmsAvailability($testUrl, $dataSendVin);
                    if (isset($hotelAvailableAPILimit5['isSuccess'])) {
                        if (!empty($hotelAvailableAPILimit5['data']['rates'])) {
                            foreach ($hotelAvailableAPILimit5['data']['rates'] as $value) {
                                $hotelAvailableAPIs[] = $value;
                            }
                        }
                    }
                }
            } else {
                $hotelAvailableAPIs = $hotelAvailableAPI['data']['rates'];
            }

            if (!empty($hotelAvailableAPIs)) {
                foreach ($hotelAvailableAPIs as $singleHotel) {
                    $listVinInfor[$singleHotel['property']['id']]['show_price'] = $singleHotel['rates'][0]['totalAmount']['amount']['amount'];
                    $listVinInfor[$singleHotel['property']['id']]['show_price'] = intval($listVinInfor[$singleHotel['property']['id']]['show_price']);
                    $vinroom = $this->Vinrooms->find()->where(['hotel_id' => $listVinInfor[$singleHotel['property']['id']]['id'], 'vin_code' => $singleHotel['rates'][0]['roomTypeID']])->first();
                    if ($listVinInfor[$singleHotel['property']['id']]['show_price'] != 0) {
                        if (!$listVinInfor[$singleHotel['property']['id']]['price_agency_type'] == 0) {
                            $listVinInfor[$singleHotel['property']['id']]['price_agency'] = $listVinInfor[$singleHotel['property']['id']]['show_price'] * $listVinInfor[$singleHotel['property']['id']]['price_agency'] / 100;
                        } else {
                            $listVinInfor[$singleHotel['property']['id']]['price_agency'] = $listVinInfor[$singleHotel['property']['id']]['price_agency'] * $dateDiff->days;
                        }
                        if (!$listVinInfor[$singleHotel['property']['id']]['price_customer_type'] == 0) {
                            $listVinInfor[$singleHotel['property']['id']]['price_customer'] = $listVinInfor[$singleHotel['property']['id']]['show_price'] * $listVinInfor[$singleHotel['property']['id']]['price_customer'] / 100;
                        } else {
                            $listVinInfor[$singleHotel['property']['id']]['price_customer'] = $listVinInfor[$singleHotel['property']['id']]['price_customer'] * $dateDiff->days;
                        }
                        if ($vinroom) {
                            if ($vinroom->trippal_price != 0) {
                                if ($vinroom->trippal_price_type == 0) {
                                    $listVinInfor[$singleHotel['property']['id']]['price_agency'] = $vinroom->trippal_price * $dateDiff->days;
                                } else {
                                    $listVinInfor[$singleHotel['property']['id']]['price_agency'] = $listVinInfor[$singleHotel['property']['id']]['show_price'] * $vinroom->trippal_price / 100;
                                }
                            }
                            if ($vinroom->customer_price != 0) {
                                if ($vinroom->customer_price_type == 0) {
                                    $listVinInfor[$singleHotel['property']['id']]['price_customer'] = $vinroom->customer_price * $dateDiff->days;
                                } else {
                                    $listVinInfor[$singleHotel['property']['id']]['price_customer'] = $listVinInfor[$singleHotel['property']['id']]['show_price'] * $vinroom->customer_price / 100;
                                }
                            }
                        }
                        $listVinInfor[$singleHotel['property']['id']]['singlePrice'] = $listVinInfor[$singleHotel['property']['id']]['show_price'] + $listVinInfor[$singleHotel['property']['id']]['price_agency'] + $listVinInfor[$singleHotel['property']['id']]['price_customer'];
                    }
                }
            }

            $listVinInfor = array_values($listVinInfor);
            foreach ($listVinInfor as $k => $singleHotel) {
                if ($singleHotel['singlePrice'] == 0) {
                    $listVinInfor[$k]['singlePrice'] = 'Đang cập nhật';
                } else {
                    $listVinInfor[$k]['singlePrice'] = number_format($singleHotel['singlePrice']);
                    $listVinInfor[$k]['number_night'] = $dateDiff->days;
                }
                unset($listVinInfor[$k]['show_price']);
                unset($listVinInfor[$k]['price_agency_type']);
                unset($listVinInfor[$k]['price_customer_type']);
                unset($listVinInfor[$k]['price_agency']);
                unset($listVinInfor[$k]['price_customer']);
            }
            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'success',
                'data' => [
                    'total_page' => $totalPage,
                    'page' => $page,
                    'data' => $listVinInfor
                ],
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
        Log::write('debug', 'end function:' . date('H:i:s') . ' timestamp: ' . strtotime(date('H:i:s')));
    }

    public function detailVinpearl($id)
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $vin = [];
            $this->loadModel('Favourites');
            $this->loadModel('Vinrooms');
            $this->loadModel('Vinhmsallotments');
            $this->loadComponent('Util');
            $clientId = $this->getRequest()->getQuery('clientId');
            $hotel = $this->Hotels->get($id, ['contain' => [
                'Categories' => function ($q) {
                    return $q->select(['icon', 'name']);
                },
                'Rooms']]);
            $hotel->isSpecial = $hotel->is_special;
            $listCaption = json_decode($hotel->caption, true);
            foreach ($listCaption as $key => $caption) {
                $listCaption[$key]['content'] = strip_tags(html_entity_decode($caption['content']));
            }

            $data = $this->request->getQuery();
            $dataVinRoom = $data['vin_room'];

            if (!isset($data['fromDate']) || !$data['fromDate']) {
                $data['fromDate'] = date('Y-m-d');
            }
            if (!isset($data['endDate']) || !$data['endDate']) {
                $data['endDate'] = date('Y-m-d', strtotime(' +1 day'));
            }
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
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $data['fromDate'])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $data['endDate'])));
            $dateDiff = date_diff(date_create($startDate), date_create($endDate));
            $testUrl = 'https://premium-api.product.cloudhms.io';

            $data = [
                "arrivalDate" => $startDate,
                "departureDate" => $endDate,
                "numberOfRoom" => 1,
                "propertyIds" => [$hotel->vinhms_code],
                "roomOccupancy" => []
            ];
            $roomOccupancy = [
                'numberOfAdult' => $dataVinRoom[0]['num_adult'],
                'otherOccupancies' => [
                    [
                        'otherOccupancyRefCode' => 'child',
                        'quantity' => $dataVinRoom[0]['num_child']
                    ],
                    [
                        'otherOccupancyRefCode' => 'infant',
                        'quantity' => $dataVinRoom[0]['num_kid']
                    ]
                ]
            ];
            $data['roomOccupancy'] = $roomOccupancy;
            $hotelAvailableAPI = $this->Util->SearchHotelHmsAvailability($testUrl, $data);

            $showPrice = 0;
            $priceAgency = 0;
            $priceCustomer = 0;
            if (isset($hotelAvailableAPI['isSuccess'])) {
                if (!empty($hotelAvailableAPI['data']['rates'])) {
                    foreach ($hotelAvailableAPI['data']['rates'] as $singleHotel) {
                        $ratePackage = $singleHotel['rates'][0];
                        $singleRoom = $singleHotel['property'];
                        $hasSpecialPackage = false;
                        if (isset($ratePackage['rateAvailablity']['allotments'][0]) && isset($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']])) {
                            $hasSpecialPackage = true;
                        }
                        $showPrice = intval($singleHotel['rates'][0]['totalAmount']['amount']['amount']);
                        $vinroom = $this->Vinrooms->find()->where(['hotel_id' => $hotel->vinhms_code, 'vin_code' => $singleHotel['rates'][0]['roomTypeID']])->first();
                        if ($showPrice != 0) {
                            if (!$hotel->price_agency_type == 0) {
                                $priceAgency = $showPrice * $hotel->price_agency / 100;
                            } else {
                                $priceAgency = $hotel->price_agency * $dateDiff->days;
                            }
                            if (!$hotel->price_customer_type == 0) {
                                $priceCustomer = $showPrice * $hotel->price_customer / 100;
                            } else {
                                $priceCustomer = $hotel->price_customer * $dateDiff->days;
                            }
                            if ($vinroom) {
                                if ($vinroom->trippal_price != 0) {
                                    if ($vinroom->trippal_price_type == 0) {
                                        $priceAgency = $vinroom->trippal_price * $dateDiff->days;
                                    } else {
                                        $priceAgency = $showPrice * $vinroom->trippal_price / 100;
                                    }
                                }
                                if ($vinroom->customer_price != 0) {
                                    if ($vinroom->customer_price_type == 0) {
                                        $priceCustomer = $vinroom->customer_price * $dateDiff->days;
                                    } else {
                                        $priceCustomer = $showPrice * $vinroom->customer_price / 100;
                                    }
                                }
                            }
                            if ($hasSpecialPackage) {
                                if (isset($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['sale_revenue']) && $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['sale_revenue'] != 0) {
                                    if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['sale_revenue_type'] == 0) {
                                        $priceAgency = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['sale_revenue'] * $dateDiff->days;
                                    } else {
                                        $priceAgency = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['sale_revenue'] / 100);
                                    }
                                }
                                if (isset($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['sale_revenue']) && $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['revenue'] != 0) {
                                    if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['revenue_type'] == 0) {
                                        $priceCustomer = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['revenue'] * $dateDiff->days;
                                    } else {
                                        $priceCustomer = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$singleHotel['rates'][0]['roomTypeID']]['revenue'] / 100);
                                    }
                                }
                            }
                            $showPrice = $showPrice + $priceAgency + $priceCustomer;
                            Log::write('debug','DetailVin--- Key: '.$singleHotel['rates'][0]['roomTypeID'].'price: '.$showPrice.'revenue: '.$priceCustomer.'sale_revenue: '.$priceAgency);
                        }
                    }
                }
            }

            $hotel->caption = $listCaption;
            $media = json_decode($hotel->media, true);
            $hotel->vinhms_meeting = $hotel->vinhms_meeting ? json_decode($hotel->vinhms_meeting, true) : [];
            $hotel->term = json_decode($hotel->term, true);
            $hotel->extends = json_decode($hotel->extends);
            $hotel['email'] = json_decode($hotel['email'], true);
            $hotel['payment_information'] = json_decode($hotel['payment_information'], true);
            $vin['id'] = $hotel['id'];
            $vin['name'] = $hotel['name'];
            $vin['icon_list'] = $hotel['icon_list'];
            $vin['description'] = $hotel['description'];
            $vin['caption'] = $hotel['caption'];
            $vin['vinhms_caption'] = json_decode($hotel['vinhms_caption'], true);
            $vin['location_id'] = $hotel['location_id'];
            $vin['contract_file'] = $hotel['contract_file'];
            $vin['promotion'] = $hotel['promotion'];
            $vin['term'] = $hotel['term'];
            $vin['thumbnail'] = $hotel['thumbnail'];
            $vin['banner'] = $hotel['banner'];
            $vin['extends'] = $hotel['extends'];
            $vin['medias'] = $media;
            $vin['fb_content'] = $hotel['fb_content'];
            $vin['rating'] = $hotel['rating'];
            $vin['address'] = $hotel['address'];
            $vin['checkin_time'] = date_format($hotel['checkin_time'], 'H:i:s');
            $vin['checkout_time'] = date_format($hotel['checkout_time'], 'H:i:s');
            $vin['map'] = $hotel['map'];
            $vin['lat'] = $hotel['lat'];
            $vin['lon'] = $hotel['lon'];
            $vin['hotline'] = $hotel['hotline'];
            $vin['email'] = $hotel['email'];
            $vin['min_price'] = $showPrice != 0 ? number_format($showPrice) : 'Đang cập nhật';
            $vin['number_night'] = $dateDiff->days;
            $fav = $this->Favourites->find()->where(['object_type' => VINPEARL, 'object_id' => $vin['id'], 'user_id' => $check['user_id']])->first();
            if ($fav) {
                $vin['is_favourite'] = true;
            } else {
                $vin['is_favourite'] = false;
            }
            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'Success',
                'data' => $vin,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function listVinpearlRoom($id)
    {
        $check = $this->Api->checkLoginApi();
        $finalData = [];
        if ($check['status']) {
            $this->loadModel('Hotels');
            $this->loadModel('Vinhmsallotments');
            $this->loadModel('Vinrooms');
            $hotel = $this->Hotels->get($id);
            if ($hotel) {
                if ($hotel->vinhms_code) {
                    $testUrl = $this->viewVars['testUrl'];
                    $dataGet = $this->request->getQuery();
                    $dataRoom = $dataGet['vin_room'];
                    $startDate = date('Y-m-d', strtotime($dataGet['fromDate']));
                    $endDate = date('Y-m-d', strtotime($dataGet['toDate']));

                    $singleVinChooseRoom = [];
                    $dateDiff = date_diff(date_create($startDate), date_create($endDate));
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
                        if (!empty($data['roomOccupancy'])) {

                        }
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
                                        'information' => [
                                            'image' => $image,
                                            'name' => $singleRoom['name'],
                                            'description' => $singleRoom['description'],
                                            'maxAdult' => $singleRoom['maxAdult'],
                                            'maxChild' => $singleRoom['maxChild'],
                                            'squareUnit' => $singleRoom['squareUnit'],
                                            'squareUnitType' => isset($singleRoom['squareUnitType']) ? $singleRoom['squareUnitType'] : "",
                                            'min_price' => 999999999,
                                            'number_night' => $dateDiff->days,
                                            'extends' => $dataVinroom && $dataVinroom->extends ? json_decode($dataVinroom->extends, true) : []
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
                                    $ratePackage['amount_left'] = $ratePackage['rateAvailablity']['allotments'][0]['quantity'];
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
                                        $listRoom[$ratePackage['roomTypeID']]['information']['min_price'] > $tmpPrice ? $listRoom[$ratePackage['roomTypeID']]['information']['min_price'] = $tmpPrice : true;
                                        $listRoom[$ratePackage['roomTypeID']]['package'][] = $ratePackage;
                                    }
                                }
                            }
                        }
                        uasort($listRoom, function ($item1, $item2) {
                            return $item1['information']['min_price'] <=> $item2['information']['min_price'];
                        });
                        $singleVinChooseRoom[] = $listRoom;
                    }
                    $dataResponse = [];

                    foreach ($singleVinChooseRoom as $i => $listRoom) {
                        foreach ($listRoom as $k => $room) {
                            $dataResponse[$i][$k]['information'] = $room['information'];
                            $dataResponse[$i][$k]['information']['room_status'] = "Hết phòng";
                            if (isset($room['package'])) {
                                foreach ($room['package'] as $packageKey => $package) {
                                    $dataResponse[$i][$k]['information']['room_status'] = "Sắp hết phòng";
                                    $arrText = explode('-', $package['rateAvailablity']['ratePlan']['name']);
                                    $packageName = '';
                                    foreach ($arrText as $kText => $text) {
                                        $text = trim($text);
                                        $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                        $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                                    }
                                    Log::write('debug','Key: '.$packageKey.'sale_revenue: '.$package['trippal_price'].'revenue: '.$package['customer_price'].'amount: '. $package['totalAmount']['amount']['amount']);
                                    $dataResponse[$i][$k]['package'][] = [
                                        'rate_plan_code' => $package['rateAvailablity']['ratePlan']['rateCode'],
                                        'room_type_code' => $package['rateAvailablity']['roomTypeCode'],
                                        'allotment_id' => $package['rateAvailablity']['allotments'][0]['allotmentId'],
                                        'package_name' => $package['rateAvailablity']['ratePlan']['name'],
                                        'package_description' => explode("\n", $package['rateAvailablity']['ratePlan']['description']),
                                        'package_name_show' => $packageName,
                                        'package_code' => $package['rateAvailablity']['ratePlanCode'],
                                        'revenue' => $package['customer_price'],
                                        'sale_revenue' => $package['trippal_price'],
                                        'package_id' => $package['rateAvailablity']['propertyId'],
                                        'rateplan_id' => $package['ratePlanID'],
                                        'room_index' => $i,
                                        'room_key' => $k,
                                        'package_price' => $package['totalAmount']['amount']['amount'] + ($package['trippal_price'] + $package['customer_price']),
                                        'package_default_price' => $package['totalAmount']['amount']['amount'],
                                        'package_left' => $package['amount_left'],
                                        'number_night' => $dateDiff->days,
                                    ];
                                }
                            }
                        }
                    }

                    $finalData['list_vin_room'] = array_values($dataResponse);
                    $status = STT_SUCCESS;
                    $message = 'Success';
                } else {
                    $status = STT_INVALID;
                    $message = 'Invalid Vinpearl Hotel';
                }
            } else {
                $status = STT_NOT_FOUND;
                $message = 'Hotel not found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not login';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $finalData,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function searchForVinPackage()
    {
        $this->loadModel('Users');
        $check = $this->Api->checkLoginApi();
        $testUrl = $this->viewVars['testUrl'];
        $finalData = [];
        if ($check['status']) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            if ($user) {
                $this->loadModel('Vinrooms');
                $this->loadModel('Vinhmsallotments');
                $dataGet = $this->request->getQuery();
                Log::write('debug', 'data:"' . json_encode($dataGet, JSON_UNESCAPED_SLASHES) . '"');
                $hotel = $this->Hotels->get($dataGet['hotel_id']);
                $listAllotments = $this->Vinhmsallotments->find()
                    ->where([
                        'hotel_id' => $hotel->id,
                    ]);
                $allotmentRoom = [];
                foreach ($listAllotments as $k => $singleRoom) {
                    if ($singleRoom->vinroom_code == $dataGet['room_key']) {
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
                $startDate = date('Y-m-d', strtotime($dataGet['fromDate']));
                $endDate = date('Y-m-d', strtotime($dataGet['toDate']));


                $data = [
                    "arrivalDate" => $startDate,
                    "departureDate" => $endDate,
                    "numberOfRoom" => 1,
                    "propertyIds" => [$hotel->vinhms_code],
                    "roomOccupancy" => []
                ];
                empty($data['roomOccupancy']);
                $roomOccupancy = [
                    'numberOfAdult' => $dataGet['num_adult'],
                    'otherOccupancies' => [
                        [
                            'otherOccupancyRefCode' => 'child',
                            'quantity' => $dataGet['num_child']
                        ],
                        [
                            'otherOccupancyRefCode' => 'infant',
                            'quantity' => $dataGet['num_kid']
                        ]
                    ]
                ];
                $data['roomOccupancy'] = $roomOccupancy;
                $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
                $listRoom = [];
                $dateDiff = date_diff(date_create($startDate), date_create($endDate));
                $listPackage = [];
                if (isset($dataApi['isSuccess'])) {
                    if (!empty($dataApi['data']['rates'])) {
                        foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
                            if ($ratePackage['roomTypeID'] == $dataGet['room_key']) {
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
                                if ($hotel->price_customer_type == 0) {
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
                                    if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['sale_revenue'] != 0) {
                                        if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['sale_revenue_type'] == 0) {
                                            $ratePackage['trippal_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['sale_revenue'] * $dateDiff->days;
                                        } else {
                                            $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['sale_revenue'] / 100);
                                        }
                                    }
                                    if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['revenue'] != 0) {
                                        if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['revenue_type'] == 0) {
                                            $ratePackage['customer_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['revenue'] * $dateDiff->days;
                                        } else {
                                            $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataGet['room_key']]['revenue'] / 100);
                                        }
                                    }
                                }
                                $arrText = explode('-', $ratePackage['rateAvailablity']['ratePlan']['name']);
                                $packageName = '';
                                foreach ($arrText as $kText => $text) {
                                    $text = trim($text);
                                    $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                    $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                                }
                                $listPackage[] = [
                                    'rate_plan_code' => $ratePackage['rateAvailablity']['ratePlan']['rateCode'],
                                    'room_type_code' => $ratePackage['rateAvailablity']['roomTypeCode'],
                                    'allotment_id' => $ratePackage['rateAvailablity']['allotments'][0]['allotmentId'],
                                    'package_name' => $packageName,
                                    'package_code' => $ratePackage['rateAvailablity']['ratePlanCode'],
                                    'revenue' => $ratePackage['customer_price'],
                                    'sale_revenue' => $ratePackage['trippal_price'],
                                    'package_id' => $ratePackage['rateAvailablity']['propertyId'],
                                    'rateplan_id' => $ratePackage['ratePlanID'],
                                    'room_key' => $dataGet['room_key'],
                                    'package_pice' => $ratePackage['totalAmount']['amount']['amount'] + ($ratePackage['trippal_price'] + $ratePackage['customer_price']),
                                    'package_default_price' => $ratePackage['totalAmount']['amount']['amount'] + ($ratePackage['trippal_price'] + $ratePackage['customer_price']),
                                    'number_night' => $dateDiff->days,
                                ];
                            }
                        }
                    }
                }
                $finalData = $listPackage;
                $status = STT_SUCCESS;
                $message = 'Success';
            } else {
                $status = STT_NOT_FOUND;
                $message = 'No User Found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not login';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $finalData,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function suggestSearch()
    {
        $check = $this->Api->checkLoginApi();
        $status = STT_ERROR;
        $data = [];
        $message = "";
        if ($check) {
            $this->loadModel('Users');
            $this->loadModel('LandTours');
            $this->loadModel('Locations');
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            $keyword = $this->request->getQuery('keyword');
            if ($user) {
                if ($keyword) {
                    $listLandtour = $this->LandTours->find()->select(['id', 'name'])->where(['name LIKE' => '%' . $keyword . '%'])->limit(10)->toArray();
                    $listLocation = $this->Locations->find()->select(['id', 'name'])->where(['name LIKE' => '%' . $keyword . '%'])->limit(10)->toArray();
                    $listHotel = $this->Hotels->find()->select(['id', 'name'])->where(['name LIKE' => '%' . $keyword . '%', 'is_vinhms' => 0])->limit(10)->toArray();
                    $listVinpearl = $this->Hotels->find()->select(['id', 'name'])->where(['name LIKE' => '%' . $keyword . '%', 'is_vinhms' => 1])->limit(10)->toArray();
                    $data = [
                        'list_location' => $listLocation,
                        'list_landtour' => $listLandtour,
                        'list_hotel' => $listHotel,
                        'list_vinpearl' => $listVinpearl,
                    ];
                    $status = STT_SUCCESS;
                    $message = "success";
                } else {
                    $status = STT_NOT_VALIDATION;
                    $message = 'Không hợp lệ';
                }
            } else {
                $status = STT_NOT_LOGIN;
                $message = 'Chưa đăng nhập';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not Log In';
        }

        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
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
