<?php

namespace App\Controller\Api\v400;
use App\Controller\Api\AppController;

use Mpdf\Tag\P;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HotelSurchargesTable $HotelSurcharges
 *
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookingsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['addBookingHotel', 'addBookingVoucher', 'addBookingLandtour', 'addBookingHomestay', 'payment', 'savePayment', 'reviewPayment', 'approveBooking', 'denyBooking']);
    }

    public function addBookingHotel()
    {
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $this->loadModel('Hotels');
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('HotelSurcharges');
        $this->loadComponent('Util');

        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }
        $data = $this->getRequest()->getData();
        if (isset($data['booking_id'])) {
            $id = $data['booking_id'];
        } else {
            $id = null;
        }
        $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingHotel', 'associated' => ['BookingRooms']]);

        if ($validate->getErrors()) {
            $res['status'] = STT_NOT_VALIDATION;
            $res['data'] = $validate->getErrors();
        } else {
            $hotel = $this->Hotels->get($data['item_id']);
            if ($user_id) {
                $curUser = $this->Users->get($user_id);
            } else {
                $curUser = null;
            }
            if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                $booking_rooms = $data['booking_rooms'];
                $totalPrice = $totalSaleRev = $totalRev = 0;
                $start_date = $end_date = '';

                $isAllow = true;
                foreach ($data['booking_rooms'] as $key => $booking_room) {
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                            if ($start_date) {
                                if (strtotime($start_date) > strtotime($booking_room['start_date'])) {
                                    $start_date = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                                }
                            } else {
                                $start_date = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                            }
                            if ($end_date) {
                                if (strtotime($end_date) < strtotime($booking_room['end_date'])) {
                                    $end_date = $this->Util->formatSQLDate($booking_room['end_date'], 'd-m-Y');
                                }
                            } else {
                                $end_date = $this->Util->formatSQLDate($booking_room['end_date'], 'd-m-Y');
                            }
                            $sDate = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                            $eDate = $this->Util->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                            $dates = $this->Util->_dateRange($sDate, $eDate);
                            foreach ($dates as $date) {
                                $resPrice = $this->Util->calculateHotelPrice($hotel, $booking_room['room_id'], $date);
                                if ($resPrice['status']) {
                                    $totalPrice += $resPrice['price'] * $booking_room['num_room'];
                                    $totalRev += $this->Util->calculateHotelRevenue($hotel, $booking_room['room_id'], $date) * $booking_room['num_room'];
                                    $totalSaleRev += $this->Util->calculateHotelSaleRevenue($hotel, $booking_room['room_id'], $date) * $booking_room['num_room'];
                                } else {
                                    $isAllow = false;
                                    $res['status'] = STT_NOT_VALIDATION;
                                    $res['data'] = ['message' => $resPrice['message']];
                                    break;
                                }
                            }
                        } else {
                            $isAllow = false;
                            $res['status'] = STT_NOT_VALIDATION;
                            $res['data'] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI LỚN cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxAdult . ' người lớn.']];
                        }
                    } else {
                        $isAllow = false;
                        $res['status'] = STT_NOT_VALIDATION;
                        $res['data'] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                    }
                    $data['booking_rooms'][$key]['start_date'] = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                    $data['booking_rooms'][$key]['end_date'] = $this->Util->formatSQLDate($booking_room['end_date'], 'd-m-Y');
                    if (isset($booking_room['child_ages']) && !empty($booking_room['child_ages'])) {
                        $data['booking_rooms'][$key]['child_ages'] = json_encode($booking_room['child_ages'], JSON_UNESCAPED_UNICODE);
                    }
                }

                if ($isAllow) {
                    foreach ($data['booking_surcharges'] as $key => $booking_surcharge) {
                        if ($booking_surcharge['surcharge_type'] == SUR_WEEKEND || $booking_surcharge['surcharge_type'] == SUR_HOLIDAY || $booking_surcharge['surcharge_type'] == SUR_ADULT || $booking_surcharge['surcharge_type'] == SUR_CHILDREN) {
                            $quantity = 0;
                        } else {
                            $quantity = $booking_surcharge['quantity'];
                        }
                        $surcharge_price = $this->Util->calHotelSurcharge($hotel, $booking_rooms, $booking_surcharge['surcharge_type'], $quantity, $booking_surcharge['id']);
                        if ($surcharge_price > 0) {
                            $data['booking_surcharges'][$key]['price'] = $surcharge_price;
                        } else {
                            unset($data['booking_surcharges'][$key]);
                        }
                    }

                    $data['booking_surcharges'] = array_values($data['booking_surcharges']);

                    if (isset($data['payment_method']) && $data['payment_method'] == 1) {
                        $totalPrice = $totalPrice - $totalRev;
                        $totalRev = 0;
                    }
                    if ($curUser) {
                        if ($curUser->role_id == 2) {
                            $data['sale_id'] = $curUser->id;
                        } elseif ($curUser->role_id == 3) {
                            $data['sale_id'] = $curUser->parent_id;
                        }
                    } else {
                        $data['sale_id'] = 0;
                    }
//                    $data['sale_id'] = ($curUser) ? $curUser->parent_id : 0;
                    $data['user_id'] = $user_id;
                    if ($data['sale_id'] == 0 && $data['user_id'] == 0) {
                        $data['status'] = -1;
                    } else {
                        $data['status'] = 0;
                    }
                    $data['client_id'] = $data['device_id'];
                    $data['item_id'] = $hotel->id;
                    $data['type'] = HOTEL;
                    $data['booking_type'] = SYSTEM_BOOKING;
                    $data['price'] = $totalPrice;
                    $data['sale_revenue'] = $totalSaleRev;
                    $data['revenue'] = $totalRev;
                    $data['start_date'] = $start_date;
                    $data['end_date'] = $end_date;
                    $data['payment_deadline'] = $end_date;

                    if ($id) {
                        $booking = $this->Bookings->get($id);
                    } else {
                        $booking = $this->Bookings->newEntity();
                    }
                    $booking = $this->Bookings->patchEntity($booking, $data);
//                dd($booking);
                    if ($this->Bookings->save($booking)) {
                        $res['status'] = STT_SUCCESS;
                        $res['message'] = 'Thành công';
                        $res['data']['booking_id'] = $booking->id;
                        $newBooking = $this->Bookings->get($booking->id);
                        $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                        $this->Bookings->save($booking);
                    } else {
                        $res['message'] = ' Có lỗi xảy ra';
                    }
                }
            } else {
                $res['status'] = STT_NOT_VALIDATION;
                $res['data'] = ['rooms' => 'Chưa chọn hạng phòng'];
            }

        }
        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => $res['data'],
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function addBookingVoucher()
    {
        $this->loadModel('Vouchers');
        $this->loadModel('Hotels');
        $this->loadModel('Users');
        $this->loadModel('Rooms');
        $this->loadModel('Bookings');

        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }
        $data = $this->getRequest()->getData();
        if (isset($data['booking_id'])) {
            $id = $data['booking_id'];
        } else {
            $id = null;
        }
        $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingVoucherApi']);
        if ($validate->getErrors()) {
            $this->set([
                'status' => STT_NOT_VALIDATION,
                'data' => $validate->getErrors(),
                '_serialize' => ['status', 'data']
            ]);
        } else {
            $voucher = $this->Vouchers->find()->where(['id' => $data['item_id']])->first();
            if ($voucher) {
                if ($user_id > 0) {
                    $curUser = $this->Users->find()->where(['id' => $user_id])->first();
                } else {
                    $curUser = null;
                }
                $hotel = $this->Hotels->find()->where(['id' => $voucher->hotel_id])->first();
                if ($hotel) {
                    $is_special = false;
                    if ($hotel->is_special) {
                        if (!isset($data['information']) || empty($data['information'])) {
                            $is_special = true;
                        }
                    }
                    if (!$is_special) {
                        $data['end_date'] = date('Y-m-d', strtotime($data['start_date'] . ' + ' . $voucher->days_attended . ' days'));
                        if ((strtotime($data['start_date']) >= strtotime($voucher->start_date)) && (strtotime($data['end_date']) <= strtotime($voucher->end_date))) {
                            $data['booking_type'] = SYSTEM_BOOKING;
                            if (is_numeric($data['amount'])) {
                                $data['price'] = ($voucher->price + $voucher->trippal_price + $voucher->customer_price) * $data['amount'];
                                $data['sale_revenue'] = $voucher->trippal_price * $data['amount'];
                                $data['revenue'] = $voucher->customer_price * $data['amount'];
                            }
                            if (isset($data['payment_method']) && $data['payment_method'] == 1) {
                                $data['price'] = $data['price'] - $data['revenue'];
                                $data['revenue'] = 0;
                            }
                            $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd-m-Y');
                            $data['payment_deadline'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
                            if ($curUser) {
                                $data['user_id'] = $curUser->id;
                                if ($curUser->role_id == 2) {
                                    $data['sale_id'] = $curUser->id;
                                } else {
                                    $data['sale_id'] = $curUser->parent_id;
                                }
                                $data['status'] = 0;
                            } else {
                                $data['user_id'] = 0;
                                $data['sale_id'] = 0;
                                $data['status'] = -1;
                            }
                            $data['client_id'] = $data['device_id'];
                            if ($id) {
                                $booking = $this->Bookings->get($id);
                            } else {
                                $booking = $this->Bookings->newEntity();
                            }
                            $booking = $this->Bookings->patchEntity($booking, $data);
                            if ($this->Bookings->save($booking)) {
                                $newBooking = $this->Bookings->get($booking->id);
                                $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                                $this->Bookings->save($booking);

                                $this->set([
                                    'status' => STT_SUCCESS,
                                    'message' => "Thành công",
                                    'data' => ['booking_id' => $booking->id],
                                    '_serialize' => ['status', 'message', 'data']
                                ]);
                            } else {
                                $this->set([
                                    'status' => STT_NOT_SAVE,
                                    'message' => "Có lỗi xảy ra",
                                    'data' => [],
                                    '_serialize' => ['status', 'message', 'data']
                                ]);
                            }
                        } else {
                            $this->set([
                                'status' => STT_NOT_VALIDATION,
                                'data' => ['date' => ['date' => 'Vui lòng nhập đúng khoảng thời gian Voucher.']],
                                '_serialize' => ['status', 'message', 'data']
                            ]);
                        }
                    } else {
                        $this->set([
                            'status' => STT_NOT_VALIDATION,
                            'data' => ['information' => ['empty' => 'This field is required']],
                            '_serialize' => ['status', 'message', 'data']
                        ]);
                    }
                } else {
                    $this->set([
                        'status' => STT_NOT_SAVE,
                        'message' => 'Không tìm thấy khách sạn có trong',
                        'data' => [],
                        '_serialize' => ['status', 'message', 'data']
                    ]);
                }
            } else {
                $this->set([
                    'status' => STT_NOT_SAVE,
                    'message' => 'Không tìm thấy voucher',
                    'data' => [],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
        }


    }

    public function addBookingLandtour($id = null)
    {
        $this->loadModel('LandTours');
        $this->loadModel('Users');
        $this->loadModel('Bookings');
        $this->loadModel('LandTourSurcharges');
        $this->loadModel('LandTourUserPrices');

        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }

        $data = $this->getRequest()->getData();
        if (isset($data['booking_id'])) {
            $id = $data['booking_id'];
        } else {
            $id = null;
        }
        $data['end_date'] = $data['start_date'];
        if (isset($data['drive_surchage_pickup'])) {
            $data['pickup_id'] = $data['drive_surchage_pickup'];
        }
        if (isset($data['drive_surchage_drop'])) {
            $data['drop_id'] = $data['drive_surchage_drop'];
        }
        $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingLandtour']);
        if ($validate->getErrors()) {
            $this->set([
                'status' => STT_NOT_VALIDATION,
                'data' => $validate->getErrors(),
                '_serialize' => ['status', 'data']
            ]);
        } else {
//            $landtour = $this->LandTours->find()->where(['id' => $data['item_id']])->first();
            if (!isset($data['drive_surchage_pickup'])) {
                $data['drive_surchage_pickup'] = 0;
            }
            if (!isset($data['drive_surchage_drop'])) {
                $data['drive_surchage_drop'] = 0;
            }
            $hasAccessories = true;
            if ($data['booking_landtour_accessories'][0] == "") {
                $data['booking_landtour_accessories'][0] = 0;
                $hasAccessories = false;
            }
            $landtour = $this->LandTours->get($data['item_id'], ['contain' => ['LandTourAccessories' => function ($q) use ($data) {
                return $q->where(['LandTourAccessories.Id IN' => $data['booking_landtour_accessories']]);
            },
                'LandTourDrivesurchages' => function ($q) use ($data) {
                    return $q->where(['id IN' => [$data['drive_surchage_pickup'], $data['drive_surchage_drop']]]);
                }
            ]]);
            if ($landtour) {
                if ($user_id > 0) {
                    $curUser = $this->Users->find()->where(['id' => $user_id])->first();
                } else {
                    $curUser = null;
                }

                $data['booking_type'] = SYSTEM_BOOKING;
                $data['amount'] = intval($data['booking_landtour']['num_adult']) + intval($data['booking_landtour']['num_children']) + intval($data['booking_landtour']['num_kid']);

                $priceDefault = $landtour->price + $landtour->customer_price;
                $userPrice = $this->LandTourUserPrices->find()->where(['user_id' => $data['user_id'], 'land_tour_id' => $data['item_id']])->first();
                if ($userPrice) {
                    $priceDefault += $userPrice->price;
                    $data['sale_revenue'] = $userPrice->price * $data['booking_landtour']['num_adult'] + $userPrice->price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $userPrice->price * $data['booking_landtour']['num_kids'] * $landtour->kid_rate / 100;
                } else {
                    $priceDefault += $landtour->trippal_price;
                    $data['sale_revenue'] = $landtour->trippal_price * $data['booking_landtour']['num_adult'] + $landtour->trippal_price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $landtour->trippal_price * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                }
                $data['revenue'] = $landtour->customer_price * $data['booking_landtour']['num_adult'] + $landtour->customer_price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $landtour->customer_price * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                foreach ($landtour->land_tour_accessories as $accessory) {
                    $priceDefault += $accessory->adult_price;
                }
                $price = $priceDefault * $data['booking_landtour']['num_adult'] + $priceDefault * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $priceDefault * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                $data['amount'] = $data['booking_landtour']['num_adult'] + $data['booking_landtour']['num_children'] + $data['booking_landtour']['num_kid'];
                $tempDriveSurchage = 0;
                if (count($landtour->land_tour_drivesurchages) == 1) {
                    if ($data['drive_surchage_pickup'] == 0 || $data['drive_surchage_drop'] == 0) {
                        $tempDriveSurchage = $data['booking_landtour']['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult * 0.5;
                        if ($tempDriveSurchage > $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5) {
                            $tempDriveSurchage = $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5;
                        }
                    } else {
                        $tempDriveSurchage = $data['booking_landtour']['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult;
                        if ($tempDriveSurchage > $landtour->land_tour_drivesurchages[0]->price_crowd) {
                            $tempDriveSurchage = $landtour->land_tour_drivesurchages[0]->price_crowd;
                        }
                    }

                } elseif (count($landtour->land_tour_drivesurchages) == 2) {
                    $tempDriveSurchage1 = $data['booking_landtour']['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult * 0.5;
                    if ($tempDriveSurchage1 > $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5) {
                        $tempDriveSurchage1 = $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5;
                    }
                    $tempDriveSurchage2 = $data['booking_landtour']['num_adult'] * $landtour->land_tour_drivesurchages[1]->price_adult * 0.5;
                    if ($tempDriveSurchage2 > $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5) {
                        $tempDriveSurchage2 = $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5;
                    }
                    $tempDriveSurchage = $tempDriveSurchage1 + $tempDriveSurchage2;
                }
                if (isset($data['drive_surchage_pickup'])) {
                    $data['booking_landtour']['pickup_id'] = $data['drive_surchage_pickup'];
                    $data['booking_landtour']['detail_pickup'] = $data['detail_pickup'];
                    unset($data['drive_surchage_pickup']);
                    unset($data['detail_pickup']);
                }
                if (isset($data['drive_surchage_drop'])) {
                    $data['booking_landtour']['drop_id'] = $data['drive_surchage_drop'];
                    $data['booking_landtour']['detail_drop'] = $data['detail_drop'];
                    unset($data['drive_surchage_drop']);
                    unset($data['detail_drop']);
                }
                $price += $tempDriveSurchage;
                $data['booking_landtour']['drive_surchage'] = $tempDriveSurchage;

                if (isset($data['amount'])) {
                    if (is_numeric($data['amount'])) {
                        $data['price'] = $price;
                        $data['sale_revenue'] = $landtour->trippal_price * $data['booking_landtour']['num_adult'] + $landtour->trippal_price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $landtour->trippal_price * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                    }
                }

                if (isset($data['payment_method'])) {
                    if ($data['payment_method'] == AGENCY_PAY) {
                        $data['price'] = $data['price'] - $data['revenue'];
                        $data['revenue'] = 0;
                    }
                    if ($data['payment_method'] == MUSTGO_DEPOSIT) {
                        $data['price'] = $data['price'] - $data['revenue'];
                        $data['revenue'] = $data['mustgo_deposit'] - $data['price'];
                    }
                } else {
                    $data['payment_method'] = CUSTOMER_PAY;
                }
                $data['start_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
                $data['end_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['end_date'])));
                $data['payment_deadline'] = $data['end_date'];
                if ($curUser) {
                    $data['user_id'] = $curUser->id;
                    if ($curUser->role_id == 2) {
                        $data['sale_id'] = $curUser->id;
                    } else {
                        if ($curUser->landtour_parent_id != 0) {
                            $data['sale_id'] = $curUser->landtour_parent_id;
                        } else {
                            $landtourSale = $this->Users->find()
                                ->where(['role_id' => 5])
                                ->order('rand()')
                                ->firstOrFail();
                            $currentUser = $this->Users->get($this->Auth->user('id'));
                            $currentUser = $this->Users->patchEntity($currentUser, ['landtour_parent_id' => $landtourSale->id]);
                            $this->Users->save($currentUser);
                            $data['sale_id'] = $landtourSale->id;
                        }
                    }
                    $data['status'] = 0;
                } else {
                    $data['user_id'] = 0;
                    $data['sale_id'] = 0;
                    $data['status'] = -1;
                }
                $data['client_id'] = $data['device_id'];
                $data['booking_landtour']['landtour_id'] = $data['item_id'];

                if ($id) {
                    $booking = $this->Bookings->get($id, ['contain' => 'BookingLandtours']);
                    $data['booking_landtour']['id'] = $booking->booking_landtour->id;
                } else {
                    $booking = $this->Bookings->newEntity();
                }
                if ($hasAccessories) {
                    $tmpData = $data['booking_landtour_accessories'];
                    unset($data['booking_landtour_accessories']);
                    $data['booking_landtour_accessories'] = [];

                    if (!(count($data['booking_landtour_accessories']) == 1 && $data['booking_landtour_accessories'][0] == 0)) {
                        foreach ($tmpData as $k => $item) {
                            $data['booking_landtour_accessories'][$k]['land_tour_accessory_id'] = $item;
                        }
                    }
                }
                $booking = $this->Bookings->patchEntity($booking, $data);
                if ($this->Bookings->save($booking)) {
                    $newBooking = $this->Bookings->get($booking->id);
                    $booking = $this->Bookings->patchEntity($booking, ['code' => "MPQ" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                    $this->Bookings->save($booking);
                    $this->set([
                        'status' => STT_SUCCESS,
                        'message' => "Thành công",
                        'data' => ['booking_id' => $booking->id],
                        '_serialize' => ['status', 'message', 'data']
                    ]);
                } else {
                    $this->set([
                        'status' => STT_NOT_SAVE,
                        'message' => "Có lỗi xảy ra",
                        'data' => [],
                        '_serialize' => ['status', 'message', 'data']
                    ]);
                }
            } else {
                $this->set([
                    'status' => STT_NOT_SAVE,
                    'message' => 'Không tìm thấy landtour',
                    'data' => [],
                    '_serialize' => ['status', 'data']
                ]);
            }
        }
    }

    public function addBookingHomestay($id = null)
    {
        $this->loadModel('HomeStays');
        $this->loadModel('Bookings');
        $this->loadModel('Users');

        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }
        $data = $this->getRequest()->getData();
        if (isset($data['booking_id'])) {
            $id = $data['booking_id'];
        } else {
            $id = null;
        }
        $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingHomeStay']);
        if ($validate->getErrors()) {
            $this->set([
                'status' => STT_NOT_VALIDATION,
                'data' => $validate->getErrors(),
                '_serialize' => ['status', 'data']
            ]);
        } else {
            $homestay = $this->HomeStays->find()->where(['id' => $data['item_id']])->contain('PriceHomeStays')->first();
            if ($homestay) {
                if ($user_id > 0) {
                    $curUser = $this->Users->find()->where(['id' => $user_id])->first();
                } else {
                    $curUser = null;
                }
                if (strtotime($data['start_date']) <= strtotime($data['end_date'])) {
                    $date_start_attended = date_create(date('Y-m-d', strtotime($data['start_date'])));
                    $date_end_attended = date_create(date('Y-m-d', strtotime($data['end_date'])));
                    $end_date = date('d-m-Y', strtotime($data['end_date'] . ' - 1 day'));
                    $days_attended = date_diff($date_start_attended, $date_end_attended);
                    $homestay->days_attended = $days_attended->days;
                    $dateArray = $this->Util->_dateRange($data['start_date'], $end_date);
                    $totalPrice = 0;
                    foreach ($dateArray as $date) {
                        $totalPrice += $this->Util->countingHomeStayPrice($date, $homestay);
                    }

                    $data['booking_type'] = SYSTEM_BOOKING;
                    $data['type'] = HOMESTAY;
                    $data['price'] = $totalPrice;
                    $data['amount'] = 1;
                    $data['revenue'] = $homestay->price_customer * $days_attended->days;
                    $data['sale_revenue'] = $homestay->price_customer * $data['amount'];
                    if (isset($data['payment_method']) && $data['payment_method'] == 1) {
                        $data['price'] = $data['price'] - $data['revenue'];
                        $data['revenue'] = 0;
                    }
                    $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd-m-Y');
                    $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'd-m-Y');
                    $data['payment_deadline'] = $data['end_date'];
                    if ($curUser) {
                        $data['user_id'] = $curUser->id;
                        if ($curUser->role_id == 2) {
                            $data['sale_id'] = $curUser->id;
                        } else {
                            $data['sale_id'] = $curUser->parent_id;
                        }
                        $data['status'] = 0;
                    } else {
                        $data['user_id'] = 0;
                        $data['sale_id'] = 0;
                        $data['status'] = -1;
                    }
                    $data['client_id'] = $data['device_id'];
                    if ($id) {
                        $booking = $this->Bookings->get($id);
                    } else {
                        $booking = $this->Bookings->newEntity();
                    }
                    $booking = $this->Bookings->patchEntity($booking, $data);
                    if ($this->Bookings->save($booking)) {
                        $newBooking = $this->Bookings->get($booking->id);
                        $response['success'] = true;
                        $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                        $this->Bookings->save($booking);
                        $this->set([
                            'status' => STT_SUCCESS,
                            'message' => "Thành công",
                            'data' => ['booking_id' => $booking->id],
                            '_serialize' => ['status', 'message', 'data']
                        ]);
                    } else {
                        $this->set([
                            'status' => STT_NOT_SAVE,
                            'message' => "Có lỗi xảy ra",
                            'data' => [],
                            '_serialize' => ['status', 'message', 'data']
                        ]);
                    }
                } else {
                    $this->set([
                        'status' => STT_NOT_VALIDATION,
                        'message' => 'Ngày check out không thể nhỏ hơn ngày check in',
                        'data' => [],
                        '_serialize' => ['status', 'message', 'data']
                    ]);
                }
            } else {
                $this->set([
                    'status' => STT_NOT_SAVE,
                    'message' => 'Không tìm thấy homestay',
                    'data' => [],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
        }
    }

    public function payment($id)
    {
        $this->loadModel('Configs');

        $data = [];

        $booking = $this->Bookings->get($id, ['contain' => ['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'Vouchers', 'LandTours', 'HomeStays']]);
        if ($booking && $booking->is_paid != 1) {
            $bookingPrice = $booking->price;
            if ($booking->booking_surcharges) {
                foreach ($booking->booking_surcharges as $booking_surcharge) {
                    $bookingPrice += $booking_surcharge->price;
                }
            }
            if ($booking->type != LANDTOUR) {
                $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
                $banks = json_decode($json_banks->value, true);
                if (!$banks) {
                    $banks = [];
                }
                $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
                $bank_invoice = json_decode($json_invoice->value, true);
            } else {
                $json_banks = $this->Configs->find()->where(['type' => 'bank-account-landtour'])->first();
                if ($json_banks) {
                    $banks = json_decode($json_banks->value, true);
                    if (!$banks) {
                        $banks = [];
                    }
                } else {
                    $banks = [];
                }
                $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice-landtour'])->first();
                if ($json_invoice) {
                    $bank_invoice = json_decode($json_invoice->value, true);
                } else {
                    $bank_invoice = null;
                }
            }
            $data['booking'] = $booking;
            $data['banks'] = $banks;
            $data['bank_invoice'][] = $bank_invoice;
            $data['booking_total_price'] = $bookingPrice;

            $this->set([
                'status' => STT_SUCCESS,
                'data' => $data,
                '_serialize' => ['status', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_ALLOW,
                'message' => 'Bạn không được phép vào đây.',
                '_serialize' => ['status', 'data']
            ]);
        }
    }

    public function savePayment()
    {
        $this->loadModel('Payments');
        $res = ['status' => STT_NOT_VALIDATION, 'message' => '', 'data' => []];

        $data = $this->getRequest()->getData();
        $validateType = $this->Payments->newEntity($data, ['validate' => 'paymentType']);
        if ($validateType->getErrors()) {
            $res['data'] = $validateType->getErrors();
        } else {
            $isValidate = false;
            if ($data['type'] == PAYMENT_TRANSFER) {
                $validateInvoice = $this->Payments->newEntity($data, ['validate' => 'paymentInvoice']);
                if ($validateInvoice->getErrors()) {
                    $res['data'] = $validateInvoice->getErrors();
                } else {
                    if ($data['invoice'] == 1) {
                        $validateInvoiceExport = $this->Payments->newEntity($data, ['validate' => 'paymentExportInvoice']);
                        if ($validateInvoiceExport->getErrors()) {
                            $res['data'] = $validateInvoiceExport->getErrors();
                        } else {
                            $isValidate = true;
                        }
                    } else {
                        $isValidate = true;
                    }
                }
            } elseif ($data['type'] == PAYMENT_HOME) {
                $validateAddress = $this->Payments->newEntity($data, ['validate' => 'paymentAddress']);
                if ($validateAddress->getErrors()) {
                    $res['data'] = $validateAddress->getErrors();
                } else {
                    $isValidate = true;
                }
            } else {
                $isValidate = true;
            }

            if ($isValidate) {
                $payment = $this->Payments->find()->where(['booking_id' => $data['booking_id']])->first();
                if (!$payment) {
                    $payment = $this->Payments->newEntity();
                }
                $payment = $this->Payments->patchEntity($payment, $data);
                $this->Payments->save($payment);
                if ($payment->images) {
                    $saveBooking = $this->Bookings->get($data['booking_id']);
                    $saveBooking = $this->Bookings->patchEntity($saveBooking, ['agency_pay' => 1]);
                    $this->Bookings->save($saveBooking);
                }

                $booking = $this->Bookings->get($data['booking_id']);
                $booking = $this->Bookings->patchEntity($booking, ['is_paid' => 1]);
                $this->Bookings->save($booking);

                $res['status'] = STT_SUCCESS;
                $res['message'] = 'Thành công';
            }
        }

        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => $res['data'],
            '_serialize' => ['status', 'data', 'message']
        ]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Combos', 'Vouchers', 'LandTours', 'Hotels']
        ];
        $bookings = $this->paginate($this->Bookings);

        $this->set(compact('bookings'));
    }

    /**
     * View method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Combos', 'Vouchers', 'LandTours', 'Hotels']
        ]);

        $this->set('booking', $booking);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $booking = $this->Bookings->newEntity();
        if ($this->request->is('post')) {
            $booking = $this->Bookings->patchEntity($booking, $this->request->getData());
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $combos = $this->Bookings->Combos->find('list', ['limit' => 200]);
        $vouchers = $this->Bookings->Vouchers->find('list', ['limit' => 200]);
        $landTours = $this->Bookings->LandTours->find('list', ['limit' => 200]);
        $hotels = $this->Bookings->Hotels->find('list', ['limit' => 200]);
        $this->set(compact('booking', 'users', 'combos', 'vouchers', 'landTours', 'hotels'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $booking = $this->Bookings->patchEntity($booking, $this->request->getData());
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $combos = $this->Bookings->Combos->find('list', ['limit' => 200]);
        $vouchers = $this->Bookings->Vouchers->find('list', ['limit' => 200]);
        $landTours = $this->Bookings->LandTours->find('list', ['limit' => 200]);
        $hotels = $this->Bookings->Hotels->find('list', ['limit' => 200]);
        $this->set(compact('booking', 'users', 'combos', 'vouchers', 'landTours', 'hotels'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $booking = $this->Bookings->get($id);
        if ($this->Bookings->delete($booking)) {
            $this->Flash->success(__('The booking has been deleted.'));
        } else {
            $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function reviewPayment($id)
    {
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $this->loadModel('Configs');
        $this->loadModel('Payments');
        $this->loadModel('HotelSurcharges');
        $data = $this->getRequest()->getQuery();

        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
            $role_id = $check['role_id'];
        }
        $booking = $this->Bookings->get($id, ['contain' => ['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'Vouchers', 'LandTours', 'HomeStays', 'Hotels', 'Vouchers.Hotels']]);
        if ($booking) {
            $bookingPrice = $booking->price;
            foreach ($booking->booking_surcharges as $booking_surcharge) {
                $bookingPrice += $booking_surcharge->price;
            }
            if (isset($role_id)) {
                if ($role_id == 3) {
                    switch ($booking->status) {
                        case -1:
                            $booking->status_str = "Khách đã gửi đơn đặt phòng";
                            break;
                        case 0:
                            $booking->status_str = "CTV mới đặt";
                            break;
                        case 1:
                            $booking->status_str = "Chờ KS xác nhận rồi gửi mail đề nghị thanh toán";
                            break;
                        case 2:
                            $booking->status_str = "Đang chờ CTV thanh toán ";
                            break;
                        case 3:
                            if ($booking->payment_method == 0) {
                                $booking->status_str = "Hoàn thành (chờ Admin cộng lãi)";
                            } else if ($booking->payment_method == 1) {
                                $booking->status_str = "Hoàn thành";
                            }
                            break;
                        case 4:
                            $booking->status_str = "Hoàn thành";
                            break;
                        case 5:
                            $booking->status_str = "Đơn hàng đã bị hủy";
                            break;
                    }
                }
            } else {
                switch ($booking->status) {
                    case -1:
                        $booking->status_str = "Đã gửi đơn đặt phòng";
                        break;
                    case 0:
                        $booking->status_str = "Chờ kiểm tra tình trạng phòng";
                        break;
                    case 1:
                        $booking->status_str = "Chờ kiểm tra tình trạng phòng";
                        break;
                    case 2:
                        $booking->status_str = "Còn phòng , đề nghị thanh toán đơn hàng ";
                        break;
                    case 3:
                        $booking->status_str = "Đã thanh toán, chờ mã xác nhận";
                        break;
                    case 4:
                        $booking->status_str = "Hoàn thành";
                        break;
                    case 5:
                        $booking->status_str = "Đơn hàng đã bị hủy";
                        break;
                }
            }
            $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
            $banks = json_decode($json_banks->value, true);
            if (!$banks) {
                $banks = [];
            }
            $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
            $bank_invoice = json_decode($json_invoice->value, true);
            $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
            if (isset($payment->images)) {
                $payment->images = json_decode($payment->images, true);
            }
            $hotline = '';
            $contractFile = '';
            switch ($booking->type) {
                case HOTEL:
                    $hotline = $booking->hotels->hotline;
                    $contractFile = $booking->hotels->contract_file;
                    break;
                case HOMESTAY:
                    $hotline = $booking->home_stays->hotline;
                    $contractFile = $booking->home_stays->contract_file;
                    break;
                case LANDTOUR:
                    $hotline = $booking->land_tours->phone;
                    $contractFile = $booking->land_tours->contract_file;
                    $driveSurchagePrice = 0;
                    if ($booking->booking_landtour) {
                        $res['data']['landtourPriceSurchage'] = $booking->booking_landtour->drive_surchage;
                    } else {
                        $res['data']['landtourPriceSurchage'] = 0;
                    }

                    break;
                case VOUCHER:
                    $hotline = $booking->vouchers->hotel->hotline;
                    $contractFile = $booking->vouchers->hotel->contract_file;
                    break;
            }
            if($booking->booking_surcharges){
                foreach ($booking->booking_surcharges as $k => $surcharge){
                    $surcharge = $this->HotelSurcharges->find()->where(['surcharge_type' => $surcharge->surcharge_type, 'hotel_id' => $booking->item_id])->first();
                    $booking->booking_surcharges[$k]->surcharge_id = $surcharge->id;
                }
            }
            $res['data']['booking'] = $booking;
            $res['data']['banks'] = $banks;
            $res['data']['bank_invoice'][] = $bank_invoice;
            $res['data']['hotline'] = $hotline;
            $res['data']['contractFile'] = $contractFile;
            $res['data']['payment'] = $payment;
            $res['data']['bookingPrice'] = $bookingPrice;
            $res['data']['agencyPrice'] = $bookingPrice - $booking->revenue;
//            if (isset($booking->booking_landtour->num_children)) {
//                $allRev = 0;
//                if ($booking->payment_method == CUSTOMER_PAY) {
//                    $allRev = $booking->land_tours->price + $booking->land_tours->trippal_price + $booking->land_tours->customer_price;
//                } elseif ($booking->payment_method == AGENCY_PAY) {
//                    $allRev = $booking->land_tours->price + $booking->land_tours->trippal_price;
//                }
//                $res['data']['booking']['bookingLandTourSurcharge'] = $booking->price - $allRev * $booking->booking_landtour->num_adult;
//            }
            $res['message'] = 'Success';
        } else {
            $res['message'] = 'Không tìm thấy đơn hàng';
        }
        $this->set([
            'status' => STT_SUCCESS,
            'message' => $res['message'],
            'data' => $res['data'],
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function approveBooking()
    {
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $this->loadModel('Users');
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $booking_id = $this->getRequest()->getData('booking_id');
            $user_id = $check['user_id'];
            $booking = $this->Bookings->get($booking_id);
            $user = $this->Users->get($user_id);
            if ($booking) {
                if ($booking->user_id == $user->id) {
                    if ($booking->status == -1) {
                        $booking = $this->Bookings->patchEntity($booking, ['status' => 0]);
                        if ($this->Bookings->save($booking)) {
                            $res['status'] = STT_SUCCESS;
                            $res['message'] = 'Success';
                        } else {
                            $res['status'] = STT_ERROR;
                            $res['message'] = 'The booking could not be saved. Please, try again.';
                        }
                    } else {
                        $res['status'] = STT_ERROR;
                        $res['message'] = 'The booking could not be saved. Please, try again.';
                    }
                } else {
                    $res['status'] = STT_ERROR;
                    $res['message'] = 'The booking not belong to you. Please, try again.';
                }
            } else {
                $res['status'] = STT_ERROR;
                $res['message'] = 'Cant find this Booking. Please, try again.';
            }
        } else {
            $res['status'] = STT_NOT_LOGIN;
            $res['message'] = 'Not login yet, please login and try again.';
        }
        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            '_serialize' => ['status', 'message']
        ]);
    }

    public function denyBooking()
    {
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $this->loadModel('Users');
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $booking_id = $this->getRequest()->getData('booking_id');
            $user_id = $check['user_id'];
            $booking = $this->Bookings->get($booking_id);
            $user = $this->Users->get($user_id);
            if ($booking) {
                if ($booking->user_id == $user->id) {
                    if ($booking->status == -1 || $booking->status == 0) {
                        $booking = $this->Bookings->patchEntity($booking, ['status' => 5]);
                        if ($this->Bookings->save($booking)) {
                            $res['status'] = STT_SUCCESS;
                            $res['message'] = 'Success';
                        } else {
                            $res['status'] = STT_ERROR;
                            $res['message'] = 'The booking could not be saved. Please, try again.';
                        }
                    } else {
                        $res['status'] = STT_ERROR;
                        $res['message'] = 'The booking could not be saved. Please, try again.';
                    }
                } else {
                    $res['status'] = STT_ERROR;
                    $res['message'] = 'The booking not belong to you. Please, try again.';
                }
            } else {
                $res['status'] = STT_ERROR;
                $res['message'] = 'Cant find this Booking. Please, try again.';
            }
        } else {
            $res['status'] = STT_NOT_LOGIN;
            $res['message'] = 'Not login yet, please login and try again.';
        }
        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            '_serialize' => ['status', 'message']
        ]);
    }
}
