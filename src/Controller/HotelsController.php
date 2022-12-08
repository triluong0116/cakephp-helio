<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use Cake\Utility\Hash;

/**
 * Hotels Controller
 *
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\LocationsTable $Locations
 * @property \App\Model\Table\RoomsTable $Rooms
 * @property \App\Model\Table\RoomsTable $RoomsPrices
 * @property \App\Model\Table\RoomPricesTable $RoomPrices
 * @property \App\Model\Table\HotelSurchargesTable $HotelSurcharges
 * @property \App\Model\Table\HotelSearchsTable $HotelSearchs
 * @property \App\Model\Table\VinroomsTable $Vinrooms
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\VinhmsallotmentsTable $Vinhmsallotments
 * @property \App\Controller\Component\UtilComponent $Util
 *
 * @method \App\Model\Entity\Hotel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HotelsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Locations']
        ];
        $hotels = $this->paginate($this->Hotels);

        $this->set(compact('hotels'));
    }

    /**
     * View method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $slug = $this->request->getParam('slug');
        $hotel = $this->Hotels->find()
            ->contain(['Locations', 'Rooms', 'Rooms.PriceRooms', 'Categories'])
            ->where(['Hotels.slug' => $slug])->first();
        if (!$hotel) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        if ($hotel->is_vinhms == 1) {
            return $this->redirect(['controller' => 'hotels', 'action' => 'viewVinpearl', $hotel->slug]);
        }

        $rooms = $this->Rooms->find()->contain(['PriceRooms'])->where(['hotel_id' => $hotel->id]);

        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            $ref = $user['ref_code'];
        }
        $title = $hotel->name;
        $headerType = 1;
        $this->set('hotel', $hotel);
        $this->set(compact('title', 'headerType', 'rooms'));
    }

    public function viewVinpearl($id = null)
    {
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('Vinrooms');
        $this->loadModel('Vinhmsallotments');
        $paramData = $this->request->getQuery();

        $dateParam = $paramData['date'];
        $date = explode('-', $paramData['date']);
        $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[0])));
        $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[1])));
        $dataRoom = $paramData['vin_room'];

        $numPeople = $paramData['num_people'];
        $roomData = explode('-', $paramData['num_people']);
        $numRoom = str_replace(' Phòng', '', $roomData[0]);
        $numAdult = str_replace('NL', '', $roomData[1]);
        $numChild = str_replace('TE', '', $roomData[2]);
        $numKid = str_replace('EB', '', $roomData[3]);
        $slug = $this->request->getParam('slug');
        $hotel = $this->Hotels->find()
            ->contain(['Locations', 'Rooms', 'Rooms.PriceRooms', 'Categories'])
            ->where(['Hotels.slug' => $slug])->first();
        if (!$hotel) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        if ($hotel->is_vinhms == 0) {
            return $this->redirect(['controller' => 'hotels', 'action' => 'view', $hotel->slug]);
        }

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
            empty($data['roomOccupancy']);
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
                            'image' => $image,
                            'information' => [
                                'image' => $json[0],
                                'name' => $singleRoom['name'],
                                'description' => $singleRoom['description'],
                                'maxAdult' => $singleRoom['maxAdult'],
                                'maxChild' => $singleRoom['maxChild'],
                                'squareUnit' => $singleRoom['squareUnit'],
                                'squareUnitType' => isset($singleRoom['squareUnitType']) ? $singleRoom['squareUnitType'] : "",
                                'min_price' => 999999999,
                                'extends' => $dataVinroom && $dataVinroom->extends ? json_decode($dataVinroom->extends, true) : []
                            ]
                        ];
                    }
                }
                if (!empty($dataApi['data']['rates'])) {
                    foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
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
        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            $ref = $user['ref_code'];
        } else {
            $user = null;
        }
        $title = $hotel->name;
        $headerType = 1;
        $this->set('hotel', $hotel);
        $this->set(compact('title', 'dateDiff', 'user', 'headerType', 'numRoom', 'numAdult', 'numChild', 'numKid', 'dateDiff', 'numPeople', 'dateParam', 'dataRoom', 'startDate', 'endDate', 'singleVinChooseRoom'));
    }

    public function chooseRoomVinpearl()
    {
        $hotel = $this->Hotels->find()->where(['slug' => $this->request->getParam('slug')])->first();
        $title = 'Mustgo Booking Khách Sạn';
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('Vinrooms');
        $this->loadModel('Vinhmsallotments');
        $paramData = $this->request->getData();

        $fromDate = date('d-m-Y', strtotime(str_replace('/', '-', $paramData['start_date'])));
        $toDate = date('d-m-Y', strtotime(str_replace('/', '-', $paramData['end_date'])));

        $hotelId = $paramData['hotel_id'];
        $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $paramData['start_date'])));
        $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $paramData['end_date'])));
        $dataRoom = $paramData['vin_room'];
        $numRoom = $paramData['num_room'];
        $numAdult = $paramData['num_adult'];
        $numChild = $paramData['num_child'];
        $numKid = $paramData['num_kid'];
        $headerType = 1;
        $this->set(compact('hotelId', 'fromDate', 'toDate', 'startDate', 'endDate', 'dataRoom', 'numRoom', 'numAdult', 'numAdult', 'numChild', 'numKid', 'headerType', 'hotel', 'title'));
    }

    public function searchForVinPackage()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('Vinrooms');
        $this->loadModel('Vinhmsallotments');
        $dataPost = $this->request->getData();
        $hotel = $this->Hotels->get($dataPost['hotel_id']);
        $listAllotments = $this->Vinhmsallotments->find()
            ->where([
                'hotel_id' => $hotel->id,
            ]);
        $allotmentRoom = [];
        foreach ($listAllotments as $k => $singleRoom) {
            if ($singleRoom->vinroom_code == $dataPost['room_id']) {
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

        $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $dataPost['start_date'])));
        $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $dataPost['end_date'])));
        $data = [
            "arrivalDate" => $startDate,
            "departureDate" => $endDate,
            "numberOfRoom" => 1,
            "propertyIds" => [$hotel->vinhms_code],
            "roomOccupancy" => []
        ];
        empty($data['roomOccupancy']);
        $roomOccupancy = [
            'numberOfAdult' => $dataPost['num_adult'],
            'otherOccupancies' => [
                [
                    'otherOccupancyRefCode' => 'child',
                    'quantity' => $dataPost['num_child']
                ],
                [
                    'otherOccupancyRefCode' => 'infant',
                    'quantity' => $dataPost['num_kid']
                ]
            ]
        ];
        $data['roomOccupancy'] = $roomOccupancy;
        $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
        $listRoom = [];
        $dateDiff = date_diff(date_create($startDate), date_create($endDate));
        if (isset($dataApi['isSuccess'])) {
            if (!empty($dataApi['data']['rates'])) {
                foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $k => $singleRoom) {
                    if ($singleRoom['id'] == $dataPost['room_id']) {
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
                            'image' => $image,
                            'information' => [
                                'image' => $json[0],
                                'name' => $singleRoom['name'],
                                'description' => $singleRoom['description'],
                                'maxAdult' => $singleRoom['maxAdult'],
                                'maxChild' => $singleRoom['maxChild'],
                                'squareUnit' => $singleRoom['squareUnit'],
                                'squareUnitType' => isset($singleRoom['squareUnitType']) ? $singleRoom['squareUnitType'] : "",
                                'min_price' => 999999999,
                                'extends' => $dataVinroom && $dataVinroom->extends ? json_decode($dataVinroom->extends, true) : []
                            ]
                        ];
                    }
                }
            }
            if (!empty($dataApi['data']['rates'])) {
                foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
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
                        if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['sale_revenue'] != 0) {
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['sale_revenue_type'] == 0) {
                                $ratePackage['trippal_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['sale_revenue'] * $dateDiff->days;
                            } else {
                                $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['sale_revenue'] / 100);
                            }
                        }
                        if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['revenue'] != 0) {
                            if ($allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['revenue_type'] == 0) {
                                $ratePackage['customer_price'] = $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['revenue'] * $dateDiff->days;
                            } else {
                                $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotmentRoom[$ratePackage['rateAvailablity']['allotments'][0]['name']][$dataPost['room_id']]['revenue'] / 100);
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
        $roomIndex = $dataPost['room_index'];
        $this->set(compact('listRoom', 'roomIndex', 'dateDiff'));
        $this->render('search_for_vin_package')->body();
    }

    public function addSearchPackageVin()
    {
        $response = [
            'html_package' => null,
            'room_total' => 0,
            'total_vin_booking_price' => 0,
            'total_vin_booking_revenue' => 0,
            'total_agency_pay_vin_booking' => 0,
        ];
        $this->viewBuilder()->enableAutoLayout(false);
        $listRoom = $this->request->getData();
        $fromDate = $listRoom['start_date'];
        $toDate = $listRoom['end_date'];
        $this->set(compact('listRoom', 'fromDate', 'toDate'));
        $response['html_package'] = $this->render('add_search_package_vin')->body();
        $response['room_total'] = number_format(str_replace(',', '', $listRoom['packagePrice']) + str_replace(',', '', $listRoom['roomIndexPrice']));
        $response['total_vin_booking_price'] = number_format(str_replace(',', '', $listRoom['packagePrice']) + str_replace(',', '', $listRoom['totalVinBookingPrice']));
        $response['total_vin_booking_revenue'] = number_format(str_replace(',', '', $listRoom['revenue']) + str_replace(',', '', $listRoom['totalVinBookingRevenue']));
        $response['total_agency_pay_vin_booking'] = number_format(str_replace(',', '', $listRoom['packagePrice']) - str_replace(',', '', $listRoom['revenue']) + str_replace(',', '', $listRoom['totalAgencyPayVinBooking']));

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }


    public function listVinpearlHotels()
    {
        $this->loadModel('Locations');
        $this->loadModel('Vinrooms');
        // build condition & sort
        $sortPrice = $this->request->getQuery('sort');
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');
        $listLocation = explode(',', $filterLocation);
        $listRating = explode(',', $filterRating);

        $orderFilter = $this->request->getQuery('sort') ? $this->request->getQuery('sort') : 'DESC';

        $condition = [];
        $data = $this->request->getQuery();
        $dataVinRoom = $data['vin_room'];
        $keyword = $fromDate = $numPeople = '';
        if (isset($data['keyword'])) {
            $condition['Hotels.name Like'] = '%' . $data['keyword'] . '%';
            $keyword = $data['keyword'];
        }
        if (isset($data['fromDate'])) {
            $fromDate = $data['fromDate'];
        }
        if (isset($data['num_people'])) {
            $numPeople = $data['num_people'];
        }
        $outputSlider = '';
        $listLocation = explode(',', $filterLocation);
        $listLocation = array_filter($listLocation);
        $listPrice = [];
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }

        if ($filterLocation) {
            $condition['location_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
            $conditionHotel['rating IN'] = $listRating;
        }

        $listVinperlHotels = $this->Hotels->find()->contain('Locations')->where([
            'is_vinhms' => 1,
            'vinhms_code !=' => '',
            $condition
        ]);
        $date = explode(' - ', $data['fromDate']);
        $roomData = explode('-', $data['num_people']);
        $numRoom = str_replace(' Phòng', '', $roomData[0]);
        $numAdult = str_replace('NL', '', $roomData[1]);
        $numChild = str_replace('TE', '', $roomData[2]);
        $numKid = str_replace('EB', '', $roomData[3]);
        $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[0])));
        $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[1])));
        $dateDiff = date_diff(date_create($startDate), date_create($endDate));
        $testUrl = $this->viewVars['testUrl'];
        $vinIds = [];
        $listVinInfor = [];
        foreach ($listVinperlHotels as $hotel) {
            if (!in_array($hotel->vinhms_code, $vinIds)) {
                $vinIds[] = $hotel->vinhms_code;
            }
            $listVinInfor[$hotel->vinhms_code] = [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'slug' => $hotel->slug,
                'address' => $hotel->address,
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
                    $listVinInfor[$singleHotel['property']['id']]['singlePrice'] = $listVinInfor[$singleHotel['property']['id']]['show_price'] + $listVinInfor[$singleHotel['property']['id']]['price_agency'];
                }
            }
        }

        $listVinInfor = array_values($listVinInfor);
//        dd($listVinInfor);
        if ($orderFilter && $orderFilter == "DESC") {
            $sortType = "DESC";
            usort($listVinInfor, function ($a, $b) {
                return ($b['singlePrice'] >= $a['singlePrice']) ? 1 : -1;
            });
        }
        if ($orderFilter && $orderFilter == "ASC") {
            $sortType = "ASC";
            usort($listVinInfor, function ($a, $b) {
                return ($a['singlePrice'] >= $b['singlePrice']) ? 1 : -1;
            });
        }
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
        $title = "Khách sạn Vinpearl";
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => "Khách sạn Vinpearl"]
        ];
        $this->set(compact('dateDiff', 'sortType', 'listVinInfor', 'title', 'headerType', 'breadcrumbs', 'sortPrice', 'listPrice', 'listRating', 'outputSlider', 'listLocation', 'keyword', 'fromDate', 'numPeople', 'dataVinRoom'));
    }

    public function searchVinpearlHotels()
    {
        $title = 'Mustgo Booking Khách Sạn';
        $startDate = date('d-m-Y');
        $endDate = date('d-m-Y', strtotime('+1 day', strtotime($startDate)));
        $date = str_replace('-', '/', $startDate) . ' - ' . str_replace('-', '/', $endDate);
//        dd($startDate, $endDate);
        $headerType = 1;
        $this->set(compact('title', 'headerType', 'date'));
    }

    public function booking()
    {
        if ($this->Auth->user('role_id') == 2) {
            $this->redirect($this->referer());
        } else {
            $this->loadModel('Rooms');
            $this->loadModel('HotelSurcharges');
            $title = 'Mustgo Booking Khách Sạn';
            $headerType = 1;
            $slug = $this->getRequest()->getParam('slug');
            $hotel = $this->Hotels->find()
                ->contain(['Locations', 'Rooms', 'Rooms.PriceRooms', 'Categories'])
                ->where(['Hotels.slug' => $slug])->first();
            if (!$hotel) {
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }
            $rooms = $this->Rooms->find('list')->where(['hotel_id' => $hotel->id]);
            $room_id = $num_adult = $num_children = $num_room = $revenue = 0;
            $fromDate = $toDate = '';

            if ($this->request->is('post')) {
                $data = $this->getRequest()->getData();

                $room_id = $data['room_id'];
                $num_adult = $data['num_adult'];
                $num_children = $data['num_children'];
                $num_room = $data['numRoom'];

                $fromDate = $data['fromDate'];
                $toDate = $data['toDate'];

                $hotel = $this->Hotels->get($data['hotel_id']);
                $rooms = $this->Rooms->find('list')->where(['hotel_id' => $hotel->id]);
                $revenue = ($hotel->price_agency + $hotel->price_customer) * $num_room;
            }
            $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
            $normalSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type NOT IN' => $constantAutoSurcharge, 'hotel_id' => $hotel->id]);
            $autoSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type IN' => $constantAutoSurcharge, 'hotel_id' => $hotel->id]);
//            dd($autoSurcharges->toArray());

            $this->set(compact('headerType', 'title', 'hotel', 'rooms', 'room_id', 'fromDate', 'toDate', 'num_adult', 'num_children', 'num_room', 'normalSurcharges', 'revenue', 'autoSurcharges'));
        }
    }

    public function bookingVinpearl()
    {
        $this->loadModel('Users');
        $data = $this->request->getData();
        if (!$data) {
            $this->redirect($this->referer());
        }
        if ($this->Auth->user()) {
            $user = $this->Users->get($this->Auth->user('id'));
        } else {
            $user = null;
        }
        $hotel = $this->Hotels->find()->where(['slug' => $this->request->getParam('slug')])->first();
        $title = 'Mustgo Booking Khách Sạn';
        $headerType = 1;
        $totalPrice = 0;
        $totalRevenue = $totalSaleRevenue = 0;
        if ($data['vin_booking_type'] == 1) {
            $data['date_diff'] = date_diff(date_create($data['start_date']), date_create($data['end_date']));
            foreach ($data['vin_room'] as $k => $room) {
                $totalPrice += str_replace(',', '', $room['price']);
                $totalRevenue += $room['revenue'];
                $totalSaleRevenue += $room['sale_revenue'];
            }
        } else {
            $startDate = 9999999999;
            $endDate = 0;
            foreach ($data['vin_room'] as $k => $singleRoomPackage) {
                foreach ($singleRoomPackage['package'] as $pK => $package) {
                    $startDate > strtotime($package['start_date']) ? $startDate = strtotime($package['start_date']) : true;
                    $endDate < strtotime($package['end_date']) ? $endDate = strtotime($package['end_date']) : true;
                    $totalPrice += str_replace(',', '', $package['package_pice']);
                    $totalRevenue += $package['revenue'];
                    $totalSaleRevenue += $package['sale_revenue'];
                }
            }
            $data['start_date'] = date('Y-m-d', $startDate);
            $data['end_date'] = date('Y-m-d', $endDate);
            $data['date_diff'] = date_diff(date_create($data['start_date']), date_create($data['end_date']));
        }
        $this->set(compact('title', 'headerType', 'data', 'totalPrice', 'totalRevenue', 'totalSaleRevenue', 'hotel', 'user'));
    }

    public function vinpearlBooking()
    {
        $title = 'Mustgo Booking Khách Sạn';
        $headerType = 1;
        $this->set(compact('title', 'headerType'));
    }

    public function bookingPayment()
    {
        $title = 'Mustgo Booking Thanh Toán';
        $headerType = 1;
        $this->set(compact('headerType', 'title'));
    }

    public function location()
    {
        $this->loadModel('Locations');
        $locations = $this->Locations->find()->toArray();
        $title = 'Tất cả điểm đến';
        $headerType = 1;
        $this->set(compact('headerType', 'locations', 'title'));
    }

    public function commit()
    {
        $this->loadModel('HotelSearchs');
        $this->loadModel('Vinrooms');
        $testUrl = $this->viewVars['testUrl'];
        $hotels = $this->Hotels->find()->contain(['Locations'])
            ->where(['is_commit' => 1])->order(['Locations.is_featured' => 'DESC']);
        $listCommit = [];
        $today = date('Y-m-d');
        $conditionPrice['single_day'] = $today;

        foreach ($hotels as $key => $hotel) {
            $conditionPrice['id'][] = $hotel->id;
            if (!isset($listCommit[$hotel->location->name])) {
                $listCommit[$hotel->location->name][$hotel->id] = $hotel;
            } else {
                $listCommit[$hotel->location->name][$hotel->id] = $hotel;
            }
        }
        $priceDay = $this->HotelSearchs->find()->where([
            'id IN' => $conditionPrice['id'],
            'single_day' => $conditionPrice['single_day']
        ]);
        foreach ($priceDay as $singlePriceDay) {
            $listCommit[$singlePriceDay->location_name][$singlePriceDay->id]->singlePrice = number_format($singlePriceDay->price_day);
        }
        foreach ($listCommit as $singleLocation => $detailLocation) {
            $listCommit[$singleLocation] = array_values($detailLocation);
        }
        $title = "Mustgo COMMIT";
        $this->set(compact('listCommit', 'title'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $hotel = $this->Hotels->newEntity();
        if ($this->request->is('post')) {
            $hotel = $this->Hotels->patchEntity($hotel, $this->request->getData());
            if (isset($data['address'])) {
                $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($data['address']) . '&key=' . MAP_API);
                $output = json_decode($geocode);
                $hotel->lat = $output->results[0]->geometry->location->lat;
                $hotel->lon = $output->results[0]->geometry->location->lng;
            }
            if ($this->Hotels->save($hotel)) {
                $this->Flash->success(__('The hotel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
        }
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);
        $categories = $this->Hotels->Categories->find('list', ['limit' => 200]);
        $combos = $this->Hotels->Combos->find('list', ['limit' => 200]);
        $this->set(compact('hotel', 'locations', 'categories', 'combos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Categories', 'Combos']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $hotel = $this->Hotels->patchEntity($hotel, $this->request->getData());
            if (isset($data['address'])) {
                $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($data['address']) . '&key=' . MAP_API);
                $output = json_decode($geocode);
                $hotel->lat = $output->results[0]->geometry->location->lat;
                $hotel->lon = $output->results[0]->geometry->location->lng;
            }
            if ($this->Hotels->save($hotel)) {
                $this->Flash->success(__('The hotel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
        }
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);
        $categories = $this->Hotels->Categories->find('list', ['limit' => 200]);
        $combos = $this->Hotels->Combos->find('list', ['limit' => 200]);
        $this->set(compact('hotel', 'locations', 'categories', 'combos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $hotel = $this->Hotels->get($id);
        if ($this->Hotels->delete($hotel)) {
            $this->Flash->success(__('The hotel has been deleted.'));
        } else {
            $this->Flash->error(__('The hotel could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function addHotelForCombo()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $hotels = $this->Hotels->find('list');
        $this->set(compact('hotels'));
        $this->render('add_hotel_for_combo')->body();
    }

    public function getPriceByDate()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => true, 'new_price' => ''];
        $data = $this->request->getData();
        $hotel = $this->Hotels->get($data['hotel_id'], ['contain' => ['PriceHotels']]);
        foreach ($hotel->price_hotels as $price) {
            if ($this->Util->checkBetweenDate($data['date'], $price->start_date, $price->end_date)) {
                $priceHotel = $price->price + $hotel->price_agency + $hotel->price_customer;
                break;
            } else {
                continue;
            }
        }
        $response['new_price'] = number_format($priceHotel);

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function description($hotel_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $hotel = $this->Hotels->get($hotel_id);
//        dd($hotel);
        $this->set(compact('hotel'));
//        $response = [];
    }

    public function category($hotel_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $hotel = $this->Hotels->get($hotel_id, ['contain' => ['Categories']]);
//        dd($hotel);
        $this->set(compact('hotel'));
//        $response = [];
    }

    public function term($hotel_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $hotel = $this->Hotels->get($hotel_id);
//        dd($hotel);
        $this->set(compact('hotel'));
//        $response = [];
    }

    public function filterRoomData()
    {
        $this->loadModel('Rooms');
        $this->loadModel('RoomPrices');
        $this->viewBuilder()->enableAutoLayout(false);

        $response = ['success' => true, 'price' => 0, 'result' => '', 'data' => '', 'profit' => 0];
        $data = $this->getRequest()->getQuery();

        $startDate = $data['fromDate'];
        $endDate = $data['toDate'];
        $numb_room = $data['numRoom'];
        $num_adult = $data['num_adult'];
        $num_children = $data['num_children'];
        $hotel = $this->Hotels->get($data['hotel_id']);
        $conditions = [
            'hotel_id' => $data['hotel_id']
        ];
        $rooms = $this->Rooms->find()
            ->where($conditions)->toArray();
        $calSDate = $data['fromDate'];
        $calEDate = date('d-m-Y', strtotime($data['toDate'] . "-1 days"));
        $dates = $this->Util->_dateRange($calSDate, $calEDate);
        $weekends = (json_decode($hotel->weekend));
        $holidayDates = json_decode($hotel->holidays);
        $holidays = [];
        if ($holidayDates) {
            foreach ($holidayDates as $holidayDate) {
                $holidayDate = explode(' - ', $holidayDate);
                $holidayStartDate = $this->Util->formatSQLDate($holidayDate[0], 'd/m/Y');
                $holidayEndDate = $this->Util->formatSQLDate($holidayDate[1], 'd/m/Y');
                $holidays = array_merge($this->Util->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
            }
        }
        foreach ($rooms as $key => $room) {
            $foundRoomPrice = false;
            $price = 0;
            $profit = 0;
            foreach ($dates as $date) {
                $resPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $date);
                $price += $resPrice['price'] * $numb_room;
                $rooms[$key]['start_date_price'] = $resPrice['price'];
                $profit += $this->Util->calculateHotelRevenue($hotel, $room->id, $date) * $numb_room;
//                $priceTypes = [HOLIDAY, WEEK_END, WEEK_DAY];
//                if (!in_array(date('Y-m-d', strtotime($date)), $holidays)) {
//                    $priceTypes = array_diff($priceTypes, [HOLIDAY]);
//                }
//                if (!in_array(date('l', strtotime($date)), $weekends)) {
//                    $priceTypes = array_diff($priceTypes, [WEEK_END]);
//                }
//                foreach ($priceTypes as $priceType) {
//                    $roomPrice = $this->RoomPrices->find()
//                        ->where(['room_id' => $room->id])
//                        ->where(['room_day =' => $date])
//                        ->where(['type >=' => $priceType])
//                        ->first();
//                    if ($roomPrice) {
//                        if (isset($roomPrice->price_customer) && isset($roomPrice->price_agency)) {
//                            $price += $numb_room * ($roomPrice->price + $roomPrice->price_customer + $roomPrice->price_agency);
//                        } else {
//                            $price += $numb_room * ($roomPrice->price + $hotel->price_customer + $hotel->price_agency);
//                        }
//                        $profit += isset($roomPrice->price_customer) ? $roomPrice->price_customer : $hotel->price_customer;
//                        if (!$foundRoomPrice) {
//                            $rooms[$key]['start_date_price'] = $price;
//                            $rooms[$key]['available_count'] = $roomPrice->available;
//                            $foundRoomPrice = true;
//                        }
//                        break;
//                    }
//                }
            }
            $rooms[$key]['price'] = $price;
            $rooms[$key]['profit'] = $profit;
            $rooms[$key]['final_price'] = $price - $profit;
        }
        if (!empty($rooms)) {
            $first_room = $rooms[0];
            $result = $hotel->name . ', hạng phòng ' . $first_room->name . ', check in ' . $startDate . ' check out ' . $endDate;
            $result .= ', ' . $numb_room . ' phòng ngủ, ' . $num_adult . ' người lớn, ' . $num_children . ' trẻ em.';
            $this->set(compact('rooms', 'endDate', 'startDate', 'numb_room', 'hotel', 'result', 'price', 'profit', 'num_adult', 'num_children'));
            $response['data'] = $this->render('filter_room_data')->body();
            $response['result'] = $result;
            $response['price'] = $first_room['price'] ? number_format($first_room['price']) . ' VNĐ' : 'Chưa cập nhật';
            $response['profit'] = $first_room['profit'] ? number_format($first_room['profit']) . ' VNĐ' : 'Chưa cập nhật';
            $price = $first_room['price'] ? $first_room['price'] : 0;
            $rev = $first_room['profit'] ? $first_room['profit'] : 0;
            $response['final_price'] = ($price != 0 && $rev != 0) ? number_format($price - $rev) . ' VNĐ' : "Chưa cập nhật";
            $response['first_room_id'] = $first_room->id;
        }


        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function addHotelRoom()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Rooms');
        $hotel_id = $this->getRequest()->getQuery('hotel_id');
        $rooms = $this->Rooms->find('list')->where(['hotel_id' => $hotel_id]);
        $this->set(compact('rooms'));
    }

    public function addHotelRoomVin()
    {
//        dd($this->getRequest());
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Rooms');
        $hotel_id = $this->getRequest()->getQuery('hotel_id');
        $rooms = $this->Rooms->find('list')->where(['hotel_id' => $hotel_id]);
        $this->set(compact('rooms'));
    }

    public function removeVinroomPackage()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = [
            'total_vin_room' => 0,
            'total_booking_price' => 0,
            'total_booking_revenue' => 0,
            'total_agency_pay' => 0
        ];
        $data = $this->request->getData();
        $response['total_vin_room'] = str_replace(',', '', $data['total_vin_room']) - str_replace(',', '', $data['package_price']);
        $response['total_booking_price'] = str_replace(',', '', $data['total_booking_price']) - str_replace(',', '', $data['package_price']);
        $response['total_booking_revenue'] = str_replace(',', '', $data['total_booking_revenue']) - str_replace(',', '', $data['revenue']);
        $response['total_agency_pay'] = $response['total_booking_price'] - $response['total_booking_revenue'];

        $response['total_vin_room'] = number_format($response['total_vin_room']);
        $response['total_booking_price'] = number_format($response['total_booking_price']);
        $response['total_booking_revenue'] = number_format($response['total_booking_revenue']);
        $response['total_agency_pay'] = number_format($response['total_agency_pay']);

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function calBookingHotelPrice()
    {
        $this->loadModel('Rooms');
        $this->loadModel('HotelSurcharges');
        $this->viewBuilder()->enableAutoLayout(false);

        $response = ['success' => true, 'errors' => [], 'data_auto_surcharge' => '', 'data_surcharge_price' => [], 'total_price' => 0, 'total_revenue' => 0];
        $data = $this->getRequest()->getQuery();
//        dd($data);
        $hotel = $this->Hotels->get($data['hotel_id'], ['contain' => ['HotelSurcharges']]);

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
            if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
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
                    $bookingRoomPrice = $tmpRevenue = 0;
                    foreach ($dates as $date) {
                        $resPrice = $this->Util->calculateHotelPrice($hotel, $room->id, $date);
                        if ($resPrice['status']) {
                            $bookingRoomPrice += $resPrice['price'];
                            $tmpRevenue += $this->Util->calculateHotelRevenue($hotel, $room->id, $date);
                        } else {
                            $response['success'] = false;
                            $response['errors']['incorrect_info'] = ['message' => $resPrice['message']];
                            break;
                        }
                    }
                    $total_price += $bookingRoomPrice * $num_room;
                    $revenue += $tmpRevenue * $num_room;
                    $bookingStr .= 'Hạng phòng ' . $room->name . ', checkin ' . $booking_room['start_date'] . ', check out ' . $booking_room['end_date'] . ', ';
                    $bookingStr .= $booking_room['num_adult'] . ' người lớn, ' . $booking_room['num_children'] . ' trẻ em.';
                } else {
                    $response['success'] = false;
                    $isAllow = false;
                    $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người.']];
                }
            } else {
                $response['success'] = false;
                $isAllow = false;
                $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
            }

        }
        if ($isAllow) {
            $data_surcharges = [];
            foreach ($hotelAutoSurcharges as $surcharge_id) {
                $priceCalculated = $this->Util->calHotelSurcharge($hotel, $data['booking_rooms'], $surcharge_id, 0, 0);
                $data_surcharges[$this->Util->getSurchargeId($surcharge_id)] = $priceCalculated;
                $data_surcharges[$this->Util->getSurchargeId($surcharge_id, '', false)] = $priceCalculated;
                $total_price += $priceCalculated;
            }

            foreach ($data['booking_surcharges'] as $key => $booking_surcharge) {
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
                    $data_surcharges[$this->Util->getSurchargeId($booking_surcharge['surcharge_type'], $other_id, false)] = $priceCalculated;
                    $total_price += $priceCalculated;
                }
            }
            if ($this->Auth->user('role_id') == 3 || $this->Auth->user('role_id') == 2 || $this->Auth->user('role_id') == 5) {
                $total_price = $total_price - $revenue;
                $revenue = 0;
            }

            $response['data_surcharge_price'] = $data_surcharges;
            $response['total_price'] = $total_price;
            $response['total_revenue'] = $revenue;
            $response['booking_str'] = $bookingStr;
        }

        $output = $this->getResponse();
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;

    }

    public function addSelectChildAge()
    {
        $this->loadModel('Rooms');
        $response = ['success' => false, 'data' => '', 'errors' => []];

        $this->viewBuilder()->enableAutoLayout(false);
        $data = $this->getRequest()->getData();
        $key = $firstKey = array_key_first($data['booking_rooms']);
        $roomData = array_values($data['booking_rooms'])[0];
        $room = $this->Rooms->get($roomData['room_id']);
        $roomTotalMaxPeople = $room->max_people * $roomData['num_room'];
        if (($roomData['num_adult'] + $roomData['num_children']) <= $roomTotalMaxPeople) {
            $numChildren = $roomData['num_children'];
            $response['success'] = true;
        } else {
            $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
            $numChildren = 0;
        }


        $this->set(compact('numChildren', 'room'));
        $response['data'] = $this->render('add_select_child_age')->body();
//        dd($response);

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function calBookingRoomPrice()
    {
//        $this->loadModel('Rooms');
//        $this->viewBuilder()->enableAutoLayout(false);
//
//        $response = ['price' => 0, 'id' => 0];
//
//        $data = $this->getRequest()->getQuery();
//        $dataRoom = array_values($data['booking_rooms'])[0];
//
//        $room = $this->Rooms->get($dataRoom['room_id']);
//        $hotel = $this->Hotels->get($room->hotel_id);
//        if ($dataRoom['start_date'] && $dataRoom['end_date']) {
//            $calSDate = $this->Util->formatSQLDate($dataRoom['start_date'], 'd-m-Y');
//            $calEDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($dataRoom['end_date'] . "-1 days")), 'd-m-Y');
//            $dates = $this->Util->_dateRange($calSDate, $calEDate);
//        } else {
//            $dates[] = date('Y-m-d');
//        }
//
//        $price = 0;
//        if ($dataRoom['num_room']) {
//            $num_room = $dataRoom['num_room'];
//        } else {
//            $num_room = 1;
//        }
//        foreach ($dates as $date) {
//            $price += $this->Util->calculateHotelPrice($hotel, $room->id, $date);
//        }
//        $price = $price * $num_room;
//
//        $response['price'] = $price;
//        $response['id'] = $room->id;
//
//        $output = $this->response;
//        $output = $output->withType('json');
//        $output = $output->withStringBody(json_encode($response));
//        return $output;
    }

    public function calAutoSurcharge()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Rooms');
        $this->loadModel('RoomPrices');
        $this->loadModel('HotelSurcharges');

        $response = ['success' => false, 'message' => '', 'data' => '', 'errors' => []];

        $data = $this->getRequest()->getQuery();

        $hotel = $this->Hotels->get($data['hotel_id'], ['contain' => ['HotelSurcharges', 'HotelSurcharges.Surcharges']]);
        $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
        $hotelSurchargeLists = Hash::extract($hotel->hotel_surcharges, '{n}.surcharge_type');
        $hotelAutoSurcharges = array_values(array_intersect($constantAutoSurcharge, $hotelSurchargeLists));
        $weekends = json_decode($hotel->weekend, true);
        $holidayDates = json_decode($hotel->holidays, true);
        $holidays = [];
        foreach ($holidayDates as $holidayDate) {
            $holidayDate = explode(' - ', $holidayDate);
            $holidayStartDate = $this->Util->formatSQLDate($holidayDate[0], 'd/m/Y');
            $holidayEndDate = $this->Util->formatSQLDate($holidayDate[1], 'd/m/Y');
            $holidays = array_merge($this->Util->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
        }

        $surcharges = [];
        $countAdult = $countChildren = 0;
        $holiday_surcharge = $weekend_surcharge = $adult_surcharge = $children_surcharge = 0;
        $hotelSurHoliday = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_HOLIDAY])->first();
        $hotelSurWeekend = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_WEEKEND])->first();

        $hotelSurAdult = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_ADULT])->first();
        $hotelSurChild = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_CHILDREN])->first();

        $isAllow = false;

        if (isset($data['booking_rooms'])) {
            $countAdultSurcharge = $countChildrenSurcharge = 0;
            foreach ($data['booking_rooms'] as $key => $booking_room) {
                $room = $this->Rooms->get($booking_room['room_id']);
                $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
                $roomTotalChildren = $room->num_children * $booking_room['num_room'];
                $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];

                $calSDate = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                $calEDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                $dates = $this->Util->_dateRange($calSDate, $calEDate);

                foreach ($dates as $date) {
                    if (in_array($date, $holidays) && $hotelSurHoliday) {
                        $holiday_surcharge += $hotelSurHoliday->price;
                    }
                    if (in_array(date('l', strtotime($date)), $weekends) && $hotelSurWeekend) {
                        $weekend_surcharge += $hotelSurWeekend->price;
                    }
                }
                if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                    if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                        $isAllow = true;
                        $child_ages = isset($booking_room['child_ages']) ? $booking_room['child_ages'] : [];
                        if ($booking_room['num_adult'] >= $roomTotalAdult) {
                            $countAdultSurcharge += $booking_room['num_adult'] - $roomTotalAdult;
                        } else {
                            $bonusAdult = $roomTotalAdult - $booking_room['num_adult'];
                            rsort($booking_room['child_ages']);
                            $child_ages = array_slice($booking_room['child_ages'], $bonusAdult, count($booking_room['child_ages']) - $bonusAdult);
                        }
                        $child_ages = $this->Util->checkFreeChildSurcharge($child_ages, $room->standard_child_age);
                        $children_surcharge += $this->Util->calChildSurcharge($child_ages, $hotelSurChild);
                    } else {
                        $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá số lượng NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalAdult . ' người lớn.']];
                    }
                } else {
                    $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá số lượng NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                }
            }

            if ($hotelSurAdult) {
                $adult_surcharge = $hotelSurAdult->price * $countAdultSurcharge;
            } else {
                $adult_surcharge = 0;
            }
        }
        if ($isAllow) {
            foreach ($hotelAutoSurcharges as $surcharge_id) {
                switch ($surcharge_id) {
                    case SUR_WEEKEND:
                        $autoItem['id'] = SUR_WEEKEND;
                        $autoItem['title'] = 'Phụ thu cuối tuần';
                        $autoItem['fee'] = $weekend_surcharge;
                        $surcharges[] = $autoItem;
                        break;
                    case SUR_HOLIDAY:
                        $autoItem['id'] = SUR_HOLIDAY;
                        $autoItem['title'] = 'Phụ thu ngày lễ';
                        $autoItem['fee'] = $holiday_surcharge;
                        $surcharges[] = $autoItem;
                        break;
                    case SUR_ADULT:
                        $autoItem['id'] = SUR_ADULT;
                        $autoItem['title'] = 'Phụ thu người lớn';
                        $autoItem['fee'] = $adult_surcharge;
                        $surcharges[] = $autoItem;
                        break;
                    case SUR_CHILDREN:
                        $autoItem['id'] = SUR_CHILDREN;
                        $autoItem['title'] = 'Phụ thu trẻ em';
                        $autoItem['fee'] = $children_surcharge;
                        $surcharges[] = $autoItem;
                        break;
                }
            }
            $this->set(compact('surcharges'));
            $response['data'] = $this->render('cal_auto_surcharge')->body();
            $response['success'] = true;
        }
//        dd($response);
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;

    }

    public function calNormalSurcharge()
    {
//        $this->loadModel('HotelSurcharges');
//        $this->loadModel('RoomPrices');
//
//        $response['price'] = 0;
//        $this->viewBuilder()->enableAutoLayout(false);
//        $data = $this->getRequest()->getData();
//        $hotel_surcharge = $this->HotelSurcharges->find()->where(['hotel_id' => $data['hotel_id'], 'surcharge_type' => $data['surcharge_type']])->first();
//        if ($data['surcharge_type'] == SUR_CHECKOUT_LATE || $data['surcharge_type'] == SUR_CHECKIN_SOON) {
//
//            $options = json_decode($hotel_surcharge->options, true);
//            if (isset($data['booking_rooms'])) {
//
//                $surcharge_price = 0;
//                $hotel = $this->Hotels->get($data['hotel_id']);
//
//                $total_room_price = 0;
//                foreach ($data['booking_rooms'] as $booking_room) {
//                    $sDate = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
//                    $eDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
//                    $dates = $this->Util->_dateRange($sDate, $eDate);
//                    $room_price = 0;
//                    foreach ($dates as $date) {
//                        $room_price += $this->Util->calculateHotelPrice($hotel, $booking_room['room_id'], $date, true);
//                    }
//                    $total_room_price += $room_price * $booking_room['num_room'];
//                }
//
//                foreach ($options as $option) {
//                    $cTime = \DateTime::createFromFormat('H:i', $data['quantity']);
//                    $sTime = \DateTime::createFromFormat('H:i', $option['start']);
//                    $eTime = \DateTime::createFromFormat('H:i', $option['end']);
//                    if ($cTime >= $sTime && $cTime <= $eTime) {
//                        $surcharge_price = ceil($total_room_price * $option['price'] / 100);
//                        break;
//                    }
//                }
//                $response['price'] = $surcharge_price;
//            } else {
//                $response['price'] = 0;
//            }
//        } else {
//            $price = $data['quantity'] * $hotel_surcharge->price;
//            $response['price'] = $price;
//        }
//
//        $output = $this->response;
//        $output = $output->withType('json');
//        $output = $output->withStringBody(json_encode($response));
//        return $output;
    }

    public function addOtherSurcharge()
    {
        $this->viewBuilder()->enableAutoLayout(false);
    }

    public function editBooking()
    {
        if ($this->Auth->user()) {
            $this->loadModel('Bookings');
            $this->loadModel('HotelSurcharges');
            $this->loadModel('BookingSurcharges');
            $title = 'Xem lại đơn hàng';
            $headerType = 1;
            $this->loadModel('Configs');
            $this->loadModel('Payments');
            $code = $this->getRequest()->getParam('code');
            $booking = $this->Bookings->find()->contain(['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtourAccessories', 'Vouchers', 'LandTours', 'HomeStays'])->where(['code' => $code])->first();
            if ($booking && $booking->status < 3) {
                $this->loadModel('Rooms');
                $title = 'Mustgo Booking Landtour';
                $headerType = 1;
                $hotel = $this->Hotels->get($booking->item_id);
                $rooms = $this->Rooms->find('list')->where(['hotel_id' => $hotel->id]);
                $constantAutoSurcharge = [SUR_WEEKEND, SUR_HOLIDAY, SUR_ADULT, SUR_CHILDREN];
                $normalSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type NOT IN' => $constantAutoSurcharge, 'hotel_id' => $hotel->id]);
                $autoSurcharges = $this->HotelSurcharges->find()->where(['surcharge_type IN' => $constantAutoSurcharge, 'hotel_id' => $hotel->id]);

                $arr_booking_surcharges = [];
                $booking_surcharges = $this->BookingSurcharges->find()->where(['booking_id' => $booking->id])->toArray();
                foreach ($booking_surcharges as $booking_surcharge) {
                    $arr_booking_surcharges[$booking_surcharge['surcharge_type']] = $booking_surcharge;
                }


                $this->set(compact('headerType', 'title', 'booking', 'rooms', 'normalSurcharges', 'autoSurcharges', 'hotel', 'arr_booking_surcharges'));
            } else {
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }
        } else {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
    }

    public function listChannelHotels(){
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('Channelrooms');
        $this->loadModel('Channelrateplanes');
        $paramData = $this->request->getQuery();
        $keyword = $fromDate = $numPeople = '';
        if (isset($paramData['keyword'])) {
            $condition['Hotels.name Like'] = '%' . $paramData['keyword'] . '%';
            $keyword = $paramData['keyword'];
        }
        if (isset($paramData['fromDate'])) {
            $fromDate = $paramData['fromDate'];
        }
        if (isset($paramData['num_people'])) {
            $numPeople = $paramData['num_people'];
        }
        else
        {
            $numPeople =  "1 Phòng-1NL-0TE-0EB";
        }
        if (isset($paramData['vin_room'])){
            $channel_room = $paramData['vin_room'];
        }
        else{
            $channel_room = [['num_adult' => 1 , "num_child" => 0, "num_kid" => 0]];
        }
//        dd($paramData,$channel_room,$numPeople);
        $roomData = explode('-',$numPeople);
        $numRoom = str_replace(' Phòng', '', $roomData[0]);
        $numAdult = str_replace('NL', '', $roomData[1]);
        $numChild = str_replace('TE', '', $roomData[2]);
        $numKid = str_replace('EB', '', $roomData[3]);
//        dd($numRoom,$numAdult,$numChild,$channel_room);
        $date = isset( $paramData['fromDate']) ?  explode(' - ', $paramData['fromDate']) : '';
        if ($date){
            $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[1])));
        }
        else {
            $startDate =  date('Y-m-d');
            $endDate = date('Y-m-d', strtotime(date('Y-m-d') . '+1 day'));
        }
        $hotels = $this->Hotels->find()->contain(['Locations', 'Channelrooms','Channelrooms.Channelrateplanes', 'Categories'])
            ->where(['is_hotel_link' => 1])->all();

//        foreach ($hotels as $hotel){
//            $hotels->minPrice =  $this->Util->getPriceHotelLink($hotels->hotel_link_code);
//        }
        $title = "Danh sách Khách sạn";
        $headerType = 1;
        $this->set('hotels', $hotels);
        $this->set(compact('title', 'headerType', 'startDate', 'endDate', 'fromDate' , 'keyword' , 'numPeople', 'numAdult' , 'numRoom' , 'numChild' ,'channel_room'));

    }
    public function viewChannel($id = null)
    {
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('Channelrooms');
        $this->loadModel('Channelrateplanes');
        $paramData = $this->request->getQuery();
        if (isset($paramData['date']) ){
            $dateParam = $paramData['date'];
            $date = explode('-', $paramData['date']);
            $fromDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[0])));
            $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[1])));
        }
        else {
            $fromDate =  date('Y-m-d');
            $endDate = date('Y-m-d', strtotime(date('Y-m-d') . '+1 day'));
            $dateParam = '';
        }
        $numPeople = $paramData['num_people'];
        $channel_room = $paramData['vin_room'];
        $roomData = explode('-', $paramData['num_people']);
        $numRoom = str_replace(' Phòng', '', $roomData[0]);
        $numAdult = str_replace('NL', '', $roomData[1]);
        $numChild = str_replace('TE', '', $roomData[2]);
        $numKid = str_replace('EB', '', $roomData[3]);
        $slug = $this->request->getParam('slug');
        $hotel = $this->Hotels->find()
            ->contain(['Locations', 'Channelrooms','Channelrooms.Channelrateplanes', 'Categories'])
            ->where(['Hotels.slug' => $slug])->first();
        if (!$hotel) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        if ($hotel->is_hotel_link == 0) {
            return $this->redirect(['controller' => 'hotels', 'action' => 'view', $hotel->slug]);
        }

        $dateDiff = date_diff(date_create($fromDate), date_create($endDate));

        $rateplaneIds = [];
        foreach ($hotel->channelrooms as $room){
            foreach ($room->channelrateplanes as $rateplane){
                if (!in_array($rateplane->rateplan_code,$rateplaneIds)){
                    $rateplaneIds[] = $rateplane->rateplan_code;
                }
            }
        }
        $dataRespone = $this->Util->getInventory($hotel->hotel_link_code, $rateplaneIds, $fromDate, $endDate);
//        dd($dataRespone);
        $dataPrice = [];
        $currency = '';
        if($dataRespone['result']){
            foreach ($dataRespone['data']['Inventories'] as $inventory){
                $dataPrice[$inventory['RoomId']]['min_price'] = 0;
                $dataPrice[$inventory['RoomId']]['statusRoom'] = 1;
                foreach ($inventory['Availabilities'] as  $availability){
                    $dataPrice[$inventory['RoomId']]['quantity'] = $availability['Quantity'];
                    if ($availability['Quantity'] < $numRoom || $dataPrice[$inventory['RoomId']]['statusRoom'] == 0) {
                        $dataPrice[$inventory['RoomId']]['statusRoom'] = 0;
                    } else {
                        $dataPrice[$inventory['RoomId']]['statusRoom'] = 1;
                    }
                }
                foreach ($inventory['RatePackages'] as $ratePackage){
                    if (isset($ratePackage['StopSell']) && $ratePackage['StopSell'] == 1){
                        $dataPrice[$inventory['RoomId']]['statusRoom'] = 0;
                    }else{
                        $dateDiffrate = date_diff(date_create($ratePackage['DateRange']['From']), date_create($ratePackage['DateRange']['To']))->days;
                        $dateRanger = [];
                        $price = -1;
                        $dateRanger[] = [
                            'DateRange' => $ratePackage['DateRange'],
                            'price' => isset($ratePackage['Rate']['Amount']['Value']) ? $ratePackage['Rate']['Amount']['Value'] : 0  ,
                        ];
                        if (isset($dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price'])){
                            $dateDiff1 = $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['dateDiff'];
                            $price = ($dateDiff1 == 0) ? $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price'] : ($dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price']/$dateDiff1)*($dateDiff1 +1) ;
                            $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price'] = $price + (isset($ratePackage['Rate']['Amount']['Value']) ? $ratePackage['Rate']['Amount']['Value'] * $dateDiffrate : 0) ;
                            $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['dateDiff'] += $dateDiffrate + 1 ;
                            $decodeDateRanger = json_decode($dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['DateRange']);
                            $decodeDateRanger[] =  $dateRanger[0];
                            $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['DateRange'] = json_encode($decodeDateRanger);
                        }
                        else{
                            $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price'] = isset($ratePackage['Rate']['Amount']['Value']) ? $ratePackage['Rate']['Amount']['Value'] * $dateDiffrate : 0 ;
                            $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['dateDiff'] = $dateDiffrate;
                            $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['DateRange'] = json_encode($dateRanger);
                        }
                        $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['currency'] = $ratePackage['Rate']['Amount']['Currency'];
                        $dataPrice[$inventory['RoomId']]['currency'] = $ratePackage['Rate']['Amount']['Currency'];
                        $currency = $ratePackage['Rate']['Amount']['Currency'];
                        if( $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price'] != 0 && $dataPrice[$inventory['RoomId']]['min_price'] != $price ){
                            $dataPrice[$inventory['RoomId']]['min_price'] = ( $dataPrice[$inventory['RoomId']]['min_price'] < $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price'] && $dataPrice[$inventory['RoomId']]['min_price'] != 0 ) ? $dataPrice[$inventory['RoomId']]['min_price'] : $dataPrice[$inventory['RoomId']][$ratePackage['RatePlanId']]['price']  ;
                        }
                    }
                }
            }
        }
//        dd($hotel,$rateplaneIds , $dataRespone , $dataPrice);
        $title = $hotel->name;
        $headerType = 1;
        $this->set('hotel', $hotel);
        $this->set(compact('title', 'dateDiff', 'headerType', 'slug' , 'numRoom', 'numAdult', 'numChild', 'numKid', 'dateDiff', 'numPeople', 'dateParam', 'fromDate', 'endDate', 'hotel', 'dataPrice','channel_room','currency'));
    }

    public function bookingChannel()
    {
        $this->loadModel('Users');
        $data = $this->request->getData();
        if (!$data) {
            $this->redirect($this->referer());
        }
        if ($this->Auth->user()) {
            $user = $this->Users->get($this->Auth->user('id'));
        } else {
            $user = null;
        }
        $hotel = $this->Hotels->find()->where(['slug' => $this->request->getParam('slug')])->first();
        $title = 'Mustgo Booking Khách Sạn';
        $headerType = 1;
        $totalPrice = 0;
        $totalRevenue = $totalSaleRevenue = 0;
        if ($data['channel_booking_type'] == 1) {
            $data['date_diff'] = date_diff(date_create($data['fromDate']), date_create($data['end_date']));
            foreach ($data['channel_room'] as $k => $room) {
                $totalPrice += str_replace(',', '', $room['price']);
                $totalSaleRevenue += $room['sale_revenue'];
            }
        } else {
            $startDate = 9999999999;
            $endDate = 0;
            foreach ($data['channel_room'] as $k => $singleRoomPackage) {
                foreach ($singleRoomPackage['package'] as $pK => $package) {
                    $startDate > strtotime($package['start_date']) ? $startDate = strtotime($package['start_date']) : true;
                    $endDate < strtotime($package['end_date']) ? $endDate = strtotime($package['end_date']) : true;
                    $totalPrice += str_replace(',', '', $package['package_pice']);
                    $totalSaleRevenue += $package['sale_revenue'];
                }
            }
            $data['start_date'] = date('Y-m-d', $startDate);
            $data['end_date'] = date('Y-m-d', $endDate);
            $data['date_diff'] = date_diff(date_create($data['start_date']), date_create($data['end_date']));
        }
        $this->set(compact('title', 'headerType', 'data', 'totalPrice', 'totalRevenue', 'totalSaleRevenue', 'hotel', 'user'));
    }
}
