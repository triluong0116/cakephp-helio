<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

//require ROOT . DS . "vendor" . DS . "phpoffice" . DS . "phpspreadsheet" . DS . "src" . DS . "Bootstrap.php";
//require ROOT . DS . "vendor" . DS . "phpoffice/phpspreadsheet/src/Bootstrap.php";

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\LandtourPaymentFeesTable $LandtourPaymentFees
 * @property \App\Model\Table\VinhmsbookingsTable $Vinhmsbookings
 * @property \App\Model\Table\BookingLogsTable $BookingLogs
 */
class DashboardsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
//        $this->Auth->allow(['logout']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('backend');
        $this->loadModel('Bookings');
        $bookings = $this->Bookings->find()->contain(['Users', 'Hotels', 'LandTours', 'Vouchers', 'HomeStays'])->order(['Bookings.created' => 'DESC']);
//        dd($bookings->toArray());
        $this->set(compact('bookings'));
    }

    public function profitReport()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $curentYear = date('Y');
        if ($this->request->getData()) {
            $curentYear = $this->request->getData('year');
        }
        $bookings = $this->Bookings->find()->select([
            'month' => 'month(Bookings.complete_date)',
            'start_date',
            'end_date',
            'amount',
            'sale_id',
            'Users.screen_name',
            'Hotels.name',
            'Hotels.price_agency',
            'Users.parent_id',
            'SUM_SALE_REV' => 'SUM(IF(Bookings.sale_id != Bookings.user_id, Bookings.sale_revenue, 0))',
            'SUM_SALE_CTV_REV' => 'SUM(IF(Bookings.sale_id = Bookings.user_id AND Bookings.complete_date < "2022-05-01", Bookings.sale_revenue + Bookings.revenue - Bookings.agency_discount, 0))',
            'SUM_SALE_CTV_REV_NEW' => 'SUM(IF(Bookings.sale_id = Bookings.user_id AND Bookings.complete_date >= "2022-05-01", Bookings.sale_revenue + Bookings.revenue, 0))'
        ])
            ->contain([
                'Users',
                'Hotels',
                'LandTours',
                'Vouchers',
                'HomeStays'
            ])
            ->where(['year(Bookings.complete_date)' => $curentYear,
                'Bookings.type !=' => LANDTOUR,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3,
                        'Bookings.status <=' => 4
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ])->group(['month', 'Bookings.sale_id']);
        $listSale = [];
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];
        $sales = $this->Users->find()->where(['role_id' => 2]);
        $listPickSale = [];
        foreach ($sales as $sale) {
            $listPickSale[$sale->id] = $sale->screen_name;
        }

        foreach ($sales as $sale) {
            $listSale[$sale->id] = [
                'name' => $sale->screen_name,
                'month' => [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                    9 => 0,
                    10 => 0,
                    11 => 0,
                    12 => 0,
                ]
            ];
        }
        foreach ($bookings as $booking) {
            if (isset($listSale[$booking->sale_id]['month'][$booking->month])) {
                $listSale[$booking->sale_id]['month'][$booking->month] += $booking->SUM_SALE_REV + $booking->SUM_SALE_CTV_REV + $booking->SUM_SALE_CTV_REV_NEW;
                $totalByMonth[$booking->month] += $booking->SUM_SALE_REV + $booking->SUM_SALE_CTV_REV + $booking->SUM_SALE_CTV_REV_NEW;
            }
        }
//        dd($listSale);


        $this->set(compact(['listSale', 'totalByMonth', 'listPickSale', 'curentYear']));

    }

    public function profitReportVin()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Users');
        $curentYear = date('Y');
        if ($this->request->getData()) {
            $curentYear = $this->request->getData('year');
        }
        $bookings = $this->Vinhmsbookings->find()->select([
            'month' => 'month(Vinhmsbookings.complete_date)',
            'start_date',
            'end_date',
            'sale_id',
            'Users.screen_name',
            'Hotels.name',
            'Hotels.price_agency',
            'Users.parent_id',
            'SUM_SALE_REV' => 'SUM(IF(Vinhmsbookings.sale_id != Vinhmsbookings.user_id, Vinhmsbookings.sale_revenue - Vinhmsbookings.sale_discount - Vinhmsbookings.agency_discount, 0))',
            'SUM_SALE_CTV_REV' => 'SUM(IF(Vinhmsbookings.sale_id = Vinhmsbookings.user_id, Vinhmsbookings.sale_revenue + Vinhmsbookings.revenue - Vinhmsbookings.sale_discount - Vinhmsbookings.agency_discount, 0))'
        ])
            ->contain([
                'Users',
                'Hotels'
            ])
            ->where(['year(Vinhmsbookings.complete_date)' => $curentYear,
                'OR' => [
                    ['Vinhmsbookings.status' => 4],
                    [
                        'Vinhmsbookings.sale_id = Vinhmsbookings.user_id',
                        'Vinhmsbookings.status' => 3,
                    ]
                ]
            ])->group(['month', 'Vinhmsbookings.sale_id']);
        $listSale = [];
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];
        $sales = $this->Users->find()->where(['role_id' => 2]);
        $listPickSale = [];
        foreach ($sales as $sale) {
            $listPickSale[$sale->id] = $sale->screen_name;
        }

        foreach ($sales as $sale) {
            $listSale[$sale->id] = [
                'name' => $sale->screen_name,
                'month' => [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                    9 => 0,
                    10 => 0,
                    11 => 0,
                    12 => 0,
                ]
            ];
        }
        foreach ($bookings as $booking) {
            if (isset($listSale[$booking->sale_id]['month'][$booking->month])) {
                $listSale[$booking->sale_id]['month'][$booking->month] += $booking->SUM_SALE_REV + $booking->SUM_SALE_CTV_REV;
                $totalByMonth[$booking->month] += $booking->SUM_SALE_REV + $booking->SUM_SALE_CTV_REV;
            }
        }
//        dd($listSale);


        $this->set(compact(['listSale', 'totalByMonth', 'listPickSale', 'curentYear']));

    }

    public function profitReportLandtour()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $curentYear = date('Y');
        if ($this->request->getData()) {
            $curentYear = $this->request->getData('year');
        }
        $bookings = $this->Bookings->find()->select([
            'month' => 'month(Bookings.start_date)',
            'start_date',
            'end_date',
            'amount',
            'sale_id',
            'Users.screen_name',
            'Hotels.name',
            'Hotels.price_agency',
            'Users.parent_id',
            'SUM_SALE_REV' => 'SUM(IF(Bookings.sale_id != Bookings.user_id, Bookings.sale_revenue, 0))',
            'SUM_SALE_CTV_REV' => 'SUM(IF(Bookings.sale_id = Bookings.user_id, Bookings.sale_revenue + Bookings.revenue, 0))'
        ])
            ->contain([
                'Users',
                'Hotels',
                'LandTours',
                'Vouchers',
                'HomeStays'
            ])
            ->where(['year(Bookings.start_date)' => $curentYear,
                'Bookings.type' => LANDTOUR,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ])->group(['month', 'Bookings.sale_id']);
        $listSale = [];
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];
        $sales = $this->Users->find()->where(['role_id' => 5]);
        $listPickSale = [];
        foreach ($sales as $sale) {
            $listPickSale[$sale->id] = $sale->screen_name;
        }

        foreach ($sales as $sale) {
            $listSale[$sale->id] = [
                'name' => $sale->screen_name,
                'month' => [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                    9 => 0,
                    10 => 0,
                    11 => 0,
                    12 => 0,
                ]
            ];
        }
        foreach ($bookings as $booking) {
            if (isset($listSale[$booking->sale_id]['month'][$booking->month])) {
                $listSale[$booking->sale_id]['month'][$booking->month] += $booking->SUM_SALE_REV + $booking->SUM_SALE_CTV_REV;
                $totalByMonth[$booking->month] += $booking->SUM_SALE_REV + $booking->SUM_SALE_CTV_REV;
            }
        }
//        dd($listSale);


        $this->set(compact(['listSale', 'totalByMonth', 'listPickSale', 'curentYear']));

    }

    public function statisticSaleByDate()
    {
        $this->loadModel('Bookings');
        $this->viewBuilder()->enableAutoLayout(false);
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $date = explode(' - ', $data['reservation']);
            $sDate = $this->Util->formatSQLDate($date[0], 'd/m/Y');
            $eDate = $this->Util->formatSQLDate($date[1], 'd/m/Y');
            $bookings = $this->Bookings->find()->contain([
                'Users',
                'Hotels',
                'Hotels.Locations',
                'Vouchers',
                'Vouchers.Hotels',
                'Vouchers.Hotels.Locations',
                'LandTours',
                'LandTours.Destinations',
                'HomeStays',
                'HomeStays.Locations',
            ])->where([
                'DATE(Bookings.complete_date) >=' => $sDate,
                'DATE(Bookings.complete_date) <=' => $eDate,
                'Bookings.sale_id' => $data['sale_id'],
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ])->order(['Bookings.complete_date' => 'DESC  ']);
            $this->set(compact('bookings'));
            $this->render('statistic_sale_by_date');
        }
    }

    public function agencyReport()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');

        $currentYear = date('Y');
        $currentAgency = 0;
        if ($this->request->getData()) {
            $currentYear = $this->request->getData('year');
            $currentAgency = $this->request->getData('agency');
        }
        $condition = [];
        $conditionSale = [];
        $conditionListSale = [];
        if ($currentAgency) {
            $user = $this->Users->get($currentAgency);
            if ($user) {
                if ($user->role_id == 3) {
                    $conditionListSale['Users.id'] = $user->parent_id;
                } else {
                    $conditionListSale['Users.id'] = $user->id;
                }
            }
            $condition['Bookings.user_id'] = $currentAgency;
            $conditionSale['ChildUsers.id'] = $currentAgency;
        }

        $bookings = $this->Bookings->find()
            ->contain([
                'Users',
                'BookingRooms',
                'BookingLandtours'
            ])
            ->where([
                $condition,
                'year(Bookings.complete_date)' => $currentYear,
                'type' => HOTEL,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ]);
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];


        $sales = $this->Users->find()->contain(['ChildUsers' => function ($q) use ($conditionSale) {
            return $q->where($conditionSale);
        }])->where(['role_id' => 2, $conditionListSale]);
        $listSale = [];
        foreach ($sales as $sale) {
            if (!$currentAgency) {
                array_unshift($sale->child_users, $sale);
            }
            $listSale[$sale->id] = [
                'name' => $sale->screen_name,
                'child' => [
                ]
            ];
            foreach ($sale->child_users as $agency) {
                $child = [
                    'agency_name' => $agency->screen_name,
                    'month' => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                        4 => 0,
                        5 => 0,
                        6 => 0,
                        7 => 0,
                        8 => 0,
                        9 => 0,
                        10 => 0,
                        11 => 0,
                        12 => 0,
                    ]
                ];
                $listSale[$sale->id]['child'][$agency->id] = $child;
            }
        }
        foreach ($bookings as $booking) {
            $month = date('n', strtotime($booking->complete_date));
            if ($booking->user) {
                $booking->user->parent_id == 0 ? $parentId = $booking->user_id : $parentId = $booking->user->parent_id;
            }
            if ($booking->type == LANDTOUR) {
                if (!$booking->booking_landtour) {
                    $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $booking->amount;
                    $totalByMonth[$month] += $booking->amount;
                } else {
                    $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += ($booking->booking_landtour->num_adult + $booking->booking_landtour->num_children);
                    $totalByMonth[$month] += ($booking->booking_landtour->num_adult + $booking->booking_landtour->num_children);
                }
            } elseif ($booking->type == HOTEL) {
                if (!$booking->booking_rooms) {
                    if ($booking->end_date && $booking->start_date) {
                        $dateDiff = date_diff($booking->end_date, $booking->start_date);
                        if (isset($listSale[$parentId]['child'][$booking->user_id])) {
                            $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $booking->amount * $dateDiff->days;
                        }
                        $totalByMonth[$month] += $booking->amount * $dateDiff->days;
                    } else {
                        if (isset($listSale[$parentId]['child'][$booking->user_id])) {
                            $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += 0;
                        }
                        $totalByMonth[$month] += 0;
                    }

                } else {
                    $totalNight = 0;
                    foreach ($booking->booking_rooms as $bookingRoom) {
                        $dateDiff = date_diff($bookingRoom->end_date, $bookingRoom->start_date);
                        $totalNight += $bookingRoom->num_room * $dateDiff->days;
                    }
                    $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $totalNight;
                    $totalByMonth[$month] += $totalNight;
                }
            } else {
                $dateDiff = date_diff($booking->end_date, $booking->start_date);
                $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $booking->amount * $dateDiff->days;
                $totalByMonth[$month] += $booking->amount * $dateDiff->days;
            }
        }
        $queryAgency = $this->Users->find()->where(['role_id IN' => [2, 3]]);
        $listAgency = [];
        foreach ($queryAgency as $agency) {
            $listAgency[$agency->id] = $agency->screen_name;
        }

        $this->set(compact(['listSale', 'totalByMonth', 'currentYear', 'listAgency', 'currentAgency']));

    }

    public function hotelReport()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Hotels');

        $currentYear = date('Y');
        $currentHotel = 0;
        if ($this->request->getData()) {
            $currentYear = $this->request->getData('year');
            $currentHotel = $this->request->getData('hotel_id');
        }
        $conditionHotel = [];
        $conditionVin = [];
        if ($currentHotel) {
            $conditionHotel['Bookings.item_id'] = $currentHotel;
            $conditionVin['Vinhmsbookings.hotel_id'] = $currentHotel;
        }
        $bookings = $this->Bookings->find()->contain(['Hotels', 'Hotels.Locations', 'BookingRooms'])
            ->where([
                $conditionHotel,
                'YEAR(Bookings.complete_date)' => $currentYear,
                'Bookings.type' => HOTEL,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3,
                        'Bookings.status <=' => 4,
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ]);
        $data = [];
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];
//        dd($bookings->toArray());
        foreach ($bookings as $booking) {
            if ($booking->hotels) {
                $data[$booking->item_id]['name'] = $booking->hotels->name;
                if ($booking->hotels->location) {
                    $data[$booking->item_id]['location'] = $booking->hotels->location->name;
                } else {
                    $data[$booking->item_id]['location'] = "Không có địa điểm";
                }
            }
            $data[$booking->item_id]['month'] = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
                12 => 0,
            ];
        }
        foreach ($bookings as $booking) {
            $month = date("n", strtotime($booking->complete_date));
            $totalByBooking = 0;
            if ($booking->booking_rooms) {
                foreach ($booking->booking_rooms as $booking_room) {
                    if ($booking_room->end_date && $booking_room->start_date) {
                        $date = date_diff($booking_room->end_date, $booking_room->start_date);
                        $totalByBooking += $date->days * $booking_room->num_room;
                    }
                }
            } else {
                if ($booking->end_date && $booking->start_date) {
                    $date = date_diff($booking->end_date, $booking->start_date);
                    $totalByBooking = $date->days * $booking->amount;
                }
            }
            $data[$booking->item_id]['month'][$month] += $totalByBooking;
            $totalByMonth[$month] += $totalByBooking;
        }

        $vinbookings = $this->Vinhmsbookings->find()->contain(['Hotels', 'Hotels.Locations', 'VinhmsbookingRooms'])
            ->where([
                $conditionVin,
                'YEAR(Vinhmsbookings.complete_date)' => $currentYear,
                'OR' => [
                    ['Vinhmsbookings.status' => 4],
                    ['Vinhmsbookings.status' => 3],
                ]
            ]);
        foreach ($vinbookings as $vinbooking) {
            if ($vinbooking->hotel) {
                $data[$vinbooking->hotel_id]['name'] = $vinbooking->hotel->name;
                if ($vinbooking->hotel->location) {
                    $data[$vinbooking->hotel_id]['location'] = $vinbooking->hotel->location->name;
                } else {
                    $data[$vinbooking->hotel_id]['location'] = "Không có địa điểm";
                }
            }
            $data[$vinbooking->hotel_id]['month'] = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
                12 => 0,
            ];
        }
        foreach ($vinbookings as $vinbooking) {
            $month = date("n", strtotime($vinbooking->complete_date));
            if ($vinbooking->vinhmsbooking_rooms) {
                foreach ($vinbooking->vinhmsbooking_rooms as $singleRoom) {
                    $days = date_diff($singleRoom->checkin, $singleRoom->checkout);
                    $data[$vinbooking->hotel_id]['month'][$month] += $days->days;
                    $totalByMonth[$month] += $days->days;
                }
            }
        }


        $queryHotel = $this->Hotels->find();
        $listHotel = [];
        foreach ($queryHotel as $hotel) {
            $listHotel[$hotel->id] = $hotel->name;
        }

        $this->set(compact(['data', 'totalByMonth', 'currentYear', 'listHotel', 'currentHotel']));
    }

    public function landtourReport()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('LandtourPaymentFees');

        $currentYear = date('Y');
        if ($this->request->getData()) {
            $currentYear = $this->request->getData('year');
        }

        $bookings = $this->Bookings->find()->contain(['LandTours', 'LandTours.Departures', 'BookingRooms'])
            ->where([
                'YEAR(Bookings.complete_date)' => $currentYear,
                'Bookings.type' => LANDTOUR,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ]);
        $data = [];
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];
        $listPaymentFee = $this->LandtourPaymentFees->find();
//        dd($bookings->toArray());
        foreach ($bookings as $booking) {
            $data[$booking->item_id]['name'] = $booking->land_tours->name;
            if ($booking->land_tours->departure) {
                $data[$booking->item_id]['location'] = $booking->land_tours->departure->name;
            } else {
                $data[$booking->item_id]['location'] = "Không có địa điểm";
            }
            $data[$booking->item_id]['month'] = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
                12 => 0,
            ];
        }
        $listPaymentFees = $this->LandtourPaymentFees->find()->where(['YEAR(created)' => $currentYear]);
        foreach ($bookings as $booking) {
            $month = date("n", strtotime($booking->start_date));
            $data[$booking->item_id]['month'][$month] += $booking->price;
            $totalByMonth[$month] += $booking->price;
        }
        foreach ($listPaymentFees as $k => $fee) {
            $month = date("n", strtotime($fee->date));
            $data[65]['month'][$month] -= $fee->total;
            $totalByMonth[$month] -= $fee->total;
        }

        $this->set(compact(['data', 'totalByMonth', 'currentYear']));
    }

    public function indexBooking()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
            $confirmAgencyPay = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Date(Bookings.start_date) >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Date(Bookings.end_date) <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        $condition['Bookings.status IN'] = [0, 1, 2, 3, 4, 5];
        $condition['Bookings.type !='] = LANDTOUR;
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'BookingSurcharges',
            'BookingRooms',
        ])->where($condition)->order(['Bookings.created' => 'DESC'])->limit(15);
        $bookings = $this->paginate($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            $booking->payment = $payment;
        }
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function indexBookingDatatable()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Bookings');
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getData();
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Date(Bookings.start_date) >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Date(Bookings.end_date) <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
        }
        if (isset($data['query']['Type']) && $data['query']['Type']) {
            // do condition here
        }
        $condition['Bookings.status IN'] = [0, 1, 2, 3, 4, 5];
        $condition['Bookings.type !='] = LANDTOUR;
        if (isset($data['query'])) {
            $keyword = $data['query'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'BookingSurcharges',
            'BookingRooms',
        ])->where($condition)->order(['Bookings.created' => 'DESC'])->limit(15);
        $bookings = $this->paginate($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            $booking->payment = $payment;
        }
        $response = [
            'meta' => [
                "page" => 1,
                "pages" => 35,
                "perpage" => 15,
                "total" => 350,
            ],
            'data' => $bookings
        ];
        $this->set([
            'my_response' => $response,
            '_serialize' => 'my_response',
        ]);
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function indexBookingVinpearl()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Vinpayments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        $condition['Vinhmsbookings.status IN'] = [0, 1, 2, 3, 4, 5];
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Vinhmsbookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Vinhmsbookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition['OR'] = [
                'code LIKE' => '%' . $keyword . '%',
                'Users.screen_name LIKE' => '%' . $keyword . '%',
                'Users.username LIKE' => '%' . $keyword . '%',
                'Hotels.name LIKE' => '%' . $keyword . '%',
            ];
        }
        $paginate = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Users'])->where($condition)->order(
            [
                'Vinhmsbookings.created' => 'DESC'
            ]);
        $bookings = $this->paginate($paginate);
        foreach ($bookings as $key => $booking) {
            $payment = $this->Vinpayments->find()->where(['booking_id' => $booking->id])->first();
            $booking->vinpayment = $payment;
        }
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function viewBookingVin($id)
    {
        $this->loadModel('Vinpayments');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('BookingLogs');
        $booking = $this->Vinhmsbookings->get($id, [
            'contain' => ['VinhmsbookingRooms', 'Hotels', 'Vinpayments', 'Users', 'Hotels.Locations']
        ]);
        if (!$booking) {
            $this->redirect(['controller' => 'Dashboards', 'action' => 'profitReport', 'prefix' => 'admin']);
        }

        $listRoom = [];
        foreach ($booking->vinhmsbooking_rooms as $room) {
            if (!isset($listRoom[$room->room_index])) {
                $listRoom[$room->room_index]['vinhms_name'] = $room['vinhms_name'];
                $listRoom[$room->room_index]['num_adult'] = $room['num_adult'];
                $listRoom[$room->room_index]['num_kid'] = $room['num_kid'];
                $listRoom[$room->room_index]['num_child'] = $room['num_child'];
                $listRoom[$room->room_index]['packages'][] = $room;
            } else {
                $listRoom[$room->room_index]['packages'][] = $room;
            }
        }
        $booking->vinhmsbooking_rooms = $listRoom;
        $this->set('booking', $booking);
        $payment = $this->Vinpayments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
        $this->set('payment', $payment);
        $userLogs = $this->Auth->user();
        $this->set('userLogs', $userLogs);
        $bookingLogs = $this->BookingLogs->find()
            ->join([
                'u' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'u.id = BookingLogs.user_id',
                ]
            ])
            ->where([
                'booking_id' => $booking->id,
                'code' => $booking->code
            ])
            ->select(['u.screen_name', 'u.role_id', 'BookingLogs.id',  'BookingLogs.comment', 'BookingLogs.title', 'BookingLogs.created'])
            ->toArray();
        $this->set('bookingLogs', $bookingLogs);
    }

    public function indexBookingLandtour()
    {
        $this->loadModel('Bookings');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
            $confirmAgencyPay = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Bookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Bookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        $condition['Bookings.status IN'] = [0, 1, 2, 3, 4, 5];
        $condition['type'] = LANDTOUR;
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'LandTours.name LIKE' => '%' . $keyword . '%',
                    'Vouchers.name LIKE' => '%' . $keyword . '%',
                    'HomeStays.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'Vouchers',
            'Vouchers.Hotels',
            'Vouchers.Hotels.Locations',
            'LandTours',
            'LandTours.Destinations',
            'HomeStays',
            'HomeStays.Locations',
            'BookingSurcharges',
            'BookingRooms',
            'BookingLandtours',
            'BookingRooms'
        ])->where($condition)->order(['Bookings.created' => 'DESC',]);
        $bookings = $this->paginate($bookings);
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function payBookingHotel()
    {
        $this->loadModel('Bookings');
        $this->loadModel('Payments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
            $confirmAgencyPay = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Bookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Bookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        $condition['Bookings.mail_type'] = 0;
        $condition['Bookings.status <'] = 3;
        $condition['Bookings.status >'] = 1;
        $condition['Bookings.type !='] = LANDTOUR;
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition[] = [
                'OR' => [
                    'Bookings.code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'BookingSurcharges',
            'BookingRooms',
        ])->where($condition)->where(['Bookings.start_date >=' => '2021-07-01'])->order(['Bookings.created' => 'DESC']);
        $bookings = $this->paginate($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            $booking->payment = $payment;
        }
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function payBookingForHotel()
    {
        $this->loadModel('Bookings');
        $this->loadModel('Payments');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
            $confirmAgencyPay = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Bookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Bookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        $condition['Bookings.mail_type !='] = 0;
        $condition['Bookings.status <'] = 3;
        $condition['Bookings.type !='] = LANDTOUR;
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'LandTours.name LIKE' => '%' . $keyword . '%',
                    'Vouchers.name LIKE' => '%' . $keyword . '%',
                    'HomeStays.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'Vouchers',
            'Vouchers.Hotels',
            'Vouchers.Hotels.Locations',
            'LandTours',
            'LandTours.Destinations',
            'HomeStays',
            'HomeStays.Locations',
            'BookingSurcharges',
            'BookingRooms',
            'BookingLandtours',
            'BookingRooms',
            'Payments'
        ])->where($condition)->order(['Bookings.created' => 'DESC']);
        $bookings = $this->paginate($bookings);
        foreach ($bookings as $k => $booking) {
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            $booking->payment = $payment;
        }
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function payBookingLandtour()
    {
        $this->loadModel('Bookings');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
            $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
            $confirmAgencyPay = $data['confirm_agency_pay'];
        }
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Bookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Bookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        $condition['Bookings.status IN'] = [1, 2, 3, 4];
        $condition['Bookings.agency_pay'] = 1;
        $condition['Bookings.booking_type'] = SYSTEM_BOOKING;
        $condition['Bookings.type'] = LANDTOUR;
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition[] = [
                'OR' => [
                    'code LIKE' => '%' . $keyword . '%',
                    'Users.screen_name LIKE' => '%' . $keyword . '%',
                    'Users.username LIKE' => '%' . $keyword . '%',
                    'LandTours.name LIKE' => '%' . $keyword . '%',
                    'full_name LIKE' => '%' . $keyword . '%',
                    'hotel_code LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $bookings = $this->Bookings->find()->contain(['Users',
            'Hotels',
            'Hotels.Locations',
            'Vouchers',
            'Vouchers.Hotels',
            'Vouchers.Hotels.Locations',
            'LandTours',
            'LandTours.Destinations',
            'HomeStays',
            'HomeStays.Locations',
            'BookingSurcharges',
            'BookingRooms',
            'BookingLandtours',
            'BookingRooms'
        ])->where($condition)->order(['Bookings.created' => 'DESC',]);
        $bookings = $this->paginate($bookings);
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function payBookingVinpearl()
    {
        $this->loadModel('Vinhmsbookings');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        $condition['Vinhmsbookings.status IN'] = [0, 1];
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Vinhmsbookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Vinhmsbookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition['OR'] = [
                'code LIKE' => '%' . $keyword . '%',
                'Users.screen_name LIKE' => '%' . $keyword . '%',
                'Users.username LIKE' => '%' . $keyword . '%',
                'Hotels.name LIKE' => '%' . $keyword . '%',
            ];
        }
        $paginate = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments', 'Users'])->where($condition)->order(
            [
                'Vinhmsbookings.created' => 'DESC'
            ]);
        $bookings = $this->paginate($paginate);
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function payBookingForVinpearl()
    {
        $this->loadModel('Vinhmsbookings');
        $this->paginate = [
            'limit' => 15];
        $data = $this->request->getQuery();
        $agencyPay = null;
        $payHotel = null;
        $confirmAgencyPay = null;
        $date = null;
        $sDate = date('d/m/Y');
        $eDate = date('d/m/Y');
        $keyword = '';
        $condition = [];
        if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
            $condition['agency_pay'] = $data['agency_pay'];
            $agencyPay = $data['agency_pay'];
        }
        if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
            $condition['pay_hotel'] = $data['pay_hotel'];
            $payHotel = $data['pay_hotel'];
        }
        $condition['Vinhmsbookings.status IN'] = [2];
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $condition['Vinhmsbookings.start_date >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
            $sDate = $data['start_date'];
        }
        if (isset($data['end_date']) && $data['end_date'] != null) {
            $condition['Vinhmsbookings.end_date <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
            $eDate = $data['end_date'];
        }
        if (isset($data['search'])) {
            $keyword = $data['search'];
            $condition['OR'] = [
                'code LIKE' => '%' . $keyword . '%',
                'Users.screen_name LIKE' => '%' . $keyword . '%',
                'Users.username LIKE' => '%' . $keyword . '%',
                'Hotels.name LIKE' => '%' . $keyword . '%',
            ];
        }
        $paginate = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments', 'Users'])->where($condition, ['status' => 2])->order(
            [
                'Vinhmsbookings.created' => 'DESC'
            ]);
        $bookings = $this->paginate($paginate);
        $this->set(compact('bookings', 'payHotel', 'keyword', 'agencyPay', 'confirmAgencyPay', 'sDate', 'eDate'));
    }

    public function view($id = null)
    {
        $this->loadModel('Payments');
        $this->loadModel('Bookings');
        $this->loadModel('BookingLogs');
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Hotels', 'Vouchers', 'HomeStays', 'LandTours', 'Hotels.Locations', 'Vouchers.Hotels', 'Vouchers.Hotels.Locations', 'LandTours.Destinations', 'HomeStays.Locations', 'BookingRooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'BookingRooms.Rooms', 'BookingSurcharges']
        ]);

        $this->set('booking', $booking);
        $payment = $this->Payments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
        $this->set('payment', $payment);
        $bookingLogs = $this->BookingLogs->find()
            ->join([
                'u' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'u.id = BookingLogs.user_id',
                ]
            ])
            ->where([
                'booking_id' => $booking->id,
                'code' => $booking->code
            ])
            ->select(['u.screen_name', 'u.role_id', 'BookingLogs.id',  'BookingLogs.comment', 'BookingLogs.title', 'BookingLogs.created'])
            ->toArray();
        $this->set('bookingLogs', $bookingLogs);
    }

    public function changeStatus()
    {
        $this->loadModel('Bookings');
        $response = ['success' => false];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $booking = $this->Bookings->get($data['booking_id']);
            $booking = $this->Bookings->patchEntity($booking, ['status' => 1]);
            if ($this->Bookings->save($booking)) {
                $response['success'] = true;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function delete($id = null)
    {
        $this->loadModel('Bookings');
        $this->request->allowMethod(['post', 'delete']);
        $booking = $this->Bookings->get($id);
        if ($this->Bookings->delete($booking)) {
            $this->Flash->success(__('The booking has been deleted.'));
        } else {
            $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index_booking']);
    }

    public function exportFile()
    {

    }

    public function exportExcel()
    {
        $this->loadModel('Bookings');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'file_name' => '', 'link' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $currentYear = $data['year'];
            switch ($data['type']) {
                case HOTEL_REPORT:

                    $this->processHotelReport($currentYear);

                    $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Bao cao khach san nam ' . $currentYear . '.xlsx';
                    $response['success'] = true;
                    $response['link'] = $fileUrl1;
                    $response['file_name'] = 'Bao cao khach san nam ' . $currentYear . '.xlsx';
                    break;
                case AGENCY_REPORT:
                    $this->processSaleReport($currentYear);

                    $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Bao cao so Booking dai ly ' . $currentYear . '.xlsx';
                    $response['success'] = true;
                    $response['link'] = $fileUrl1;
                    $response['file_name'] = 'Bao cao so Booking dai ly ' . $currentYear . '.xlsx';
                    break;
                case PROFIT_REPORT:
                    $this->processProfitReport($currentYear, false);

                    $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Bao cao Doanh thu ' . $currentYear . '.xlsx';
                    $response['success'] = true;
                    $response['link'] = $fileUrl1;
                    $response['file_name'] = 'Bao cao Doanh thu ' . $currentYear . '.xlsx';
                    break;
                case PROFIT_REPORT_LANDTOUR:
                    $this->processProfitReport($currentYear, true);
                    $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Bao cao Doanh thu ' . $currentYear . '.xlsx';
                    $response['success'] = true;
                    $response['link'] = $fileUrl1;
                    $response['file_name'] = 'Bao cao Doanh thu ' . $currentYear . '.xlsx';
                    break;
            }
//            $date = explode(' - ', $data['reservation']);
//            $sDate = $this->Util->formatSQLDate($date[0], 'd/m/Y');
//            $eDate = $this->Util->formatSQLDate($date[1], 'd/m/Y');
//
//            $this->processFile($sDate, $eDate);
//
//            $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Doanh thu MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';
//            $response['success'] = true;
//            $response['link'] = $fileUrl1;
//            $response['file_name'] = 'Doanh thu MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';


            return $this->response->withType("application/json")->withStringBody(json_encode($response));

        }
    }

    private
    function processHotelReport($currentYear)
    {
        $this->loadModel('Bookings');

        $input = WWW_ROOT . "files/exports/template/hotel_report_template.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($input);

        $bookings = $this->Bookings->find()->contain(['Hotels', 'Hotels.Locations', 'BookingRooms'])
            ->where([
                'YEAR(Bookings.complete_date)' => $currentYear,
                'Bookings.type' => HOTEL,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ]);
        $data = [];
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];
//        dd($bookings->toArray());
        foreach ($bookings as $booking) {
            $data[$booking->item_id]['name'] = $booking->hotels->name;
            $data[$booking->item_id]['location'] = $booking->hotels->location->name;
            $data[$booking->item_id]['month'] = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
                12 => 0,
            ];
        }
        foreach ($bookings as $booking) {
            $month = date("n", strtotime($booking->complete_date));
            $totalByBooking = 0;
            if ($booking->booking_rooms) {
                foreach ($booking->booking_rooms as $booking_room) {
                    $date = date_diff($booking_room->end_date, $booking_room->start_date);
                    $totalByBooking += $date->days * $booking_room->num_room;
                }
            } else {
                $date = date_diff($booking->end_date, $booking->start_date);
                $totalByBooking = $date->days * $booking->amount;
            }
            $data[$booking->item_id]['month'][$month] += $totalByBooking;
            $totalByMonth[$month] += $totalByBooking;
        }
        $current_sheet = $spreadsheet->getSheetByName('Sheet1');
//        dd($current_sheet->toArray());
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];
        $cell = $current_sheet->getCellByColumnAndRow(1, 2)->getCoordinate();
        $indexKey = 3;
        $k = 0;
//        dd($data);
        foreach ($data as $singleData) {
            $k++;
            $current_sheet
                ->setCellValue('A' . $indexKey, $k)->getStyle('A' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('B' . $indexKey, $singleData['name'])->getStyle('B' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('C' . $indexKey, $singleData['location'])->getStyle('C' . $indexKey)->getAlignment()->setWrapText(true);
            foreach ($singleData['month'] as $monthKey => $month) {
                $cell = $current_sheet->getCellByColumnAndRow($monthKey + 3, $indexKey)->getCoordinate();
                $current_sheet
                    ->setCellValue($cell, $month)->getStyle($cell)->getAlignment()->setWrapText(true);
            }
            $indexKey++;
        }

        $merge_sum = 'A2:C2';
        $current_sheet->mergeCells($merge_sum)->setCellValue('A2', 'Tổng')
            ->getStyle('A2')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->setCellValue('D2', "=SUM(D:D)")->getStyle('D2')->getNumberFormat();
        $current_sheet->setCellValue('E2', "=SUM(E:E)")->getStyle('E2')->getNumberFormat();
        $current_sheet->setCellValue('F2', "=SUM(F:F)")->getStyle('F2')->getNumberFormat();
        $current_sheet->setCellValue('G2', "=SUM(G:G)")->getStyle('G2')->getNumberFormat();

        $current_sheet->setCellValue('H2', "=SUM(H:H)")->getStyle('H2')->getNumberFormat();

        $current_sheet->setCellValue('I2', "=SUM(I:I)")->getStyle('I2')->getNumberFormat();

        $current_sheet->setCellValue('J2', "=SUM(J:J)")->getStyle('J2')->getNumberFormat();

        $current_sheet->setCellValue('K2', "=SUM(K:K)")->getStyle('K2')->getNumberFormat();

        $current_sheet->setCellValue('L2', "=SUM(L:L)")->getStyle('L2')->getNumberFormat();

        $current_sheet->setCellValue('M2', "=SUM(M:M)")->getStyle('M2')->getNumberFormat();

        $current_sheet->setCellValue('N2', "=SUM(N:N)")->getStyle('N2')->getNumberFormat();

        $current_sheet->setCellValue('O2', "=SUM(O:O)")->getStyle('O2')->getNumberFormat();


        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $current_sheet->getStyle('A2:' . $cell)->applyFromArray($styleArray);
//        dd($current_sheet->toArray());

        $filePath = WWW_ROOT . '/files/exports/Bao cao khach san nam ' . $currentYear . '.xlsx';
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

        return true;
    }

    private
    function processSaleReport($currentYear)
    {
        $this->loadModel('Bookings');
        $this->loadModel('Users');

        $input = WWW_ROOT . "files/exports/template/sale_report_template.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($input);

        $bookings = $this->Bookings->find()
            ->contain([
                'Users',
                'BookingRooms',
                'BookingLandtours'

            ])
            ->where(['year(Bookings.complete_date)' => $currentYear,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ]);
        $totalByMonth = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];

        $sales = $this->Users->find()->contain(['ChildUsers'])->where(['role_id' => 2]);

        $listSale = [];
        foreach ($sales as $sale) {
            array_unshift($sale->child_users, $sale);
            $listSale[$sale->id] = [
                'name' => $sale->screen_name,
                'child' => [
                ]
            ];
            foreach ($sale->child_users as $agency) {
                $child = [
                    'agency_name' => $agency->screen_name,
                    'month' => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                        4 => 0,
                        5 => 0,
                        6 => 0,
                        7 => 0,
                        8 => 0,
                        9 => 0,
                        10 => 0,
                        11 => 0,
                        12 => 0,
                    ]
                ];
                $listSale[$sale->id]['child'][$agency->id] = $child;
            }
        }
        foreach ($bookings as $booking) {
            $month = date('n', strtotime($booking->complete_date));
            $booking->user->parent_id == 0 ? $parentId = $booking->user_id : $parentId = $booking->user->parent_id;
            if ($booking->type == LANDTOUR) {
                if (!$booking->booking_landtour) {
                    $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $booking->amount;
                    $totalByMonth[$month] += $booking->amount;
                } else {
                    $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += ($booking->booking_landtour->num_adult + $booking->booking_landtour->num_children);
                    $totalByMonth[$month] += ($booking->booking_landtour->num_adult + $booking->booking_landtour->num_children);
                }
            } elseif ($booking->type == HOTEL) {
                if (!$booking->booking_rooms) {
                    $dateDiff = date_diff($booking->end_date, $booking->start_date);
                    $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $booking->amount * $dateDiff->days;
                    $totalByMonth[$month] += $booking->amount * $dateDiff->days;
                } else {
                    $totalNight = 0;
                    foreach ($booking->booking_rooms as $bookingRoom) {
                        $dateDiff = date_diff($bookingRoom->end_date, $bookingRoom->start_date);
                        $totalNight += $bookingRoom->num_room * $dateDiff->days;
                    }
                    $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $totalNight;
                    $totalByMonth[$month] += $totalNight;
                }
            } else {
                $dateDiff = date_diff($booking->end_date, $booking->start_date);
                $listSale[$parentId]['child'][$booking->user_id]['month'][$month] += $booking->amount * $dateDiff->days;
                $totalByMonth[$month] += $booking->amount * $dateDiff->days;
            }
        }

        $current_sheet = $spreadsheet->getSheetByName('Sheet1');
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];
        $cell = $current_sheet->getCellByColumnAndRow(1, 2)->getCoordinate();
        $indexKey = 3;
        foreach ($listSale as $singleSale) {
            $mergeSale = 'A' . $indexKey . ':B' . $indexKey;
            $current_sheet->mergeCells($mergeSale)->setCellValue('A' . $indexKey, 'TEAM ' . $singleSale['name'])
                ->getStyle('A' . $indexKey)->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $indexKey++;
            $agencyKey = 0;
            $childIndex = $indexKey;
            foreach ($singleSale['child'] as $agency) {
                $agencyKey++;
                $current_sheet
                    ->setCellValue('A' . $childIndex, $agencyKey)->getStyle('A' . $childIndex)->getAlignment()->setWrapText(true);
                $current_sheet
                    ->setCellValue('B' . $childIndex, $singleSale['name'] == $agency['agency_name'] ? 'Sale ' . $agency['agency_name'] : $agency['agency_name'])->getStyle('B' . $childIndex)->getAlignment()->setWrapText(true);
                foreach ($agency['month'] as $monthKey => $month) {
                    $cell = $current_sheet->getCellByColumnAndRow($monthKey + 2, $childIndex)->getCoordinate();
                    $current_sheet
                        ->setCellValue($cell, $month)->getStyle($cell)->getAlignment()->setWrapText(true);
                }
                $childIndex++;
            }
            $indexKey += count($singleSale['child']);
        }

        $merge_sum = 'A2:B2';
        $current_sheet->mergeCells($merge_sum)->setCellValue('A2', 'Tổng')
            ->getStyle('A2')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);

        $current_sheet->setCellValue('C2', "=SUM(C:C)")->getStyle('C2')->getNumberFormat();

        $current_sheet->setCellValue('D2', "=SUM(D:D)")->getStyle('D2')->getNumberFormat();

        $current_sheet->setCellValue('E2', "=SUM(E:E)")->getStyle('E2')->getNumberFormat();

        $current_sheet->setCellValue('F2', "=SUM(F:F)")->getStyle('F2')->getNumberFormat();

        $current_sheet->setCellValue('G2', "=SUM(G:G)")->getStyle('G2')->getNumberFormat();

        $current_sheet->setCellValue('H2', "=SUM(H:H)")->getStyle('H2')->getNumberFormat();

        $current_sheet->setCellValue('I2', "=SUM(I:I)")->getStyle('I2')->getNumberFormat();

        $current_sheet->setCellValue('J2', "=SUM(J:J)")->getStyle('J2')->getNumberFormat();

        $current_sheet->setCellValue('K2', "=SUM(K:K)")->getStyle('K2')->getNumberFormat();

        $current_sheet->setCellValue('L2', "=SUM(L:L)")->getStyle('L2')->getNumberFormat();

        $current_sheet->setCellValue('M2', "=SUM(M:M)")->getStyle('M2')->getNumberFormat();

        $current_sheet->setCellValue('N2', "=SUM(N:N)")->getStyle('N2')->getNumberFormat();

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $current_sheet->getStyle('A2:' . $cell)->applyFromArray($styleArray);

        $filePath = WWW_ROOT . '/files/exports/Bao cao so Booking dai ly ' . $currentYear . '.xlsx';
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
        return true;
    }

    private function processProfitReport($currentYear, $isLandtour = false)
    {
        $this->loadModel('Bookings');
        $this->loadModel('Users');

        $input = WWW_ROOT . "files/exports/template/profit_report_template.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($input);

        if (!$isLandtour) {
            $condition['Users.role_id'] = 2;
        } else {
            $condition['Users.role_id'] = 5;
        }
        $bookings = $this->Bookings->find()->select([
            'month' => 'month(Bookings.complete_date)',
            'start_date',
            'end_date',
            'amount',
            'sale_id',
            'Users.screen_name',
            'Hotels.name',
            'Hotels.price_agency',
            'Users.parent_id',
            'SUM_SALE_REV' => 'SUM(IF(Bookings.sale_id != Bookings.user_id, Bookings.sale_revenue, 0))',
            'SUM_SALE_CTV_REV' => 'SUM(IF(Bookings.sale_id = Bookings.user_id, Bookings.sale_revenue + Bookings.revenue, 0))'
        ])
            ->contain([
                'Users',
                'Hotels',
                'LandTours',
                'Vouchers',
                'HomeStays'
            ])
            ->where(['year(Bookings.complete_date)' => $currentYear,
                $condition,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ])->group(['month', 'Bookings.sale_id']);

        $listSale = [];
        $sales = $this->Users->find()->where($condition);
        foreach ($sales as $sale) {
            $listSale[$sale->id] = [
                'name' => $sale->screen_name,
                'month' => [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                    9 => 0,
                    10 => 0,
                    11 => 0,
                    12 => 0,
                ]
            ];
        }
        if ($bookings) {
            foreach ($bookings as $booking) {
                if (isset($listSale[$booking->sale_id]['month'][$booking->month])) {
                    $listSale[$booking->sale_id]['month'][$booking->month] += $booking->SUM_SALE_REV + $booking->SUM_SALE_CTV_REV;
                }
            }
        }


        $current_sheet = $spreadsheet->getSheetByName('Sheet1');
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];
        $cell = $current_sheet->getCellByColumnAndRow(1, 2)->getCoordinate();
        $indexKey = 3;
        $keyNumber = 0;
        foreach ($listSale as $singleSale) {
            $keyNumber++;
            $current_sheet->setCellValue('A' . $indexKey, $keyNumber)
                ->getStyle('A' . $indexKey)->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $current_sheet->setCellValue('B' . $indexKey, $singleSale['name'])
                ->getStyle('B' . $indexKey)->getAlignment()->setWrapText(true);
            foreach ($singleSale['month'] as $monthKey => $value) {
                $cell = $current_sheet->getCellByColumnAndRow($monthKey + 2, $indexKey)->getCoordinate();
                $current_sheet->setCellValue($cell, $value)
                    ->getStyle($cell)->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            }
            $indexKey++;
        }

        $merge_sum = 'A2:B2';
        $current_sheet->mergeCells($merge_sum)->setCellValue('A2', 'Tổng')
            ->getStyle('A2')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);

        $current_sheet->setCellValue('C2', "=SUM(C:C)")->getStyle('C2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('D2', "=SUM(D:D)")->getStyle('D2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('E2', "=SUM(E:E)")->getStyle('E2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('F2', "=SUM(F:F)")->getStyle('F2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('G2', "=SUM(G:G)")->getStyle('G2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('H2', "=SUM(H:H)")->getStyle('H2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('I2', "=SUM(I:I)")->getStyle('I2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('J2', "=SUM(J:J)")->getStyle('J2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('K2', "=SUM(K:K)")->getStyle('K2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('L2', "=SUM(L:L)")->getStyle('L2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('M2', "=SUM(M:M)")->getStyle('M2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $current_sheet->setCellValue('N2', "=SUM(N:N)")->getStyle('N2')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $current_sheet->getStyle('A2:' . $cell)->applyFromArray($styleArray);

        $filePath = WWW_ROOT . '/files/exports/Bao cao Doanh thu ' . $currentYear . '.xlsx';
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
        return true;
    }


    public function checkFile($type)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false];
        $location = '';
        $file_name = 'baocaotong_tmp.xlsx';
        $new_file = '';

        $location = WWW_ROOT . '/files/outputs/tmp/donvi/total/';
        $new_file = "Cac bao cao theo dinh muc chi cua don vi " . $this->Util->exportFileDate() . ".xlsx";

        $new_location = WWW_ROOT . '/files/outputs/';
        if (file_exists($location . $file_name)) {
            copy($location . $file_name, $new_location . $new_file);
            unlink($location . $file_name);

            if ($type == 1) {
                $files = glob(WWW_ROOT . '/files/outputs/tmp/donvi/single_report/*'); // get all file names
            } else {
                $files = glob(WWW_ROOT . '/files/outputs/tmp/uyban/single_report/*'); // get all file names
            }
            foreach ($files as $single_file) { // iterate files
                if (is_file($single_file)) {
                    unlink($single_file); // delete file
                }
            }
            $response['success'] = true;
        }

        return $this->response->withType("application/json")->withStringBody(json_encode($response));
    }

    public function bookingView($id = null)
    {
        $this->loadModel('Bookings');
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Hotels', 'Vouchers', 'HomeStays', 'LandTours', 'Hotels.Locations', 'Vouchers.Hotels', 'Vouchers.Hotels.Locations', 'LandTours.Destinations', 'HomeStays.Locations']
        ]);

//        dd($booking);
        $this->set('booking', $booking);
    }

    public function updatePayHotel($id)
    {
        $this->loadModel('Bookings');
        $response = ['success' => false, 'message' => ''];
        if ($this->request->is('ajax')) {
            $pay_hotel = $this->request->getData('payHotel');
            $booking = $this->Bookings->get($id);
            $booking = $this->Bookings->patchEntity($booking, ['pay_hotel' => $pay_hotel]);
            if ($this->Bookings->save($booking)) {
                $response['success'] = true;
            } else {
                $response['message'] = "Đã có lỗi xảy ra";
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;

    }

    public function updateBooking($id)
    {
        $this->loadModel('Payments');
        $this->loadModel('Bookings');
        $booking = $this->Bookings->get($id, ['contain' => ['Users', 'Hotels', 'Vouchers', 'HomeStays', 'LandTours', 'Hotels.Locations', 'Vouchers.Hotels', 'Vouchers.Hotels.Locations', 'LandTours.Destinations', 'HomeStays.Locations', 'BookingRooms', 'BookingLandtours', 'Payments', 'BookingSurcharges']]);
//        $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
        $referer = $this->referer();
        $url_components = parse_url($referer);
        if (isset($url_components['query'])) {
            parse_str($url_components['query'], $indexParams);
        } else {
            $indexParams = [];
        }
        if ($booking->payment) {
            $list_images = $booking->payment->payment_photo;
        } else {
            $list_images = null;
        }
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $indexParams = json_decode($data['indexParams'], true);
            $saveData = [
                'pay_hotel' => $data['pay_hotel'],
                'confirm_agency_pay' => $data['confirm_agency_pay'],
                'payment' => [
                    'payment_photo' => $data['media']
                ],
                'note_for_hotel_payment' => $data['note_for_hotel_payment']
            ];
            $booking = $this->Bookings->patchEntity($booking, $saveData);
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));
                $this->redirect(array('controller' => 'dashboards', 'action' => 'payBookingHotel', 'prefix' => 'admin', '?' => $indexParams));
            }
        }
        $this->set(compact('list_images', 'booking', 'indexParams'));
    }

    public function exportFileCtv()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->loadModel('Users');
        $listSales = $this->Users->find()->where(['role_id' => 2]);
        $this->set(compact('listSales'));
    }

    public function processSaleListCTV()
    {
        $this->loadModel('Users');
        $saleId = $this->request->getQuery('sale_id');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'file_name' => '', 'link' => ''];
        if ($saleId == 0) {
            $sale = null;
        } else {
            $sale = $this->Users->get($saleId);
        }
        if ($sale) {
            $name = $sale->screen_name;
        } else {
            $name = "tat ca Sale";
        }
        if ($this->request->is('ajax')) {
            $this->genListCtv($saleId);
            $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Danh sach Dai ly cua ' . $name . '.xlsx';
            $response['success'] = true;
            $response['link'] = $fileUrl1;
            $response['file_name'] = 'Danh sach Dai ly cua ' . $name . '.xlsx';
            return $this->response->withType("application/json")->withStringBody(json_encode($response));

        }
    }

    private function genListCtv($sale_id)
    {
        $this->loadModel('Users');
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $current_sheet = $spreadsheet->getActiveSheet();
        $condition = [];
        $condition['role_id'] = 2;
        if ($sale_id != 0) {
            $condition['id'] = $sale_id;
            $singleSale = $this->Users->get($sale_id);
            $name = $singleSale->screen_name;
        } else {
            $name = "tat ca Sale";
        }
        $listSales = $this->Users->find()->contain(['ChildUsers'])->where($condition);
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];
//        $current_sheet->mergeCells($mergeHeader)->setCellValue('A1', 'Danh sách Đại lý của ' . $currentSale['screen_name'])
//            ->getStyle('A1')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
//        $current_sheet->getStyle('A2')->getAlignment()->setHorizontal('center')->setWrapText(true);
//        $current_sheet->getStyle('B2')->getAlignment()->setHorizontal('center')->setWrapText(true);
//        $current_sheet->getStyle('C2')->getAlignment()->setHorizontal('center')->setWrapText(true);
//        $current_sheet->getStyle('D2')->getAlignment()->setHorizontal('center')->setWrapText(true);
//        $current_sheet->getStyle('E2')->getAlignment()->setHorizontal('center')->setWrapText(true);
        $arrayData = [];
        $majorKey = 1;
        foreach ($listSales as $k => $sale) {
            $arrayData[] = [
                'Danh sách Đại lý của ' . $sale->screen_name
            ];
            $mergeHeader = 'A' . $majorKey . ':E' . $majorKey;
            $current_sheet->mergeCells($mergeHeader)->getStyle('A' . $majorKey)->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $majorKey++;
            $arrayData[] = [
                'STT', 'Tên hiển thị', 'Email', 'SĐT', 'Zalo'
            ];
            $current_sheet->getStyle('A' . $majorKey)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $current_sheet->getStyle('B' . $majorKey)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $current_sheet->getStyle('C' . $majorKey)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $current_sheet->getStyle('D' . $majorKey)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $current_sheet->getStyle('E' . $majorKey)->getAlignment()->setHorizontal('center')->setWrapText(true);
            $majorKey++;
            foreach ($sale->child_users as $key => $ctv) {
                $arrayData[] = [
                    $key + 1, $ctv->screen_name, $ctv->email, $ctv->phone, $ctv->zalo
                ];
                $majorKey++;
            }
            $arrayData[] = [];
            $majorKey++;
        }
        $current_sheet->fromArray($arrayData, NULL, 'A1');
        $current_sheet->getColumnDimension('B')->setWidth(40)->setAutoSize(true);
        $current_sheet->getColumnDimension('C')->setWidth(40)->setAutoSize(true);
        $current_sheet->getColumnDimension('D')->setWidth(20)->setAutoSize(true);
        $current_sheet->getColumnDimension('E')->setWidth(20)->setAutoSize(true);
//        dd($arrayData);
//        $current_sheet
//            ->setCellValue('A' . $indexKey, $key + 1)->getStyle('A' . $indexKey)->getAlignment()->setWrapText(true);
//        $indexKey++;

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $current_sheet->getStyle('A1:E' . $majorKey)->applyFromArray($styleArray);

        $filePath = WWW_ROOT . '/files/exports/Danh sach Dai ly cua ' . $name . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

        return true;
    }

    public function exportBookingRoom()
    {
        $this->loadModel('Bookings');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'file_name' => '', 'link' => ''];

        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $agencyPay = null;
            $payHotel = null;
            $confirmAgencyPay = null;
            $date = null;
            $sDate = date('d/m/Y');
            $eDate = date('d/m/Y');
            $keyword = '';
            $condition = [];
            if (isset($data['agency_pay']) && $data['agency_pay'] != null) {
                $condition['agency_pay'] = $data['agency_pay'];
                $agencyPay = $data['agency_pay'];
            }
            if (isset($data['pay_hotel']) && $data['pay_hotel'] != null) {
                $condition['pay_hotel'] = $data['pay_hotel'];
                $payHotel = $data['pay_hotel'];
            }
            if (isset($data['confirm_agency_pay']) && $data['confirm_agency_pay'] != null) {
                $condition['confirm_agency_pay'] = $data['confirm_agency_pay'];
                $confirmAgencyPay = $data['confirm_agency_pay'];
            }
            if (isset($data['start_date']) && $data['start_date'] != null) {
                $condition['Bookings.created >='] = $this->Util->formatSQLDate($data['start_date'], 'd/m/Y');
                $sDate = $data['start_date'];
            }
            if (isset($data['end_date']) && $data['end_date'] != null) {
                $condition['Bookings.created <='] = $this->Util->formatSQLDate($data['end_date'], 'd/m/Y');
                $eDate = $data['end_date'];
            }
            $condition['Bookings.status IN'] = [0, 1, 2, 3, 4, 5];
            $condition['type !='] = LANDTOUR;
            if (isset($data['search'])) {
                $keyword = $data['search'];
                $condition[] = [
                    'OR' => [
                        'code LIKE' => '%' . $keyword . '%',
                        'Users.screen_name LIKE' => '%' . $keyword . '%',
                        'Users.username LIKE' => '%' . $keyword . '%',
                        'Hotels.name LIKE' => '%' . $keyword . '%',
                        'Vouchers.name LIKE' => '%' . $keyword . '%',
                        'HomeStays.name LIKE' => '%' . $keyword . '%',
                        'full_name LIKE' => '%' . $keyword . '%',
                        'hotel_code LIKE' => '%' . $keyword . '%'
                    ]
                ];
            }
            $bookings = $this->Bookings->find()->contain(['Users',
                'Hotels',
                'Hotels.Locations',
                'Vouchers',
                'Vouchers.Hotels',
                'Vouchers.Hotels.Locations',
                'HomeStays',
                'HomeStays.Locations',
                'BookingSurcharges',
                'BookingRooms'
            ])->where($condition)->order(['Bookings.created' => 'DESC',]);

            $this->processBookingRoomFile($bookings);

            $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Danh sach booking phong MustGo' . '.xlsx';
            $response['success'] = true;
            $response['link'] = $fileUrl1;
            $response['file_name'] = 'Danh sach booking phong MustGo' . '.xlsx';


            return $this->response->withType("application/json")->withStringBody(json_encode($response));

        }
    }

    private function processBookingRoomFile($bookings)
    {
        $input = WWW_ROOT . "files/exports/template/template_export_room.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($input);

        $current_sheet = $spreadsheet->getSheetByName('Sheet1');
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];


        $indexKey = 4;
        foreach ($bookings as $key => $booking) {
            if ($booking->start_date && $booking->end_date) {
                $days_attended = date_diff($booking->start_date, $booking->end_date);
                $booking->days_attended = $days_attended;
            } else {
                $booking->days_attended = null;
            }
            $default_price = 0;
            $sell_price = 0;
            $revenue = 0;
            $totalPrice = 0;
            $status = '';
            switch ($booking->status) {
                case 0:
                    $status = 'Đại lý mới đặt';
                    break;
                case 1:
                    $status = 'Chờ KS mail XN, gửi mail XN và ĐNTT';
                    break;
                case 2:
                    $status = $booking->agency_pay == 1 ? 'ĐL đã TT, chờ KT TT' : 'Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán';
                    break;
                case 3:
                    $status = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? 'Hoàn thành' : 'Hoàn thành';
                    break;
                case 4:
                    $status = 'Hoàn thành';
                    break;
                case 5:
                    $status = 'Đã hủy';
                    break;
            }
            $totalSurchargePrice = 0;
            foreach ($booking->booking_surcharges as $surcharge) {
                $totalSurchargePrice += $surcharge->price;
            }
            $totalPrice = $booking->price +
                ($booking->adult_fee ? $booking->adult_fee : 0)
                + ($booking->children_fee ? $booking->children_fee : 0)
                + ($booking->holiday_fee ? $booking->holiday_fee : 0)
                + ($booking->other_fee ? $booking->other_fee : 0)
                + $totalSurchargePrice;

            //default price
            $default_price = $totalPrice - $booking->sale_revenue - $booking->revenue;

            //sell price
            if ($booking->sale_id != $booking->user_id) {
                $sell_price = $totalPrice - $booking->revenue;
            } else {
                $sell_price = $totalPrice;
            }

            //revenue
            if ($booking->sale_id != $booking->user_id) {
                $revenue = $booking->sale_revenue;
            } else {
                $revenue = $booking->sale_revenue + $booking->revenue;
            }
            $obj_name = $booking->hotels->name;
            $payment_infomation = '';
            $type = 'Khách sạn';
            $current_sheet
                ->setCellValue('A' . $indexKey, $booking->complete_date ? date('d/m/y', strtotime($booking->complete_date)) : '')->getStyle('A' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('B' . $indexKey, $booking->created ? date('d/m/y', strtotime($booking->created)) : '')->getStyle('B' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('C' . $indexKey, $booking->user ? $booking->user->screen_name : 'Khách lẻ')->getStyle('C' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('D' . $indexKey, $obj_name)->getStyle('D' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('E' . $indexKey, $type)->getStyle('E' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('F' . $indexKey, $booking->full_name)->getStyle('F' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('G' . $indexKey, $booking->amount)->getStyle('G' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('H' . $indexKey, $booking->days_attended ? $days_attended->days : 0)->getStyle('H' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('I' . $indexKey, date('d/m/y', strtotime($booking->start_date)))->getStyle('I' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('J' . $indexKey, date('d/m/y', strtotime($booking->end_date)))->getStyle('J' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('K' . $indexKey, $status)->getStyle('K' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet->setCellValue('L' . $indexKey, $default_price)->getStyle('L' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet->setCellValue('M' . $indexKey, $sell_price)->getStyle('M' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet->setCellValue('N' . $indexKey, $revenue)->getStyle('N' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet
                ->setCellValue('O' . $indexKey, "Cọc " . ($booking->customer_deposit / 1000000) . "tr")->getStyle('O' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('P' . $indexKey, ($booking->agency_pay == 1) ? "Rồi" : "Chưa")->getStyle('P' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('Q' . $indexKey, ($booking->pay_hotel == 1) ? "Rồi" : "Chưa")->getStyle('Q' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('R' . $indexKey, $payment_infomation)->getStyle('R' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('S' . $indexKey, "")->getStyle('S' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('T' . $indexKey, $booking->code)->getStyle('T' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('U' . $indexKey, $booking->hotel_code)->getStyle('U' . $indexKey)->getAlignment()->setWrapText(true);
            $indexKey++;
        }

        $merge_sum = 'A3:K3';
        $current_sheet->mergeCells($merge_sum)->setCellValue('A3', 'Tổng')
            ->getStyle('A3')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->setCellValue('L3', "=SUM(L:L)")->getStyle('L3')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $current_sheet->setCellValue('M3', "=SUM(M:M)")->getStyle('M3')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $current_sheet->setCellValue('N3', "=SUM(N:N)")->getStyle('N3')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $current_sheet->getStyle('A3:R' . $indexKey)->applyFromArray($styleArray);
//        dd($current_sheet->toArray());

        $filePath = WWW_ROOT . '/files/exports/Danh sach booking phong MustGo' . '.xlsx';
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

        return true;
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && $user['role_id'] === 1) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'admin'));
        return parent::isAuthorized($user);
    }

    public function saveCommentLogs() {
        $this->loadModel('BookingLogs');
        $lData = $this->request->getQuery();
        $userLogs = $this->Auth->user();
        $title = [];
//        dd($lData);
        switch (intval($lData['title'])){
            case 1:
                $title = 'Comment Booking';
                break;
        }
        if(isset($lData['cmt'])){
            // create log booking
            $dataLog = [];
            $dataLog['user_id'] = $userLogs['id'];
            $dataLog['booking_id'] = intval($lData['id']);
            $dataLog['code'] = $lData['code'];
            $dataLog['title'] = $title;
            $dataLog['comment'] = $lData['cmt'];
            $dataLog['type'] = 1;
            $dataLog['status'] = 1;
//            dd($dataLog);
            $bookingLogs = $this->BookingLogs->newEntity();
            $bookingLogs = $this->BookingLogs->patchEntity($bookingLogs, $dataLog);
            $this->BookingLogs->save($bookingLogs);
            // end create log booking
            return $this->render('view');
        }
    }

}
