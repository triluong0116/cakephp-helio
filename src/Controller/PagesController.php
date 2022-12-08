<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Utility\Hash;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 * @property \App\Model\Table\LocationsTable $Locations
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HomeStaysTable $HomeStays
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\HotelSearchsTable $HotelSearchs
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could notoc
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function home()
    {
        $this->loadModel('Locations');
        $title = 'MustGo';
        $locations = $this->Locations->find()->order(['is_featured' => 'DESC'])->limit(9)->toArray();
        $countLocations = count($locations);
//        dd($countLocations);
        $this->set(compact('title', 'locations', 'countLocations'));
    }

    public function location()
    {
        $this->loadModel('Locations');
        $locationIds = $this->getRequest()->getQuery('locationIds');
        $condition = [];
        if ($locationIds) {
            $arrLocationIds = explode(',', $locationIds);
            $condition['id IN'] = $arrLocationIds;
        }
        $locations = $this->Locations->find()->where($condition)->toArray();
        $title = 'Tất cả điểm đến';
        $headerType = 1;
        $this->set(compact('headerType', 'locations', 'title'));
    }

    public function search()
    {
        $this->loadModel('Locations');
        $this->loadModel('Hotels');
        $this->loadModel('HotelSearchs');
        $this->loadModel('HomeStays');
        $this->loadModel('LandTours');
        $this->loadModel('Vouchers');
        $this->loadModel('Vinrooms');

        $testUrl = $this->viewVars['testUrl'];
        $keyword = $this->getRequest()->getQuery('keyword');
        $type = $this->getRequest()->getQuery('search_type');
        if (!$type) {
            $locations = $this->Locations->find()->where(['name LIKE ' => '%' . $keyword . '%'])->extract('id')->toArray();

            if ($locations) {
                $locationIds = implode(',', $locations);
                return $this->redirect(\Cake\Routing\Router::url(['_name' => 'location.all', 'locationIds' => $locationIds], true));
            }
            $today = date('Y-m-d');
            $hotels = $this->HotelSearchs->find()->where(['name LIKE' => '%' . $keyword . '%', 'single_day' => $today])->toArray();
            if ($hotels) {
                $hotelIds = Hash::extract($hotels, '{n}.id');
            } else {
                $hotelIds[] = 0;
            }
            $missinHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'is_vinhms' => 0, 'Hotels.name LIKE' => '%' . $keyword . '%'])->toArray();
            foreach ($missinHotels as $key => $item) {
                $item->price_day = 0;
                if ($item->location) {
                    $item->location_name = $item->location->name;
                } else {
                    $item->location_name = '';
                }
                $hotels[] = $item;
            }

            $vinHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'is_vinhms' => 1, 'Hotels.name LIKE' => '%' . $keyword . '%'])->toArray();
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
                $hotels[] = $item;
            }

            $homestays = $this->HomeStays->find()->contain(['Locations', 'PriceHomeStays'])->where(['HomeStays.name LIKE' => '%' . $keyword . '%'])->toArray();

            if ($this->Auth->user('role_id') == 3) {
                $agencyUserId = $this->Auth->user('id');
            } else {
                $agencyUserId = 0;
            }
            $landtours = $this->LandTours->find()->contain(['Destinations', 'Departures', 'LandTourAccessories', 'LandTourUserPrices' => function ($q) use ($agencyUserId) {
                return $q->where(['user_id' => $agencyUserId]);
            }])->where(['LandTours.name LIKE' => '%' . $keyword . '%'])->toArray();
            foreach ($landtours as $k => $landtour) {
                if (count($landtour['land_tour_user_prices']) > 0) {
                    $landtours[$k]->totalPrice = $landtour->price + $landtour->trippal_price + $landtour['land_tour_user_prices'][0]['price'];
                } else {
                    $landtours[$k]->totalPrice = $landtour->price + $landtour->trippal_price + $landtour->customer_price;
                }
                if (count($landtour['land_tour_accessories']) > 0) {
                    foreach ($landtour['land_tour_accessories'] as $accessory) {
                        $landtours[$k]->totalPrice += $accessory->adult_price;
                    }
                }
            }
            $vouchers = $this->Vouchers->find()->contain(['Departures', 'Destinations', 'Hotels'])->where(['Vouchers.name LIKE' => '%' . $keyword . '%'])->toArray();
        } else {
            switch ($type) {
                case HOTEL:
                    $today = date('Y-m-d');
                    $hotels = $this->HotelSearchs->find()->where(['name LIKE' => '%' . $keyword . '%', 'single_day' => $today])->toArray();
                    if ($hotels) {
                        $hotelIds = Hash::extract($hotels, '{n}.id');
                    } else {
                        $hotelIds[] = 0;
                    }
                    $missinHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id NOT IN' => $hotelIds, 'Hotels.name LIKE' => '%' . $keyword . '%'])->toArray();
                    foreach ($missinHotels as $key => $item) {
                        $item->price_day = 0;
                        if ($item->location) {
                            $item->location_name = $item->location->name;
                        } else {
                            $item->location_name = '';
                        }
                        $hotels[] = $item;
                    }
                    break;
                case HOMESTAY:
                    $homestays = $this->HomeStays->find()->contain(['Locations', 'PriceHomeStays'])->where(['HomeStays.name LIKE' => '%' . $keyword . '%'])->toArray();
                    break;
                case LANDTOUR:
                    if ($this->Auth->user('role_id') == 3) {
                        $agencyUserId = $this->Auth->user('id');
                    } else {
                        $agencyUserId = 0;
                    }
                    $landtours = $this->LandTours->find()->contain(['Destinations', 'Departures', 'LandTourAccessories', 'LandTourUserPrices' => function ($q) use ($agencyUserId) {
                        return $q->where(['user_id' => $agencyUserId]);
                    }])->where(['LandTours.name LIKE' => '%' . $keyword . '%'])->toArray();
                    foreach ($landtours as $k => $landtour) {
                        if (count($landtour['land_tour_user_prices']) > 0) {
                            $landtours[$k]->totalPrice = $landtour->price + $landtour->customer_price + $landtour['land_tour_user_prices'][0]['price'];
                        } else {
                            $landtours[$k]->totalPrice = $landtour->price + $landtour->trippal_price + $landtour->customer_price;
                        }
                        if (count($landtour['land_tour_accessories']) > 0) {
                            foreach ($landtour['land_tour_accessories'] as $accessory) {
                                $landtours[$k]->totalPrice += $accessory->adult_price;
                            }
                        }
                    }
                    break;
                case VOUCHER:
                    $vouchers = $this->Vouchers->find()->contain(['Departures', 'Destinations', 'Hotels'])->where(['Vouchers.name LIKE' => '%' . $keyword . '%'])->toArray();
                    break;
            }
        }

        if (isset($hotels) && !empty($hotels)) {
            $locationIds = Hash::extract($hotels, '{n}.location_id');
            $hotelIds = Hash::extract($hotels, '{n}.id');
            $maxPrice = 0;
            $minPrice = 0;
            foreach ($hotels as $hotel) {
                if ($hotel->price_day - 1000000 > $minPrice) {
                    $minPrice = $hotel->price_day;
                }
                if ($hotel->price_day > $maxPrice) {
                    $maxPrice = $hotel->price_day;
                }

            }

            $tmpHotels = $this->HotelSearchs->find()
                ->where(['id NOT IN' => $hotelIds, 'single_day' => $today, 'price_day <=' => $maxPrice, 'price_day >=' => $minPrice])->limit(4)->toArray();
            $samePriceHotels = $tmpHotels;
//            foreach ($tmpHotels as $tmpHotel) {
//                if ($tmpHotel->price_day <= $maxPrice && $tmpHotel->price_day >= $minPrice) {
//                    $samePriceHotels[] = $tmpHotel;
//                }
//            }
//            $samePriceHotels = array_slice($samePriceHotels, 0, 4);
            if ($locationIds) {
                $sameLocationHotels = $this->HotelSearchs->find()
                    ->where(['location_id IN' => $locationIds, 'id NOT IN' => $hotelIds, 'single_day' => $today])->limit(4);
            } else {
                $sameLocationHotels = [];
            }
            $this->set(compact('hotels', 'samePriceHotels', 'sameLocationHotels'));
        }
        if (isset($homestays) && !empty($homestays)) {
            $locationIds = Hash::extract($homestays, '{n}.location_id');
            $homestayIds = Hash::extract($homestays, '{n}.id');
            $maxPrice = 0;
            $minPrice = 0;
            $today = date('d-m-Y');
            foreach ($homestays as $homestay) {
                if ($homestay->price_home_stays) {
                    $resPrice = $this->Util->countingHomeStayPrice($today, $homestay);
                    $price = $resPrice;
                    if ($price - 1000000 > $minPrice) {
                        $minPrice = $price;
                    }
                    if ($price > $maxPrice) {
                        $maxPrice = $price;
                    }
                }
            }

            $tmpHomestays = $this->HomeStays->find()->contain(['Locations', 'PriceHomeStays'])->where(['HomeStays.id NOT IN' => $homestayIds]);
            $samePriceHomestays = [];
            foreach ($tmpHomestays as $tmpHomestay) {
                if ($homestay->price_home_stays) {
                    $resPrice = $this->Util->countingHomeStayPrice($today, $homestay);
                    $price = $resPrice;
                    if ($price <= $maxPrice && $price >= $minPrice) {
                        $samePriceHomestays[] = $tmpHomestay;
                    }
                }
            }
            $samePriceHomestays = array_slice($samePriceHomestays, 0, 4);

            if ($locationIds) {
                $sameLocationHomestays = $this->HomeStays->find()->contain(['Locations', 'PriceHomeStays'])
                    ->where(['HomeStays.location_id IN' => $locationIds, 'HomeStays.id NOT IN' => $homestayIds])->limit(4);
            } else {
                $sameLocationHomestays = [];
            }
            $this->set(compact('homestays', 'samePriceHomestays', 'sameLocationHomestays'));
        }
        if (isset($landtours) && !empty($landtours)) {
            $locationIds = Hash::extract($landtours, '{n}.destination_id');
            $landtourIds = Hash::extract($landtours, '{n}.id');
            $maxPrice = 0;
            $minPrice = 0;
            foreach ($landtours as $landtour) {
                $price = $landtour->totalPrice;
                if ($price - 1000000 > $minPrice) {
                    $minPrice = $price;
                }
                if ($price > $maxPrice) {
                    $maxPrice = $price;
                }
            }
            $samePriceLandtours = [];
            if ($this->Auth->user('role_id') == 3) {
                $agencyUserId = $this->Auth->user('id');
            } else {
                $agencyUserId = 0;
            }
            $tmpLandTours = $this->LandTours->find()->contain(['Destinations', 'Departures', 'LandTourAccessories', 'LandTourUserPrices' => function ($q) use ($agencyUserId) {
                return $q->where(['user_id' => $agencyUserId]);
            }])->where(['LandTours.id NOT IN' => $landtourIds])->toArray();
            foreach ($tmpLandTours as $k => $landtour) {
                if (count($landtour['land_tour_user_prices']) > 0) {
                    $tmpLandTours[$k]->totalPrice = $landtour->price + $landtour->trippal_price + $landtour['land_tour_user_prices'][0]['price'];
                } else {
                    $tmpLandTours[$k]->totalPrice = $landtour->price + $landtour->trippal_price + $landtour->customer_price;
                }
                if (count($landtour['land_tour_accessories']) > 0) {
                    foreach ($landtour['land_tour_accessories'] as $accessory) {
                        $tmpLandTours[$k]->totalPrice += $accessory->adult_price;
                    }
                }
                if ($tmpLandTours[$k]->totalPrice >= $minPrice && $tmpLandTours[$k]->totalPrice <= $maxPrice) {
                    $samePriceLandtours[] = $tmpLandTours[$k];
                }
            }
            $samePriceLandtours = array_slice($samePriceLandtours, 0, 4);

            if ($locationIds) {
                $sameLocationLandtours = $this->LandTours->find()->contain(['Destinations', 'Departures', 'LandTourAccessories', 'LandTourUserPrices' => function ($q) use ($agencyUserId) {
                    return $q->where(['user_id' => $agencyUserId]);
                }])
                    ->where(['LandTours.destination_id IN' => $locationIds, 'LandTours.id NOT IN' => $landtourIds])->limit(4)->toArray();
                foreach ($sameLocationLandtours as $k => $landtour) {
                    if (count($landtour['land_tour_user_prices']) > 0) {
                        $sameLocationLandtours[$k]->totalPrice = $landtour->price + $landtour->trippal_price + $landtour['land_tour_user_prices'][0]['price'];
                    } else {
                        $sameLocationLandtours[$k]->totalPrice = $landtour->price + $landtour->trippal_price + $landtour->customer_price;
                    }
                    if (count($landtour['land_tour_accessories']) > 0) {
                        foreach ($landtour['land_tour_accessories'] as $accessory) {
                            $sameLocationLandtours[$k]->totalPrice += $accessory->adult_price;
                        }
                    }
                }
            } else {
                $sameLocationLandtours = [];
            }
            $this->set(compact('landtours', 'samePriceLandtours', 'sameLocationLandtours'));
        }
        if (isset($vouchers) && !empty($vouchers)) {
            $locationIds = Hash::extract($vouchers, '{n}.location_id');
            $voucherIds = Hash::extract($vouchers, '{n}.id');
            $maxPrice = 0;
            $minPrice = 0;
            foreach ($vouchers as $voucher) {
                $price = $voucher->price + $voucher->trippal_price + $voucher->customer_price;
                if ($price - 1000000 > $minPrice) {
                    $minPrice = $price;
                }
                if ($price > $maxPrice) {
                    $maxPrice = $price;
                }
            }

            $samePriceVouchers = [];
            $tmpVouchers = $this->Vouchers->find()->contain(['Departures', 'Destinations', 'Hotels'])->where(['Vouchers.id NOT IN' => $voucherIds])->toArray();
            foreach ($tmpVouchers as $tmpVoucher) {
                $price = $tmpVoucher->price + $tmpVoucher->trippal_price + $tmpVoucher->customer_price;
                if ($price >= $minPrice && $price <= $maxPrice) {
                    $samePriceVouchers[] = $tmpVoucher;
                }
            }
            $samePriceVouchers = array_slice($samePriceVouchers, 0, 4);

            if ($locationIds) {
                $sameLocationVouchers = $this->Vouchers->find()->contain(['Departures', 'Destinations', 'Hotels'])
                    ->where(['Vouchers.location_id IN' => $locationIds, 'Vouchers.id NOT IN' => $voucherIds])->limit(4);
            } else {
                $sameLocationVouchers = [];
            }
            $this->set(compact('vouchers', 'samePriceVouchers', 'sameLocationVouchers'));
        }


        $headerType = 1;
        $title = 'Tìm kiếm: ' . $keyword;

        $this->set(compact('headerType', 'title', 'keyword'));

    }

    public function reportByMonth()
    {
        $this->loadModel('Bookings');
        $bookings = $this->Bookings->find()->select(['item_id', 'month' => 'month(Bookings.created)', 'total' => 'count(*)'])
            ->contain([
                'Hotels' => function ($query) {
                    return $query->select(['name']);
                }
            ])
            ->where(['year(Bookings.created)' => 2019])->group(['item_id', 'month']);
        $data = [];
        foreach ($bookings as $booking) {

            if (!isset($data[$booking->item_id])) {
                $data[$booking->item_id]['name'] = $booking->hotels->name;
                $month = [
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
                $data[$booking->item_id]['month'] = $month;
            }
            $data[$booking->item_id]['month'][$booking->month] = $booking->total;
        }
        dd($data);
        die;
    }

    public function flightSearch()
    {
        $data = $this->request->getQuery();
        if (isset($data['isApp']) && !empty($data['isApp'])) {
            $this->viewBuilder()->enableAutoLayout(false);
        } else {
            $this->viewBuilder()->setLayout('transport');
        }
        $title = 'Hệ thống đặt vé MUSTGO';
        $this->set(compact('title'));
    }

    public function flightSearchSimple()
    {
        $this->viewBuilder()->autoLayout(false);
        $title = 'Hệ thống đặt vé MUSTGO';
        $this->set(compact('title'));
    }

    public function flightSearchResult()
    {
        $referer = $this->referer();
        $url_components = parse_url($referer);
        parse_str($url_components['query'], $params);
        if (isset($params['isApp']) && $params['isApp']) {
            $this->viewBuilder()->enableAutoLayout(false);
        } else {
            $this->viewBuilder()->setLayout('transport');
        }
        $title = 'Hệ thống đặt vé MUSTGO';
        $this->set(compact('title'));
    }

    public function flightSearchResultSimple()
    {
        $this->viewBuilder()->autoLayout(false);
        $title = 'Hệ thống đặt vé MUSTGO';
        $this->set(compact('title'));
    }

    public function carSearch()
    {
        $data = $this->request->getQuery();
        if (isset($data['isApp']) && !empty($data['isApp'])) {
            $this->viewBuilder()->enableAutoLayout(false);
        } else {
            $this->viewBuilder()->setLayout('transport');
        }
        $title = 'Thuê xe MUSTGO';
        $this->set(compact('title'));
    }

    public function carSearchSimple()
    {
        $this->viewBuilder()->autoLayout(false);
        $title = 'Thuê xe MUSTGO simple';
        $this->set(compact('title'));
    }

    public function carSearchResult()
    {
        $this->viewBuilder()->setLayout('transport');
        $title = 'Thuê xe MUSTGO';
        $this->set(compact('title'));
    }

    public function searchSuggest()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Hotels');
        $this->loadModel('HotelSearchs');
        $this->loadModel('LandTours');
        $this->loadModel('HomeStays');
        $this->loadModel('Vouchers');
        $this->loadModel('Locations');
        if ($this->request->is('ajax')) {
            $keyword = $this->request->getQuery('keyword');
            $type = $this->request->getQuery('type');
            $results = [];
            switch ($type) {
                case HOTEL:
                    $listHotels = $this->Hotels->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    break;
                case HOMESTAY:
                    $listHomeStays = $this->HomeStays->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    break;
                case LANDTOUR:
                    $listLandTours = $this->LandTours->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    break;
                case VOUCHER:
                    $listVouchers = $this->Vouchers->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    break;
                case 'all':
                    $listHotels = $this->Hotels->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    $listHomeStays = $this->HomeStays->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    $listLandTours = $this->LandTours->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    $listVouchers = $this->Vouchers->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
                    break;
            }
//            $listLocations = $this->Locations->find()->where(['name LIKE' => '%' . $keyword . '%'])->limit(3)->toArray();
            if (isset($listHotels)) {
                $results['hotel'] = $listHotels;
            }
            if (isset($listHomeStays)) {
                $results['homestay'] = $listHomeStays;
            }
            if (isset($listLandTours)) {
                $results['landtour'] = $listLandTours;
            }
            if (isset($listVouchers)) {
                $results['voucher'] = $listVouchers;
            }
            if (isset($listLocations)) {
                $results['voucher'] = $listLocations;
            }
//            if($listLocations){
//                $result[] = $listLocations;
//            }
            $this->set(compact('results', 'keyword', 'type'));
            $this->render('search_suggest')->getBody();
        }
    }

    public function searchSuggestVin()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Hotels');
        $this->loadModel('Locations');
        if ($this->request->is('ajax')) {
            $keyword = $this->request->getQuery('keyword');
            $results = [];
            $listLocations = $this->Locations->find()->where(['name LIKE' => '%' . $keyword . '%'])->toArray();
            $listHotels = $this->Hotels->find()->where(['name LIKE' => '%' . $keyword . '%', 'is_vinhms' => 1, 'vinhms_code !=' => ''])->toArray();
            $results['locations'] = $listLocations;
            $results['hotels'] = $listHotels;
            $this->set(compact('results', 'keyword'));
            $this->render('search_suggest_vin')->getBody();
        }
    }

    public function inputRoomVin()
    {
        $keyword = $this->request->getQuery('room_number');
        $this->set(compact('keyword'));
        $this->render('input_room_vin')->getBody();
    }

    public function depositCash()
    {
        $this->loadModel('Configs');
        $title = 'Nạp tiền';
        $headerType = 1;
        $code = $this->Util->generateRandomString(8);
        $transferAgencyInfor = $this->Configs->find()->where(['type' => 'bank-recharge-infor'])->first();
        $bank_accounts = json_decode($transferAgencyInfor->value, true);
        $this->set(compact('title', 'headerType', 'code','bank_accounts'));
    }

    public function recharge()
    {
        $this->loadModel('DepositLogs');
        $data_request = $this->request['data'];
        $messege = 'error';
        $response = ['success' => false, 'message' => $messege, 'errors' => []];
        if (!$data_request['title']) {
            $response['errors']['title'] = [0 => 'Bạn chưa nhập tiêu đề'];
        } else if (!$data_request['amount']) {
            $response['errors']['amount'] = [0 => 'Bạn chưa nhập số tiền nạp'];
        } else if (!is_numeric(str_replace(',', '', $data_request['amount']))) {
            $response['errors']['amount'] = [0 => 'Bạn nhập không đúng fomat. Cần nhập số'];
        } else if ($data_request['image'] == "undefined") {
            $response['errors']['image'] = [0 => 'Bạn chưa tải ảnh lên'];
        }  else {
            $messege = 'success';
            $data['user_id'] = $this->Auth->user('id');
            $data['creator_id'] = $this->Auth->user('id');
            $data['title'] = $data_request['title'] ? $data_request['title'] : '';
            $data['message'] = $data_request['message'] ? $data_request['message'] : '';
            $data['amount'] = $data_request['amount'] ? str_replace(',', '', $data_request['amount']) : '';
            $data['balance'] = 0;
            $data['images'] = $data_request['image'] ? $data_request['image'] : '';
            $data['type'] = 1;
            $data['status'] = 2;
            $check_hack = $this->DepositLogs->find()->where(['message' => $data['message']])->first();
            if ($check_hack) {
                $response['errors']['message'] = [0 => 'Bị trùng code nội dung chuyển khoản ? Bạn đang cố hack hệ thống hay do hệ thống lỗi  ?? Hãy thử lại'];
            } else {
                $deposit_log = $this->DepositLogs->newEntity();
                $deposit_log = $this->DepositLogs->patchEntity($deposit_log, $data);
                $this->DepositLogs->save($deposit_log);
                $deposit_log = $this->DepositLogs->patchEntity($deposit_log, ['code' => "MNT" . str_pad($deposit_log->id, 9, '0', STR_PAD_LEFT)]);
                $this->DepositLogs->save($deposit_log);
                $response = ['success' => true, 'message' => $messege, 'errors' => []];
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function listRecharge()
    {
        $dataRequest = $this->request->getQuery();
        $title = 'Quản lý giao dịch';
        $headerType = 1;
        $this->paginate = [
            'limit' => 10];
        $this->loadModel('DepositLogs');
        $this->loadModel('Users');
        $condition = [];
        if ($dataRequest) {
            if (isset($dataRequest['typeSelect']) && $dataRequest['typeSelect'] != 3) {
                $condition['type'] =  $dataRequest['typeSelect'];
            } else {
                $dataRequest['typeSelect'] = 3;
            }
            if (isset($dataRequest['statusSelect']) && $dataRequest['statusSelect'] != 3) {
                $condition['status'] = $dataRequest['statusSelect'];
            } else {
                $dataRequest['statusSelect'] = 3;
            }
            if (isset($dataRequest['seachCode']) && $dataRequest['seachCode'] != '') {
                $condition['code LIKE']  =  '%' . $dataRequest['seachCode'] . '%';
            } else {
                $dataRequest['seachCode'] = '';
            }
        } else {
            $dataRequest = [
                "typeSelect" => "3",
                "statusSelect" => "3",
                "seachCode" => ''
            ];
        }
        $datas = $this->DepositLogs->find()->where(['user_id' => $this->Auth->user('id')])->where($condition)->orderDesc('modified');
        $datas = $this->paginate($datas);
        $balance = $this->Users->find()->where(['id' => $this->Auth->user('id')])->first()->balance;
        if (!$balance) {
            $balance = 0;
        }
        $this->set(compact('title', 'headerType', 'datas', 'dataRequest', 'balance'));
    }

    public function testIframe() {
        $this->viewBuilder()->enableAutoLayout(false);
    }
}
