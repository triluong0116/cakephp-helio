<?php

namespace App\Controller\Api\V600;

use Cake\Log\Log;
use Mpdf\Tag\P;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\HotelSurchargesTable $HotelSurcharges
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\VinhmsbookingsTable $Vinhmsbookings
 * @property \App\Model\Table\VinhmsbookingRoomsTable $VinhmsbookingRooms
 * @property \App\Model\Table\VinpaymentsTable $Payments
 * @property \App\Model\Table\VinpaymentsTable $Vinpayments
 * @property \App\Model\Table\VinhmsbookingTransportationsTable $VinhmsbookingTransportations
 * @property \App\Model\Table\VinhmsallotmentsTable $Vinhmsallotments
 * @property \App\Model\Table\DepositLogsTable $DepositLogs
 * @property \App\Controller\Component\UtilComponent $Util
 *
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookingsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['addBookingHotel', 'addBookingVoucher', 'addBookingLandtour', 'addBookingHomestay', 'payment', 'savePayment', 'getPaymentInfor', 'approveBooking', 'denyBooking', 'addBookingVinpearl', 'listBookingCode', 'listBookingVinCode', 'listBookingLandtourCode', 'paymentVinpearl', 'getVinPaymentInfor', 'savePaymentVinpearl', 'calPriceVinpearl', 'paymentLandtour', 'reviewPayment', 'reviewPaymentVinpaerl']);
    }

    public function addBookingHotel()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
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
            Log::write('debug', 'data:"' . json_encode($data, JSON_UNESCAPED_SLASHES) . '"');
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
                if ($hotel) {
                    $is_special = false;
                    if ($hotel->is_special == 1) {
//                        dd(empty($data['information']),isset($data['information']));
                        if (!isset($data['information']) || empty($data['information'])) {
                            $is_special = true;
                        }
                    }
                    if (!$is_special) {
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
                                try {
                                    if ($this->Bookings->save($booking)) {
                                        $res['status'] = STT_SUCCESS;
                                        $res['message'] = 'Thành công';
                                        $res['data']['booking_id'] = $booking->id;
                                        $newBooking = $this->Bookings->get($booking->id);
                                        $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                                        $this->Bookings->save($booking);
//                                $this->Util->notifyCountNewBooking($data['sale_id']);
//                            if ($booking->sale_id == 0 && $booking->user_id == 0) {
//                                $this->Util->notifyNewCustomerBookingTelegram($booking->code);
//                            } else {
//                                $sale = $this->Users->get($booking->sale_id);
//                                if ($sale) {
//                                    $this->Util->notifyNewAgentBookingTelegram($booking->code, $sale->telegram_id, $sale->telegram_username);
//                                } else {
//                                    $this->Util->notifyNewCustomerBookingTelegram($booking->code);
//                                }
//                            }
                                    } else {
                                        $res['message'] = ' Có lỗi xảy ra';
                                    }
                                } catch (\Exception $exception) {
                                    dd($exception);
                                }
                            }
                        } else {
                            $res['status'] = STT_NOT_VALIDATION;
                            $res['data'] = ['rooms' => 'Chưa chọn hạng phòng'];
                        }
                    } else {
                        $res['status'] = STT_NOT_VALIDATION;
                        $res['data'] = ['information' => ["_required" => "This field is required"]];
                    }
                } else {
                    $res['status'] = STT_NOT_SAVE;
                    $res['message'] = 'Không tìm thấy khách sạn ';
                    $res['data'] = [];
                }
            }
            $this->set([
                'status' => $res['status'],
                'message' => $res['message'],
                'data' => $res['data'],
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
                    if ($hotel->is_special == 1) {
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
                                if ($booking->sale_id == 0 && $booking->user_id == 0) {
                                    $this->Util->notifyNewCustomerBookingTelegram($booking->code);
                                } else {
                                    $sale = $this->Users->get($booking->sale_id);
                                    if ($sale) {
                                        $this->Util->notifyNewAgentBookingTelegram($booking->code, $sale->telegram_id, $sale->telegram_username);
                                    } else {
                                        $this->Util->notifyNewCustomerBookingTelegram($booking->code);
                                    }
                                }

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
            $landtour = $this->LandTours->get($data['item_id'], [
                'contain' => [
                    'LandTourAccessories' => function ($q) use ($data) {
                        return $q->where(['LandTourAccessories.Id IN' => $data['booking_landtour_accessories']]);
                    },
                    'LandTourDrivesurchages' => function ($q) use ($data) {
                        return $q->where(['id IN' => [$data['drive_surchage_pickup'], $data['drive_surchage_drop']]]);
                    }
                ]
            ]);
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
                if (!isset($data['end_date'])) {
                    $data['end_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
                } else {
                    $data['end_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
                }
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
                            $currentUser = $this->Users->get($curUser->id);
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
//                    if ($booking->sale_id == 0 && $booking->user_id == 0) {
//                        $this->Util->notifyNewCustomerBookingTelegram($booking->code);
//                    } else {
//                        $sale = $this->Users->get($booking->sale_id);
//                        if ($sale) {
//                            $this->Util->notifyNewAgentBookingTelegram($booking->code, $sale->telegram_id, $sale->telegram_username);
//                        } else {
//                            $this->Util->notifyNewCustomerBookingTelegram($booking->code);
//                        }
//                    }
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
                        if ($booking->sale_id == 0 && $booking->user_id == 0) {
                            $this->Util->notifyNewCustomerBookingTelegram($booking->code);
                        } else {
                            $sale = $this->Users->get($booking->sale_id);
                            if ($sale) {
                                $this->Util->notifyNewAgentBookingTelegram($booking->code, $sale->telegram_id, $sale->telegram_username);
                            } else {
                                $this->Util->notifyNewCustomerBookingTelegram($booking->code);
                            }
                        }
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
        $this->loadModel('Users');
        $this->loadModel('Payments');
        $data = [];
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $booking = $this->Bookings->get($id, ['contain' => ['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'LandTours', 'Payments']]);
            if ($booking) {
                $bookingPrice = $booking->price;
                if ($booking->booking_surcharges) {
                    foreach ($booking->booking_surcharges as $booking_surcharge) {
                        $bookingPrice += $booking_surcharge->price;
                    }
                }
                $booking->price = $bookingPrice;
                if ($booking->type != LANDTOUR) {
                    $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
                    $banks = json_decode($json_banks->value, true);
                    if (!$banks) {
                        $banks = [];
                    }
                    $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
                    $bank_invoice = json_decode($json_invoice->value, true);
                } else {
                    if ($booking->payment_method == MUSTGO_DEPOSIT) {
                        $booking->revenue = $booking->mustgo_deposit - $booking->price;
                    }
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
                $payment_booking = $this->Payments->find()->where(['booking_id' => $booking['id']])->first();
                $payment_format = [];
                if ($payment_booking) {
                    if ($payment_booking->invoice == 1) {
                        $payment_format['invoice'] = 'Xuất hóa đơn';
                    } else {
                        $payment_format['invoice'] = 'Không xuất hóa đơn';
                    }
                    if ($payment_booking->type == PAYMENT_ONEPAY_CREDIT || $payment_booking->type == PAYMENT_ONEPAY_ATM || $payment_booking->type == PAYMENT_ONEPAY_QR) {
                        switch ($payment_booking->onepaystatus) {
                            case 0:
                                $status = 1;
                                break;
                            default:
                                $status = 0;
                                break;
                        }
                    } elseif ($payment_booking->type == PAYMENT_BALANCE) {
                        $status = 1;
                    } else {
                        if (json_decode($payment_booking->images)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                    }

                    $payment_format['status'] = $status;
                    if ($payment_booking->type == PAYMENT_BALANCE) {
                        $payment_format['type'] = 'Credit';
                    } elseif ($payment_booking->type == PAYMENT_TRANSFER) {
                        $payment_format['type'] = 'Chuyển khoản ngân hàng';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_CREDIT) {
                        $payment_format['type'] = 'Thẻ tín dụng / Ghi nợ';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_ATM) {
                        $payment_format['type'] = 'ATM / Tài khoản ngân hàng';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_QR) {
                        $payment_format['type'] = 'Quét mã QR';
                    }
                } else {
                    $payment_format['status'] = 0;
                }
                if($booking->type == LANDTOUR) {
                    $booking = $this->Util->getStatusBookingLandtour($booking, 3);
                } else {
                    $booking = $this->Util->getStatusBooking($booking, 3);
                }
                $status_str = $booking->status_str;
                $payment_format['status_str'] = $status_str;

                $atm_and_qr = 0;
                $visa = 0;
                if ($booking->user_id != $booking->sale_id) {
                    $price = $booking->price - $booking->revenue;
                    $atm_and_qr = round($price / (100 - 1.1) * 100 + 1760 - $price);
                    $visa = round($price / (100 - 2.75) * 100 + 7150 - $price);
                } else {
                    $price = $booking->price;
                    $atm_and_qr = round($booking->price / (100 - 1.1) * 100 + 1760 - $booking->price);
                    $visa = round($booking->price / (100 - 2.75) * 100 + 7150 - $booking->price);
                }
                $booking->price = $price;
                $user = $this->Users->get($check['user_id']);
                $payment_method = [
                    'credit' => [
                        'available' => $user->balance > $price ? true : false,
                        'fee' => 0,
                        'balande' => $user->balance,
                        'type' => PAYMENT_BALANCE,
                        'name' => 'Credit',
                        'description' => '',
                        'icon' => 'files/icons/credit.png',
                    ],
                    'banking' => [
                        'available' => true,
                        'fee' => 0,
                        'type' => PAYMENT_TRANSFER,
                        'name' => 'Chuyển khoản ngân hàng',
                        'description' => '',
                        'icon' => 'files/icons/banking.png',
                    ],
                    'visa' => [
                        'available' => true,
                        'fee' => $visa,
                        'type' => PAYMENT_ONEPAY_CREDIT,
                        'name' => 'Thẻ tín dụng / Ghi nợ',
                        'description' => '',
                        'icon' => 'files/icons/visa.png',
                    ],
                    'atm' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_ATM,
                        'name' => 'ATM / Tài khoản ngân hàng',
                        'description' => '',
                        'icon' => 'files/icons/atm.png',
                    ],
                    'qr' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_QR,
                        'name' => 'Quét mã QR',
                        'description' => '',
                        'icon' => 'files/icons/qr.png',
                    ],
                ];
                $booking_total_price = [
                    'booking_total_price' => ($booking->price - $booking->revenue - $booking->sale_discount - $booking->agency_discount),
                    'price_dai_ly_thanh_toan' => ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                    'content' => "Thanh toán " . $booking->code
                ];
                $booking->revenue = 0;
                $allow_payment = false;
                if ($booking->status >= 2 || $booking->type == 3) {
                    $allow_payment = true;
                }
                $data['booking'] = $booking;
                $data['banks'] = $banks;
                $data['bank_invoice'][] = $bank_invoice;
                $data['booking_total_price'] = $booking_total_price;
                $data['transaction_fee'] = $payment_method;
                $data['allow_payment'] = $allow_payment;
                $data['booking']['payment_format'] = $payment_format;

                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Success',
                    'data' => $data,
                    '_serialize' => ['status', 'message', 'data']
                ]);
            } else {
                $this->set([
                    'status' => STT_NOT_ALLOW,
                    'message' => 'Bạn không được phép vào đây.',
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

    public function savePayment()
    {
        $this->loadModel('Payments');
        $this->loadModel('Users');
        $this->loadModel('DepositLogs');
        $res = ['status' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dự liệu gửi lên.', 'data' => []];
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $dataRequest = $this->request->getData();
            $data = [
                'booking_id' => $dataRequest['booking_id'],
                'type' => $dataRequest['type'],
                'invoice' => $dataRequest['invoice'],
                'invoice_information' => $dataRequest['invoice_information'],
                'images' => isset($dataRequest['images']) ? json_encode($dataRequest['images']) : '',
            ];
            $validateType = $this->Payments->newEntity($data, ['validate' => 'paymentType']);
            if ($validateType->getErrors()) {
                $res['data'] = $validateType->getErrors();
            } else {
                $isValidate = false;
                if (isset($data['type'])) {
                    if ($data['type'] == PAYMENT_TRANSFER) {
                        $validateInvoice = $this->Payments->newEntity($data, ['validate' => 'paymentInvoice']);
                        if ($validateInvoice->getErrors()) {
                            $response['errors'] = $validateInvoice->getErrors();
                        } else {
                            if ($data['invoice'] == 1) {
                                $validateInvoiceExport = $this->Payments->newEntity($data, ['validate' => 'paymentExportInvoice']);
                                if ($validateInvoiceExport->getErrors()) {
                                    $response['errors'] = $validateInvoiceExport->getErrors();
                                    $res['message'] = $response['errors'];
                                } else {
                                    $isValidate = true;
                                }
                            } else {
                                $isValidate = true;
                            }
                        }
                    } elseif ($data['type'] == PAYMENT_ONEPAY_ATM || $data['type'] == PAYMENT_ONEPAY_CREDIT || $data['type'] == PAYMENT_ONEPAY_QR) {
                        $isValidate = true;
                    } elseif ($data['type'] == PAYMENT_BALANCE) {
                        $booking = $this->Bookings->find()->contain(['BookingSurcharges'])->where(['id' => $data['booking_id']])->first();
                        if ($booking) {
                            $user = $this->Users->find()->where(['id' => $booking->user_id])->first();
                            $bookingPrice = $booking->price;
                            foreach ($booking->booking_surcharges as $surcharge) {
                                $bookingPrice += $surcharge->price;
                            }
                            $pricePaid = $bookingPrice - $booking->revenue;
                            if ($user->balance > $bookingPrice - $booking->revenue) {
                                $depositLog = $this->DepositLogs->newEntity();
                                if ($this->DepositLogs->save($depositLog)) {
                                    $arr = [];
                                    $arr['user_id'] = $booking->user_id;
                                    $arr['creator_id'] = $check['user_id'];
                                    $arr['title'] = "Thanh toán Booking mã " . $booking->code;
                                    $arr['amount'] = "-" . $pricePaid;
                                    $arr['balance'] = $user->balance - $pricePaid;
                                    $arr['type'] = 2;
                                    $arr['status'] = 1;
                                    $arr['booking_id'] = $booking->id;
                                    $arr['booking_type'] = $booking->type;
                                    $depositLog = $this->DepositLogs->patchEntity($depositLog, $arr);
                                    if ($this->DepositLogs->save($depositLog)) {
                                        $depositLog = $this->DepositLogs->patchEntity($depositLog, ['code' => "MTT" . str_pad($depositLog->id, 9, '0', STR_PAD_LEFT)]);
                                        $this->DepositLogs->save($depositLog);
                                        $user = $this->Users->patchEntity($user, ['balance' => $user->balance - $pricePaid]);
                                        $this->Users->save($user);
                                        $isValidate = true;
                                    } else {
                                        $res['message'] = 'Lỗi xảy ra trong quá trình Thanh toán, vui lòng thử lại';
                                    }
                                } else {
                                    $res['message'] = 'Lỗi xảy ra trong quá trình Thanh toán, vui lòng thử lại';
                                }


                            } else {
                                $isValidate = false;
                                $res['status'] = STT_NOT_ENOUGH_BALANCE;
                                $res['message'] = 'Số dư trong tài khoản không đủ để thực hiện giao dịch';
                                $res['data']['balance'] = $user->balance;
                            }
                        }
                    }
                    if ($isValidate) {
                        $payment = $this->Payments->find()->where(['booking_id' => $data['booking_id']])->first();
                        if (!$payment) {
                            $payment = $this->Payments->newEntity();
                        }
                        $payment = $this->Payments->patchEntity($payment, $data);
                        $this->Payments->save($payment);
                        $booking = $this->Bookings->get($data['booking_id']);
                        if ($data['type'] == PAYMENT_ONEPAY_ATM || $data['type'] == PAYMENT_ONEPAY_CREDIT || $data['type'] == PAYMENT_ONEPAY_QR) {
                            $res['status'] = STT_SUCCESS;
                            $res['data'] = [
                                'is_onepay' => true,
                                'redirect_link' => $this->onepayPayment($data['booking_id'], 1, $data['type'], $data['invoice']),
                                'success_link' => 'returnOnePaySuccess',
                                'fail_link' => 'returnOnePayFail'
                            ];
                        } else if ($data['type'] == PAYMENT_BALANCE) {
                            $saveBooking = $this->Bookings->get($data['booking_id']);
                            $saveBooking = $this->Bookings->patchEntity($saveBooking, ['agency_pay' => 1, 'is_paid' => 1, 'mail_type' => 1]);
                            $this->Bookings->save($saveBooking);
                            $res['status'] = STT_SUCCESS;
                            $res['message'] = 'success';
                            $res['data'] = $payment;
                        } else {
                            if ($payment->images) {
                                $saveBooking = $this->Bookings->get($data['booking_id']);
                                $saveBooking = $this->Bookings->patchEntity($saveBooking, ['agency_pay' => 1]);
                                $this->Bookings->save($saveBooking);
                            }
                            $booking = $this->Bookings->get($data['booking_id']);
                            $booking = $this->Bookings->patchEntity($booking, ['is_paid' => 1]);
                            $this->Bookings->save($booking);
                            $res['status'] = STT_SUCCESS;
                            $res['message'] = 'success';
                            $res['data'] = $payment;
                        }
                    }
                }
            }
            $this->set([
                'status' => $res['status'],
                'message' => $res['message'],
                'data' => $res['data'],
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

    public function getPaymentInfor($id)
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
            $this->loadModel('Configs');
            $this->loadModel('Payments');
            $this->loadModel('HotelSurcharges');
            $this->loadModel('Users');
            $data = $this->getRequest()->getQuery();

            $check = $this->Api->checkLoginApi();
            if ($check['status']) {
                $user_id = $check['user_id'];
                $role_id = $check['role_id'];
            }
            $booking = $this->Bookings->get($id, ['contain' => ['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'Vouchers', 'LandTours', 'HomeStays', 'Hotels', 'Vouchers.Hotels']]);
            if ($booking) {
                $bookings = [];
                $bookings['id'] = $booking['id'];
                $bookings['code'] = $booking['code'];
                $bookings['content_paymen'] = "Thanh toán " . $booking['code'];
                $user = $this->Users->find()->where(['id' => $booking['user_id']])->first();
                $users = [];
                $users['id'] = $user['id'];
                $users['balance'] = $user['balance'];
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
                                } else {
                                    if ($booking->payment_method == 1) {
                                        $booking->status_str = "Hoàn thành";
                                    }
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


//            $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
//            $banks = json_decode($json_banks->value, true);
//            if (!$banks) {
//                $banks = [];
//            }
//            $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
//            $bank_invoice = json_decode($json_invoice->value, true);

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
                if ($booking->booking_surcharges) {
                    foreach ($booking->booking_surcharges as $k => $surcharge) {
                        $surcharge = $this->HotelSurcharges->find()->where(['surcharge_type' => $surcharge->surcharge_type, 'hotel_id' => $booking->item_id])->first();
                        $booking->booking_surcharges[$k]->surcharge_id = $surcharge->id;
                    }
                }
                $res['data']['user'] = $users;
                $res['data']['booking'] = $bookings;
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
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
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

    public function getVinPaymentInfor($id)
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $testUrl = 'https://premium-api.product.cloudhms.io';
            $this->loadModel('Configs');
            $this->loadModel('Payments');
            $this->loadModel('Vinhmsbookings');
            $this->loadModel('Vinpayments');
            $this->loadModel('VinhmsbookingRooms');
            $this->loadModel('Users');
            $booking = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['Vinhmsbookings.id' => $id])->first();
            $data = $this->request->getQuery();
            if ($booking) {
                $bookings = [];
                $bookingPrice = $booking->price;
                $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
                $banks = json_decode($json_banks->value, true);
                if (!$banks) {
                    $banks = [];
                }
                $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
                $bank_invoice = json_decode($json_invoice->value, true);
                $payment = $this->Vinpayments->query()->where(['booking_id' => $booking->id])->first();
                $payment['images'] = json_decode($payment['images'], true);
                $user = $this->Users->find()->where(['id' => $booking['user_id']])->first();
                if ($user) {
                    $users = [];
                    $users['id'] = $user['id'];
                    $users['balance'] = $user['balance'];
                }
                $bookings['id'] = $booking['id'];
                $bookings['code'] = $booking['code'];
                $bookings['content_paymen'] = "Thanh toán " . $booking['code'];

                if (!$data && ($payment->type == PAYMENT_ONEPAY_QR || $payment->type == PAYMENT_ONEPAY_ATM || $payment->type == PAYMENT_ONEPAY_CREDIT)) {
                    $data = [];
                    if ($payment->invoice == 1) {
                        $data['vpc_AccessCode'] = ACCESSCODE_INVOICE;
                        $data['vpc_Command'] = "queryDR";
                        $data['vpc_MerchTxnRef'] = $payment->merchtxnref;
                        $data['vpc_Merchant'] = MERCHANT_ID_INVOICE;
                        $data['vpc_Password'] = ONEPAY_PASSWORD_INVOICE;
                        $data['vpc_User'] = ONEPAY_USER_INVOICE;
                        $data['vpc_Version'] = "2";
//                    ksort($data);
                        $md5HashData = "";
                        foreach ($data as $key => $value) {
                            $md5HashData .= $key . "=" . $value . "&";
                        }
                        $md5HashData = rtrim($md5HashData, "&");
                        $data['vpc_SecureHash'] = strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', HASHCODE_INVOICE)));
                        $md5HashData .= "&vpc_SecureHash=" . $data['vpc_SecureHash'];
                        $header = [];
                        $header[] = 'Content-Type: application/x-www-form-urlencoded';
//                    unset($data['vpc_TxnResponseCode']);
                        $url = "'https://onepay.vn/msp/api/v1/vpc/invoices/queries";
                    } else {
                        $data['vpc_AccessCode'] = ACCESSCODE_NO_INVOICE;
                        $data['vpc_Command'] = "queryDR";
                        $data['vpc_MerchTxnRef'] = $payment->merchtxnref;
                        $data['vpc_Merchant'] = MERCHANT_ID_NO_INVOICE;
                        $data['vpc_Password'] = ONEPAY_PASSWORD_NO_INVOICE;
                        $data['vpc_User'] = ONEPAY_USER_NO_INVOICE;
                        $data['vpc_Version'] = "2";
//                    ksort($data);
                        $md5HashData = "";
                        foreach ($data as $key => $value) {
                            $md5HashData .= $key . "=" . $value . "&";
                        }
                        $md5HashData = rtrim($md5HashData, "&");
                        $data['vpc_SecureHash'] = strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', HASHCODE_NO_INVOICE)));
                        $md5HashData .= "&vpc_SecureHash=" . $data['vpc_SecureHash'];
                        $header = [];
                        $header[] = 'Content-Type: application/x-www-form-urlencoded';
//                    unset($data['vpc_TxnResponseCode']);
                        $url = "'https://onepay.vn/msp/api/v1/vpc/invoices/queries";
                    }


                    $var = $this->Util->postJsonEncoded($url, $md5HashData, $header);
                    $arrayData = explode('&', $var);
                    $arrayOnepayData = [];
                    foreach ($arrayData as $k => $singleValue) {
                        $tmpArr = explode('=', $singleValue);
                        $arrayOnepayData[$tmpArr[0]] = $tmpArr[1];
                    }
                    if ($payment->onepaystatus != $arrayOnepayData['vpc_TxnResponseCode']) {
                        $payment = $this->Vinpayments->patchEntity($payment, ['onepaystatus' => $arrayOnepayData['vpc_TxnResponseCode']]);
                        $this->Vinpayments->save($payment);
                    }

                } else {
                    if ($payment->type == PAYMENT_ONEPAY_CREDIT || $payment->type == PAYMENT_ONEPAY_ATM || $payment->type == PAYMENT_ONEPAY_QR) {
                        $payment = $this->Vinpayments->patchEntity($payment, ['merchtxnref' => $data['vpc_MerchTxnRef'], 'onepaystatus' => $data['vpc_TxnResponseCode']]);
                        $this->Vinpayments->save($payment);
                    }
                    $arrayOnepayData = $data;
                    if ($payment->onepaystatus == 0) {
                        $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['Vinhmsbookings.id' => $id])->first();
                        if (!$bookingSendmail->reservation_id) {
                            $res = $this->Util->createBooking($testUrl, $bookingSendmail);
                            if ($res['isSuccess']) {
                                $listReservationId = [];
                                $bookingSendmail = $this->Vinhmsbookings->patchEntity($bookingSendmail, ['reservation_id' => $res['data']['reservations'][0]['itineraryNumber']]);
                                $this->Vinhmsbookings->save($bookingSendmail);
                                foreach ($bookingSendmail->vinhmsbooking_rooms as $vinbkroomKey => $vinhmsbooking_room) {
                                    $vinroom_savedata = $this->VinhmsbookingRooms->get($vinhmsbooking_room->id);
                                    $vinroom_savedata = $this->VinhmsbookingRooms->patchEntity($vinroom_savedata, [
                                        'vinhms_reservation_id' => $res['data']['reservations'][$vinbkroomKey]['reservationID'],
                                        'vinhms_confirmation_code' => $res['data']['reservations'][$vinbkroomKey]['confirmationNumber']
                                    ]);
                                    $this->VinhmsbookingRooms->save($vinroom_savedata);
                                    $listReservationId[] = $res['data']['reservations'][$vinbkroomKey]['reservationID'];
                                }
                                $resCommit = $this->getGuaranteeMethod($listReservationId);
                                if ($resCommit) {
                                    $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['Vinhmsbookings.id' => $id])->first();
                                    $this->_sendVinCodeEmail($bookingSendmail);
                                    $this->_sendBookingToVin($bookingSendmail);
                                }
                            }
                        }
                    }
                }

                $atm_and_qr = 0;
                $visa = 0;

                if ($booking->user_id != $booking->sale_id) {
                    $price = $booking->price - $booking->revenue;
                    $atm_and_qr = round($price / (100 - 1.1) * 100 + 1760 - $price);
                    $visa = round($price / (100 - 2.75) * 100 + 7150 - $price);
                } else {
                    $price = $booking->price;
                    $atm_and_qr = round($booking->price / (100 - 1.1) * 100 + 1760 - $booking->price);
                    $visa = round($booking->price / (100 - 2.75) * 100 + 7150 - $booking->price);
                }
                $user = $this->Users->get($check['user_id']);
                $payment_method = [
                    'credit' => [
                        'available' => $user->balance > $price ? true : false,
                        'fee' => 0,
                        'balande' => $user->balance,
                        'type' => PAYMENT_BALANCE,
                    ],
                    'banking' => [
                        'available' => true,
                        'fee' => 0,
                        'type' => PAYMENT_TRANSFER,
                    ],
                    'visa' => [
                        'available' => true,
                        'fee' => $visa,
                        'type' => PAYMENT_ONEPAY_CREDIT,
                    ],
                    'atm' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_ATM,
                    ],
                    'qr' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_QR,
                    ],
                ];

                if (isset($arrayOnepayData['vpc_TxnResponseCode']) && $arrayOnepayData['vpc_TxnResponseCode'] == 0) {
                    $booking = $this->Vinhmsbookings->patchEntity($booking, ['agency_pay' => 1, 'is_paid' => 1, 'status' => 2]);
                    $this->Vinhmsbookings->save($booking);
                }
                if ($this->Auth->user('role_id') == 2) {
                    $isSaleVinBooking = true;
                } else {
                    $isSaleVinBooking = false;
                }
                $dataResponse = [
                    'user' => $users,
                    'booking' => $bookings,
                    'transaction_fee' => $payment_method,
                    'banks' => $banks,
                    'bank_invoice' => $bank_invoice,
                    'payment' => $payment,
                    'arrayOnepayData' => $arrayOnepayData,
                    'bookingPrice' => $booking['price'] - $booking['sale_discount'] - $booking['agency_discount'],
                    'agencyPrice' => $booking['price'] - $booking['sale_discount'] - $booking['agency_discount'] - $booking['revenue'],
                ];
                $this->set([
                    'status' => '1',
                    'message' => 'success',
                    'data' => $dataResponse,
                    '_serialize' => ['status', 'message', 'data']
                ]);
//            $this->set(compact('headerType', 'title', 'booking', 'banks', 'bank_invoice', 'bookingPrice', 'payment', 'arrayOnepayData', 'isSaleVinBooking'));?
            } else {
                $this->set([
                    'status' => STT_ERROR,
                    'message' => 'Không tìm thấy booking',
                    'data' => '',
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

    public function paymentVinpearl($id)
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $this->loadModel('Vinhmsbookings');
            $this->loadModel('Vinpayments');
            $this->loadModel('Configs');
            $this->loadModel('Hotels');
            $this->loadModel('Users');
            $booking = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Vinpayments'])->where(['Vinhmsbookings.id' => $id])->first();
            $numRoom = $numAdult = $numChild = $numKid = 0;
            $data = [];
            if ($booking) {
                $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();

                $banks = json_decode($json_banks->value, true);
                if (!$banks) {
                    $banks = [];
                }
                $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
                $bank_invoice = json_decode($json_invoice->value, true);
                $vinhmsbooking_rooms = [];
                foreach ($booking->vinhmsbooking_rooms as $room) {
                    $numRoom++;
                    $numAdult += $room->num_adult;
                    $numChild += $room->num_child;
                    $numKid += $room->num_kid;
                    if (isset($room->room_index)) {

                    } else {
                        $room->room_index = 0;
                    }
                    if (isset($vinhmsbooking_rooms[$room->room_index])) {
                        $vinhmsbooking_rooms[$room->room_index]['price'] += $room->price;
                        $vinhmsbooking_rooms[$room->room_index]['packages'][] = [
                            'vinhms_package_id' => $room->vinhms_package_id,
                            'vinhms_package_name' => $room->vinhms_package_name,
                            'vinhms_package_code' => $room->vinhms_package_code,
                            'time' => date_format($room->checkin, "d/m/Y") . ' - ' . date_format($room->checkout, "d/m/Y"),
                        ];
                    } else {
                        $vinhmsbooking_rooms[$room->room_index] = [
                            'id' => $room->id,
                            'vinhms_name' => $room->vinhms_name,
                            'price' => $room->price,
                            'num_adult' => $room->num_adult,
                            'num_kid' => $room->num_kid,
                            'num_child' => $room->num_child,
                        ];
                        $vinhmsbooking_rooms[$room->room_index]['packages'][] = [
                            'vinhms_package_id' => $room->vinhms_package_id,
                            'vinhms_package_name' => $room->vinhms_package_name,
                            'vinhms_package_code' => $room->vinhms_package_code,
                            'time' => date_format($room->checkin, "d/m/Y") . ' - ' . date_format($room->checkout, "d/m/Y"),
                        ];
                    }
                }
                $hotel = $this->Hotels->get($booking->hotel_id);


                $date = date_diff($booking->start_date, $booking->end_date);
                $atm_and_qr = 0;
                $visa = 0;

                if ($booking->user_id != $booking->sale_id) {
                    $price = $booking->price - $booking->revenue;
                    $atm_and_qr = round($price / (100 - 1.1) * 100 + 1760 - $price);
                    $visa = round($price / (100 - 2.75) * 100 + 7150 - $price);
                } else {
                    $price = $booking->price;
                    $atm_and_qr = round($booking->price / (100 - 1.1) * 100 + 1760 - $booking->price);
                    $visa = round($booking->price / (100 - 2.75) * 100 + 7150 - $booking->price);
                }
                $payment_booking = $this->Vinpayments->find()->where(['booking_id' => $booking['id']])->first();
                $payment_format = [];
                if ($payment_booking) {
                    $payment_format['images'] = $payment_booking->images ? json_decode($payment_booking->images,true) : '';
                    if ($payment_booking->invoice == 1) {
                        $payment_format['invoice'] = 'Xuất hóa đơn';
                    } else {
                        $payment_format['invoice'] = 'Không xuất hóa đơn';
                    }
                    if ($payment_booking->type == PAYMENT_ONEPAY_CREDIT || $payment_booking->type == PAYMENT_ONEPAY_ATM || $payment_booking->type == PAYMENT_ONEPAY_QR) {
                        switch ($payment_booking->onepaystatus) {
                            case 0:
                                $status = 1;
                                break;
                            default:
                                $status = 0;
                                break;
                        }
                    } elseif ($payment_booking->type == PAYMENT_BALANCE) {
                        $status = 1;
                    } else {
                        if (json_decode($payment_booking->images)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                    }

                    $payment_format['status'] = $status;
                    if ($payment_booking->type == PAYMENT_BALANCE) {
                        $payment_format['type'] = 'Credit';
                    } elseif ($payment_booking->type == PAYMENT_TRANSFER) {
                        $payment_format['type'] = 'Chuyển khoản ngân hàng';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_CREDIT) {
                        $payment_format['type'] = 'Thẻ tín dụng / Ghi nợ';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_ATM) {
                        $payment_format['type'] = 'ATM / Tài khoản ngân hàng';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_QR) {
                        $payment_format['type'] = 'Quét mã QR';
                    }
                }
                $status_str = $this->Util->getStatusBookingVinpearl($booking, 3);
                $payment_format['status_str'] = $status_str->status_str;

                $user = $this->Users->get($check['user_id']);
                $payment_method = [
                    'credit' => [
                        'available' => $user->balance > $price ? true : false,
                        'fee' => 0,
                        'balande' => $user->balance,
                        'type' => PAYMENT_BALANCE,
                        'name' => 'Credit',
                        'description' => '',
                        'icon' => 'files/icons/credit.png',
                    ],
                    'banking' => [
                        'available' => true,
                        'fee' => 0,
                        'type' => PAYMENT_TRANSFER,
                        'name' => 'Chuyển khoản ngân hàng',
                        'description' => 'Không thu phí. Phòng được đặt khi Mustgo xác nhận tiền đã nổi trên tài khoản. Tình trạng phòng có thể hết trong khi chờ Mustgo Xác nhận',
                        'icon' => 'files/icons/banking.png',
                    ],
                    'visa' => [
                        'available' => true,
                        'fee' => $visa,
                        'type' => PAYMENT_ONEPAY_CREDIT,
                        'name' => 'Thẻ tín dụng / Ghi nợ',
                        'description' => '',
                        'icon' => 'files/icons/visa.png',
                    ],
                    'atm' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_ATM,
                        'name' => 'ATM / Tài khoản ngân hàng',
                        'description' => '',
                        'icon' => 'files/icons/atm.png',
                    ],
                    'qr' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_QR,
                        'name' => 'Quét mã QR',
                        'description' => '',
                        'icon' => 'files/icons/qr.png',
                    ],
                ];
                $data['booking']['id'] = $booking['id'];
                $data['booking']['reservation_id'] = $booking['reservation_id'];
                $data['booking']['code'] = $booking['code'];
                $data['booking']['vinhms_code'] = $booking['vinhms_code'];
                $data['booking']['vinhms_hotel_code'] = $booking['vinhms_hotel_code'];
                $data['booking']['user_id'] = $booking['user_id'];
                $data['booking']['phone'] = $booking['phone'];
                $data['booking']['email'] = $booking['email'];
                $data['booking']['nationality'] = $booking['nationality'];
                $data['booking']['nation'] = $booking['nation'];
                $data['booking']['note'] = $booking['note'];
                $data['booking']['name'] = $booking['first_name'] . " " . $booking['sur_name'];
                $data['booking']['sale_id'] = $booking['sale_id'];
                $data['booking']['hotel_id'] = $booking['hotel_id'];
                $data['booking']['hotel_name'] = $hotel->name;
                $data['booking']['address'] = $hotel->address;
                $data['booking']['start_date'] = $booking['start_date'];
                $data['booking']['end_date'] = $booking['end_date'];
                $data['booking']['status'] = $booking['status'];
                $data['booking']['price'] = $booking['price'];
                $data['booking']['revenue'] = $booking['revenue'];
                $data['booking']['sale_revenue'] = $booking['sale_revenue'];
                $data['booking']['sale_revenue_default'] = $booking['sale_revenue_default'];
                $data['booking']['change_price'] = $booking['change_price'];
                $data['booking']['created'] = $booking['created'];
                $data['booking']['creator_type'] = $booking['creator_type'];
                $data['booking']['vinhmsbooking_rooms'] = $vinhmsbooking_rooms;
                $data['booking']['payment_format'] = $payment_format;

                $booking_total_price = [
                    'booking_total_price' => ($booking->price - $booking->sale_discount - $booking->agency_discount),
                    'price_dai_ly_thanh_toan' => ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                    'content' => "Thanh toán " . $booking->code
                ];

                $dataRespone = [
                    'booking' => $data['booking'],
                    'transaction_fee' => $payment_method,
                    'bank_invoice' => $bank_invoice,
                    'banks' => $banks,
                    'payment' => ['images'=> isset($payment_format['images']) && $payment_format['images'] ? $payment_format['images'] : ''],
                    'booking_total_price' => $booking_total_price,
                ];

                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Success',
                    'data' => $dataRespone,
                    '_serialize' => ['status', 'message', 'data']
                ]);


            } else {
                $this->set([
                    'status' => STT_ERROR,
                    'message' => 'Không tìm thấy booking',
                    'data' => '',
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

//        $this->set(compact('title', 'headerType', 'numRoom', 'numAdult', 'numChild', 'numKid', 'booking', 'banks', 'bank_invoice', 'date', 'isSaleVinBooking', 'hotel'));
    }

    public function listBookingVinCode()
    {
        $this->loadModel('Vinhmsbookings');
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            if ($check['role_id'] == 3) {
                $dataRespone = [];
                $dataCodes = $this->Vinhmsbookings->find()->where(['user_id' => $check['user_id']])->toList();
                foreach ($dataCodes as $key => $dataCode) {
                    $dataRespone[$key] = $dataCode->code;
                }
                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Success',
                    'data' => $dataRespone,
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
            if ($check['role_id'] == 2) {
                $dataRespone = [];
                $dataCodes = $this->Vinhmsbookings->find()->where(['sale_id' => $check['user_id']])->toList();
                foreach ($dataCodes as $key => $dataCode) {
                    $dataRespone[$key] = $dataCode->code;
                }
                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Success',
                    'data' => $dataRespone,
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

    public function addBookingVinpearl()
    {
        $this->loadModel('Users');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('VinhmsbookingRooms');
        $this->loadModel('Hotels');
        $this->loadModel('Vinhmsallotments');
        $this->loadModel('Vinrooms');
        $check = $this->Api->checkLoginApi();
        $status = STT_ERROR;
        $message = '';
        $data = [];
        if ($check['status']) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            $error = [];
            if ($user) {
                $data = $this->request->getData();
                Log::write('debug', json_encode($data));
                $isValidate = true;
                if (!isset($data['first_name']) || !$data['first_name']) {
                    $isValidate = false;
                    $error['first_name'] = 'Bạn không được để trống trường này';
                }
                if (!isset($data['sur_name']) || !$data['sur_name']) {
                    $isValidate = false;
                    $error['sur_name'] = 'Bạn không được để trống trường này';
                }
                if (!isset($data['email']) || !$data['email']) {
                    $isValidate = false;
                    $error['email'] = 'Bạn không được để trống trường này';
                }
                if (!isset($data['vin_room']) || count($data['vin_room']) == 0) {
                    $isValidate = false;
                    $error['vin_room'] = 'Bạn không được để trống trường này';
                }
                if ($isValidate) {
                    $booking = $this->Vinhmsbookings->newEntity();
                    $bookingRooms = $data['vin_room'];
                    unset($data['vin_room']);
                    $data['status'] = 1;
                    $sDateTS = $bookingRooms[0]['package'][0]['start_date'];
                    $eDateTS = $bookingRooms[0]['package'][0]['end_date'];
                    foreach ($bookingRooms as $room) {
                        $countPack = count($room['package']);
                        if (strtotime($eDateTS) < strtotime($room['package'][$countPack - 1]['end_date'])) {
                            $eDateTS = $room['package'][$countPack - 1]['end_date'];
                        }
                    }
                    $data['start_date'] = date('Y-m-d', strtotime($sDateTS));
                    $data['end_date'] = date('Y-m-d', strtotime($eDateTS));
                    if (!isset($data['vin_information'])) {
                        $data['vin_information'] = [];
                    }
                    $data['vin_information'] = json_encode($data['vin_information'], JSON_UNESCAPED_SLASHES);
                    $data['user_id'] = $user->id;
                    $data['sale_id'] = $user->parent_id;
                    $data['sale_revenue_default'] = $data['sale_revenue'];
                    $booking = $this->Vinhmsbookings->patchEntity($booking, $data);
                    $this->Vinhmsbookings->save($booking);
                    $totalPrice = 0;
                    $hotel = $this->Hotels->find()->where(['id' => $data['hotel_id']])->first();
                    $allotmentTotal = [];
                    foreach ($bookingRooms as $room) {
                        foreach ($room['package'] as $package) {
                            if (isset($allotmentTotal[$package['allotment_id']])) {
                                $allotmentTotal[$package['allotment_id']] += 1;
                            } else {
                                $allotmentTotal[$package['allotment_id']] = 1;
                            }
                        }
                    }
                    $datafFailRes = [];
                    if ($hotel) {
                        $isEnoughPackage = true;
                        $testUrl = $this->viewVars['testUrl'];
                        $bookingPrice = 0;
                        $revenueBooking = 0;
                        $salerevenueBooking = 0;
                        foreach ($bookingRooms as $roomK => $room) {
                            if ($isEnoughPackage) {
                                $dataGet = [
                                    "arrivalDate" => '',
                                    "departureDate" => '',
                                    "numberOfRoom" => 1,
                                    "propertyIds" => [$hotel->vinhms_code],
                                    "roomOccupancy" => [],
                                ];
                                if (empty($dataGet['roomOccupancy'])) {
                                    $roomOccupancy = [
                                        'numberOfAdult' => $room['num_adult'],
                                        'otherOccupancies' => [
                                            [
                                                'otherOccupancyRefCode' => 'child',
                                                'quantity' => $room['num_child']
                                            ],
                                            [
                                                'otherOccupancyRefCode' => 'infant',
                                                'quantity' => $room['num_kid']
                                            ]
                                        ]
                                    ];

                                    $dataGet['roomOccupancy'] = $roomOccupancy;
                                }
                                foreach ($room['package'] as $packageK => $package) {
                                    $packageFound = false;
                                    $dataGet['ratePlanId'] = $package['rateplan_id'];
                                    $dataGet['arrivalDate'] = date('Y-m-d', strtotime($package['start_date']));
                                    $dataGet['departureDate'] = date('Y-m-d', strtotime($package['end_date']));
                                    $dateDiff = date_diff(date_create($dataGet['arrivalDate']), date_create($dataGet['departureDate']));
                                    $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $dataGet);
                                    if (isset($dataApi['isSuccess'])) {
                                        if (!empty($dataApi['data']['rates'])) {
                                            foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
                                                if ($ratePackage['roomTypeID'] == $room['room_id'] && $ratePackage['ratePlanID'] == $package['rateplan_id']) {
                                                    $allotmentCode = '';
                                                    $firstAllotment = $ratePackage['rateAvailablity']['allotments'][0];
                                                    foreach ($ratePackage['rateAvailablity']['allotments'] as $singleAllotmentCheck) {
                                                        if ($singleAllotmentCheck['allotmentId'] == $package['allotment_id']) {
                                                            $packageFound = true;
//                                                            dd($singleAllotmentCheck['quantity'] );
                                                            if ($singleAllotmentCheck['quantity'] > $allotmentTotal[$package['allotment_id']]) {
                                                                $allotmentCode = $singleAllotmentCheck['code'];
                                                                $firstAllotment = $singleAllotmentCheck;
                                                            } else {
                                                                $isEnoughPackage = false;
                                                            }
                                                        }
                                                    }
                                                    if ($isEnoughPackage) {
                                                        $allotment = $this->Vinhmsallotments->find()->where([
                                                            'code' => $allotmentCode,
                                                            'hotel_id' => $hotel->id,
                                                            'vinroom_code' => $room['room_id'],
                                                        ])->first();
                                                        $ratePackage['rateAvailablity']['allotments'][0] = $firstAllotment;
                                                        $hasSpecialPackage = false;
                                                        if (isset($ratePackage['rateAvailablity']['allotments'][0]) && $allotment) {
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
                                                        if ($hasSpecialPackage) {
                                                            if ($allotment->sale_revenue != 0) {
                                                                if ($allotment->sale_revenue_type == 0) {
                                                                    $ratePackage['trippal_price'] = $allotment->sale_revenue * $dateDiff->days;
                                                                } else {
                                                                    $ratePackage['trippal_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotment->sale_revenue / 100);
                                                                }
                                                            }
                                                            if ($allotment->revenue != 0) {
                                                                if ($allotment->revenue_type == 0) {
                                                                    $ratePackage['customer_price'] = $allotment->revenue * $dateDiff->days;
                                                                } else {
                                                                    $ratePackage['customer_price'] = intval(intval($ratePackage['totalAmount']['amount']['amount']) * $allotment->revenue / 100);
                                                                }
                                                            }
                                                        }
                                                        foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $roomType) {
                                                            if ($roomType['id'] == $room['room_id']) {
                                                                $bookingRooms[$roomK]['room_name'] = $roomType['name'];
                                                            }
                                                        }
                                                        $bookingRooms[$roomK]['package'][$packageK]['package_name'] = $ratePackage['rateAvailablity']['ratePlan']['name'];
                                                        $bookingRooms[$roomK]['package'][$packageK]['package_code'] = $ratePackage['rateAvailablity']['ratePlanCode'];
                                                        $bookingRooms[$roomK]['package'][$packageK]['room_type_code'] = $ratePackage['rateAvailablity']['roomTypeCode'];
                                                        $bookingRooms[$roomK]['package'][$packageK]['rateplan_code'] = $ratePackage['rateAvailablity']['ratePlanCode'];
                                                        $bookingRooms[$roomK]['package'][$packageK]['default_price'] = intval($ratePackage['totalAmount']['amount']['amount']);
                                                        $bookingRooms[$roomK]['package'][$packageK]['revenue'] = intval($ratePackage['customer_price']);
                                                        $bookingRooms[$roomK]['package'][$packageK]['sale_revenue'] = intval($ratePackage['trippal_price']);
                                                        $bookingPrice += intval($ratePackage['totalAmount']['amount']['amount']) + $ratePackage['trippal_price'] + $ratePackage['customer_price'];
                                                        $revenueBooking += $ratePackage['customer_price'];
                                                        $salerevenueBooking += $ratePackage['trippal_price'];
                                                    }
                                                }
                                            }
                                            if (!$packageFound) {
                                                $isValidate = false;
                                                $status = STT_NOT_SAVE;
                                                $message = 'Không tìm thấy gói';
                                                $datafFailRes['package'][$packageK] = 'Không tìm thấy gói';
                                            }
                                            if (!$isEnoughPackage) {
                                                $isValidate = false;
                                                $status = STT_NOT_SAVE;
                                                $message = 'Không đủ gói';
                                            }
                                        }
                                    } else {
                                        $isValidate = false;
                                        $status = STT_NOT_SAVE;
                                        $message = 'Không đủ gói';
                                    }
                                }
                            } else {
                                $isValidate = false;
                                $status = STT_NOT_SAVE;
                                $message = 'Không đủ gói';
                            }
                        }
                    } else {
                        $isValidate = false;
                        $status = STT_NOT_FOUND;
                        $message = 'Hotel not found';
                    }
                    if ($isValidate) {
                        foreach ($bookingRooms as $k => $room) {
                            foreach ($room['package'] as $pK => $package) {
                                $bookingVinRoom = $this->VinhmsbookingRooms->newEntity();
                                $bookingVinRoom = $this->VinhmsbookingRooms->patchEntity($bookingVinRoom, [
                                    'room_index' => $k,
                                    'vinhms_name' => $room['room_name'],
                                    'vinhmsbooking_id' => $booking->id,
                                    'vinhms_package_id' => $package['package_id'],
                                    'vinhms_package_code' => isset($package['package_code']) ? $package['package_code'] : '',
                                    'vinhms_package_name' => isset($package['package_name']) ? $package['package_name'] : '',
                                    'vinhms_room_id' => $room['room_id'],
                                    'vinhms_rateplan_id' => $package['rateplan_id'],
                                    'vinhms_allotment_id' => $package['allotment_id'],
                                    'vinhms_room_type_code' => $room['room_type_code'],
                                    'vinhms_rateplan_code' => isset($package['rateplan_code']) ? $package['rateplan_code'] : '',
                                    'room_id' => $room['room_id'],
                                    'checkin' => date('Y-m-d', strtotime($package['start_date'])),
                                    'checkout' => date('Y-m-d', strtotime($package['end_date'])),
                                    'num_adult' => $room['num_adult'],
                                    'num_kid' => $room['num_kid'],
                                    'num_child' => $room['num_child'],
                                    'customer_note' => '',
                                    'detail_by_day' => '',
                                    'price' => isset($package['default_price']) ? str_replace(',', '', $package['default_price']) : 0,
                                    'revenue' => isset($room['revenue']) ? str_replace(',', '', $room['revenue']) : 0,
                                    'sale_revenue' => isset($room['sale_revenue']) ? str_replace(',', '', $room['sale_revenue']) : 0,
                                ]);
                                $this->VinhmsbookingRooms->save($bookingVinRoom);
                            }
                        }
                        $booking = $this->Vinhmsbookings->patchEntity($booking, [
                            'code' => "MVP" . str_pad($booking->id, 9, '0', STR_PAD_LEFT),
                            'price' => $bookingPrice,
                            'price_default' => $bookingPrice,
                            'sale_revenue' => $salerevenueBooking,
                            'revenue' => $revenueBooking,
                            'sale_revenue_default' => $salerevenueBooking,
                        ]);
                        $booking['start_date'] = date('Y-m-d', strtotime($booking['start_date']));
                        $booking['end_date'] = date('Y-m-d', strtotime($booking['end_date']));
//                        $this->Util->notifyCountNewBooking($data['sale_id']);
                        $this->Vinhmsbookings->save($booking);
                        $status = STT_SUCCESS;
                        $message = 'Success';
                        $data = [
                            'id' => $booking->id,
                            'code' => $booking->code,
                        ];
                    } else {
                        $data = $datafFailRes;
                    }
                } else {
                    $status = STT_NOT_VALIDATION;
                    $message = 'Not Validation';
                    $data = $error;
                }
            } else {
                $status = STT_NOT_FOUND;
                $message = 'User Not Found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not Logged In';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function listBookingLandtourCode()
    {
        $this->loadModel('Bookings');
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            if ($check['role_id'] == 3) {
                $dataRespone = [];
                $dataCodes = $this->Bookings->find()->where(['user_id' => $check['user_id'], 'type' => 3])->toList();
                foreach ($dataCodes as $dataCode) {
                    $dataRespone[] = $dataCode->code;
                }
                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Success',
                    'data' => $dataRespone,
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
            if ($check['role_id'] == 2) {
                $dataRespone = [];
                $dataCodes = $this->Bookings->find()->where(['sale_id' => $check['user_id'], 'booking_type' => 3])->toList();
                foreach ($dataCodes as $key => $dataCode) {
                    $dataRespone[$key] = $dataCode->code;
                }
                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Success',
                    'data' => $dataRespone,
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

    public function listBookingCode($id)
    {
        $this->loadModel('UserTransactions');
        $this->loadModel('Bookings');

        $check = $this->Api->checkLoginApi();

        if ($check['status']) {
            $role_id = $check['role_id'];
            $user_id = $check['user_id'];

            $condition = [];

            if ($role_id == 3) {
                $condition['user_id'] = $user_id;
            }
            if ($role_id == 2 || $role_id == 1) {
                if (isset($id)) {
                    $condition['user_id'] = $id;
                }
            }
            $listBookingCode = $this->Bookings->find()->where($condition)->orderDesc('id')->toArray();

            $listCode = [];

            foreach ($listBookingCode as $key => $bookingCode) {
                $listCode[] = $bookingCode['code'];
            }

            $res['status'] = STT_SUCCESS;
            $res['data'] = $listCode;

            $this->set([
                'status' => $res['status'],
                'message' => 'Thành công',
                'data' => $res['data'],
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

    public function savePaymentVinpearl()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $testUrl = $this->viewVars['testUrl'];
            $this->viewBuilder()->enableAutoLayout(false);
            $this->loadModel('Vinpayments');
            $this->loadModel('Vinhmsbookings');
            $this->loadModel('VinhmsbookingRooms');
            $this->loadModel('Users');
            $this->loadModel('DepositLogs');
            $dataRequest = $this->request->getData();
            $data = [
                'booking_id' => $dataRequest['booking_id'],
                'type' => $dataRequest['type'],
                'invoice' => $dataRequest['invoice'],
                'invoice_information' => $dataRequest['invoice_information'],
                'images' => isset($dataRequest['images']) ? json_encode($dataRequest['images']) : '',
            ];
            $errors = '';
            //code moi
            $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên', 'is_onepay' => false, 'redirect_link' => '', 'booking_code' => ''];
            $validateType = $this->Vinpayments->newEntity($data, ['validate' => 'paymentType']);
            if ($validateType->getErrors()) {
                $errors = $validateType->getErrors();
            } else {
                $isValidate = false;
                if (isset($data['type'])) {
                    if ($data['type'] == PAYMENT_BALANCE) {
                        $user = $this->Users->get($check['user_id']);
                        $vinBooking = $this->Vinhmsbookings->get($data['booking_id']);
                        if ($user->balance < $vinBooking->price - $vinBooking->agency_discount) {
                            $isValidate = false;
                            $response['status'] = STT_NOT_ENOUGH_BALANCE;
                            $response['message'] = 'Số dư trong tài khoản không đủ để thực hiện giao dịch';
                            $response['data']['balance'] = $user->balance;
                        } else {
                            $isValidate = true;
                        }
                    } else {
                        if ($data['type'] == PAYMENT_TRANSFER) {
                            $validateInvoice = $this->Vinpayments->newEntity($data, ['validate' => 'paymentInvoice']);
                            if ($validateInvoice->getErrors()) {
                                $errors = $validateInvoice->getErrors();
                                $errors = $errors['invoice_information']['_empty'];
                            } else {
                                if ($data['invoice'] == 1) {
                                    $validateInvoiceExport = $this->Vinpayments->newEntity($data, ['validate' => 'paymentExportInvoice']);
                                    if ($validateInvoiceExport->getErrors()) {
                                        $errors = $validateInvoiceExport->getErrors();
                                        $errors = $errors['invoice_information']['_empty'];
                                    } else {
                                        $isValidate = true;
                                    }
                                } else {
                                    $isValidate = true;
                                }
                            }
                        } elseif ($data['type'] == PAYMENT_ONEPAY_ATM || $data['type'] == PAYMENT_ONEPAY_CREDIT || $data['type'] == PAYMENT_ONEPAY_QR) {
                            $isValidate = true;
                        }
                    }
                    //validate data
                    if ($isValidate) {
                        $booking = $this->Vinhmsbookings->get($data['booking_id'], ['contain' => ['Hotels', 'VinhmsbookingRooms', 'Users', 'Vinpayments']]);
                        $payment = $this->Vinpayments->find()->where(['booking_id' => $data['booking_id']])->first();
                        if (!$payment) {
                            $payment = $this->Vinpayments->newEntity();
                        }
                        $payment = $this->Vinpayments->patchEntity($payment, $data);
                        $this->Vinpayments->save($payment);
                        if ($data['type'] == PAYMENT_ONEPAY_ATM || $data['type'] == PAYMENT_ONEPAY_CREDIT || $data['type'] == PAYMENT_ONEPAY_QR) {
                            $enoughPackage = $this->Util->checkEnoughPackage($testUrl, $booking);
                            if ($enoughPackage) {
                                $response['data'] = [
                                    'is_onepay' => true,
                                    'redirect_link' => $this->onepayPayment($data['booking_id'], 2, $data['type'], $data['invoice']),
                                    'success_link' => 'returnOnePaySuccess',
                                    'fail_link' => 'returnOnePayFail'
                                ];
                                $response['success'] = STT_SUCCESS;
                            } else {
                                $response['success'] = STT_NOT_VALIDATION;
                                $response['message'] = 'Không đủ gói';
                            }
                        }
                        elseif ($data['type'] == PAYMENT_BALANCE) {
                            $user = $this->Users->get($check['user_id']);
                            // Gửi booking mới
                            $enoughPackage = $this->Util->checkEnoughPackage($testUrl, $booking);
                            if ($enoughPackage) {
                                $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['Vinhmsbookings.id' => $data['booking_id']])->first();
                                $resBookingVin = $this->Util->createBooking($testUrl, $bookingSendmail);
                                if (isset($resBookingVin['isSuccess']) && !empty($resBookingVin['isSuccess'])) {
                                    $bookingSendmail = $this->Vinhmsbookings->patchEntity($bookingSendmail, ['reservation_id' => $resBookingVin['data']['reservations'][0]['itineraryNumber']]);
                                    $this->Vinhmsbookings->save($bookingSendmail);
                                    $listReservationId = [];
                                    foreach ($bookingSendmail->vinhmsbooking_rooms as $vinbkroomKey => $vinhmsbooking_room) {
                                        foreach ($vinhmsbooking_room['packages'] as $pK => $package) {
                                            $vinroom_savedata = $this->VinhmsbookingRooms->get($package->id);
                                            $vinroom_savedata = $this->VinhmsbookingRooms->patchEntity($vinroom_savedata, [
                                                'vinhms_reservation_id' => $resBookingVin['data']['reservations'][$vinbkroomKey]['reservationID'],
                                                'vinhms_confirmation_code' => $resBookingVin['data']['reservations'][$vinbkroomKey]['confirmationNumber']
                                            ]);

                                            $this->VinhmsbookingRooms->save($vinroom_savedata);
                                        }
                                        $listReservationId[] = $resBookingVin['data']['reservations'][$vinbkroomKey]['reservationID'];
                                    }
                                    $resCommit = $this->getGuaranteeMethod($listReservationId);
                                    if ($resCommit) {
                                        $depositLog = $this->DepositLogs->newEntity();
                                        $depositLog = $this->DepositLogs->patchEntity($depositLog, [
                                            'user_id' => $booking->user_id,
                                            'creator_id' => $check['user_id'],
                                            'title' => 'Thanh toán booking mã ' . $booking->code,
                                            'amount' => 0 - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                                            'balance' => $user->balance - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                                            'type' => 2,
                                            'status' => 1,
                                            'booking_id' => $booking->id,
                                            'booking_type' => VINPEARL
                                        ]);
                                        $this->DepositLogs->save($depositLog);

                                        $depositLog = $this->DepositLogs->patchEntity($depositLog, ['code' => "MTT" . str_pad($depositLog->id, 9, '0', STR_PAD_LEFT)]);
                                        $this->DepositLogs->save($depositLog);
                                        // Trừ balance
                                        $user = $this->Users->patchEntity($user, ['balance' => $user->balance - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount)]);
                                        $this->Users->save($user);
                                        $vinBooking = $this->Vinhmsbookings->patchEntity($vinBooking, ['mail_type' => 1]);
                                        $this->Vinhmsbookings->save($vinBooking);
                                        $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['Vinhmsbookings.id' => $vinBooking->id])->first();
                                        $resSendMail = $this->_sendVinCodeEmail($bookingSendmail);
                                        $this->_sendBookingToVin($bookingSendmail);
                                        if ($resSendMail['success']) {
                                            $bookingSendmail = $this->Vinhmsbookings->patchEntity($bookingSendmail, ['status' => 2]);
                                            $this->Vinhmsbookings->save($bookingSendmail);
                                        }
                                        $response['message'] = "Success";
                                        $response['success'] = STT_SUCCESS;
                                    } else {
                                        $response['message'] = "Không tạo được booking trên Portal, liên hệ Sale để gắn code";
                                        $response['success'] = STT_ERROR;
                                    }
                                } else {
                                    $response['debug'] = $resBookingVin;
                                }
                            } else {
                                $response['success'] = STT_NOT_VALIDATION;
                                $response['message'] = 'Không đủ gói';
                            }

                        } elseif ($data['type'] == PAYMENT_TRANSFER) {
                            $response['success'] = STT_SUCCESS;
                            $response['message'] = 'Success';
                        }
                        if ($payment->images) {
                            $saveBooking = $this->Vinhmsbookings->get($data['booking_id']);
                            $saveBooking = $this->Vinhmsbookings->patchEntity($saveBooking, ['agency_pay' => 1]);
                            $this->Vinhmsbookings->save($saveBooking);
                        }

                        $booking = $this->Vinhmsbookings->patchEntity($booking, ['is_paid' => 1]);
                        $this->Vinhmsbookings->save($booking);
                        $response['booking_code'] = $booking->code;
                    }
                } else {
                    $response['success'] = STT_ERROR;
                    $response['message'] = 'Kiểm tra lại type gửi lên';
                }
            }
            $this->set('_jsonOptions', JSON_UNESCAPED_UNICODE);
            $this->set([
                'status' => $response['success'],
                'message' => $errors,
                'data' => $response,
                '_serialize' => ['status', 'message', 'data']
            ]);
            //end code moi
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function calPriceVinpearl()
    {
        $check = $this->Api->checkLoginApi();

        if ($check['status']) {
            $dataGet = $this->request->getData();
//            dd($dataGet);
            Log::debug('Cal price vinpearl log:' . json_encode($dataGet));
            $arr = [];
            $list = [];
            $text_number_night = 0;
            $discount = 0;
            $total = 0;
            $vinRooms = $dataGet['vinroom'];
            $date_start = date('Y-m-d');
            $date_end = date('Y-m-d');
            foreach ($vinRooms as $vinRoom) {
                $data = [];
                $number_night = 0;
                $packages = $vinRoom['package'];
                $total_price = 0;
                foreach ($packages as $package) {
                    $price = [];
                    $price['price'] = $package['price'];
                    $total_price += $price['price'];
                    $discount += $package['revenue'];
                    $startDate = isset($package['fromDate']) && $package['fromDate'] ? date('Y-m-d', strtotime($package['fromDate'])) : date('Y-m-d');
                    $endDate = isset($package['toDate']) && $package['toDate'] ? date('Y-m-d', strtotime($package['toDate'])) : date('Y-m-d');
                    $dateDiff = date_diff(date_create($startDate), date_create($endDate));
                    $number_night += $dateDiff->days;
                    $price['number_night'] = $dateDiff->days;
                    $data[] = $price;
                }
                $total += $total_price;
                $list['total_price'] = $total_price;
                $list['package'] = $data;
                $date_start = isset($packagse[0]['fromDate']) && $packages[0]['fromDate']  ? $packages[0]['fromDate'] : date('Y-m-d');
                if (strtotime($date_end) < strtotime($endDate) ){
                    $date_end = $endDate;
                }
                $list['total_number_night'] = $number_night > 0 ? $number_night + 1 . " ngày " . $number_night . " đêm" : "";
                if ($text_number_night < $number_night) {
                    $text_number_night = $number_night;
                }
                $arr[] = $list;
            }
            $finalData['total_price'] = $total;
            $finalData['total_number_night'] = $text_number_night > 0 ? $text_number_night + 1 . " ngày " . $text_number_night . " đêm" : "";
            $finalData['agency_pay_price'] = $total - $discount;
            $finalData['date'] = date('d/m/Y', strtotime($vinRooms[0]['package'][0]['fromDate'])).'-'.date('d/m/Y', strtotime($date_end));
            $finalData['discount'] = $discount;
            $finalData['vin_room'] = $arr;
            $status = STT_SUCCESS;
            $message = 'Success';

            $this->set([
                'status' => $status,
                'message' => $message,
                'data' => $finalData,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not login';
            $this->set([
                'status' => $status,
                'message' => $message,
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }


    }

    public function paymentLandtour($id)
    {
//        dd($id);
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $this->loadModel('LandTours');
            $this->loadModel('Users');
            $this->loadModel('Bookings');
            $this->loadModel('LandTourSurcharges');
            $this->loadModel('LandTourUserPrices');
            $this->loadModel('Configs');
            $booking = $this->Bookings->get($id, ['contain' => ['BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'LandTours']]);
            if ($booking) {
                $json_banks = $this->Configs->find()->where(['type' => 'bank-account-landtour'])->first();

                $banks = json_decode($json_banks->value, true);
                if (!$banks) {
                    $banks = [];
                }
                $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice-landtour'])->first();
                $bank_invoice = json_decode($json_invoice->value, true);
                $atm_and_qr = 0;
                $visa = 0;

                if ($booking->user_id != $booking->sale_id) {
                    $price = $booking->price - $booking->revenue;
                    $atm_and_qr = round($price / (100 - 1.1) * 100 + 1760 - $price);
                    $visa = round($price / (100 - 2.75) * 100 + 7150 - $price);
                } else {
                    $price = $booking->price;
                    $atm_and_qr = round($booking->price / (100 - 1.1) * 100 + 1760 - $booking->price);
                    $visa = round($booking->price / (100 - 2.75) * 100 + 7150 - $booking->price);
                }
                $user = $this->Users->get($check['user_id']);
                $payment_method = [
                    'credit' => [
                        'available' => $user->balance > $price ? true : false,
                        'fee' => 0,
                        'balande' => $user->balance,
                        'type' => PAYMENT_BALANCE,
                    ],
                    'banking' => [
                        'available' => true,
                        'fee' => 0,
                        'type' => PAYMENT_TRANSFER
                    ],
                    'visa' => [
                        'available' => true,
                        'fee' => $visa,
                        'type' => PAYMENT_ONEPAY_CREDIT,
                    ],
                    'atm' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_ATM,
                    ],
                    'qr' => [
                        'available' => true,
                        'fee' => $atm_and_qr,
                        'type' => PAYMENT_ONEPAY_QR,
                    ],
                ];
                $dataRespone = [
                    'transaction_fee' => $payment_method,
                    'bank_invoice' => $bank_invoice,
                    'banks' => $banks,
                    'booking' => $booking,
                ];

                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Success',
                    'data' => $dataRespone,
                    '_serialize' => ['status', 'message', 'data']
                ]);
            } else {
                $this->set([
                    'status' => STT_ERROR,
                    'message' => 'Không tìm thấy booking landtour',
                    'data' => '',
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
        $booking = $this->Bookings->get($id, ['contain' => ['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'LandTours', 'Hotels', 'Payments']]);
        if ($booking) {
            $bookingPrice = $booking->price;
            foreach ($booking->booking_surcharges as $booking_surcharge) {
                $bookingPrice += $booking_surcharge->price;
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


            $payment_booking = $payment = $this->Payments->find()->where(['booking_id' => $booking->id])->first();
            if (isset($payment->images)) {
                $payment->images = json_decode($payment->images, true);
            }
            $payment_format = [];
            if ($payment_booking) {
                if ($payment_booking->invoice == 1) {
                    $payment_format['invoice'] = 'Xuất hóa đơn';
                } else {
                    $payment_format['invoice'] = 'Không xuất hóa đơn';
                }
                if ($payment_booking->type == PAYMENT_ONEPAY_CREDIT || $payment_booking->type == PAYMENT_ONEPAY_ATM || $payment_booking->type == PAYMENT_ONEPAY_QR) {
                    switch ($payment_booking->onepaystatus) {
                        case 0:
                            $status = 1;
                            break;
                        default:
                            $status = 0;
                            break;
                    }
                } elseif ($payment_booking->type == PAYMENT_BALANCE) {
                    $status = 1;
                } else {
                    if ($payment_booking->images) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }
                }
                $payment_format['status'] = $status;
                if ($payment_booking->type == PAYMENT_BALANCE) {
                    $payment_format['type'] = 'Credit';
                } elseif ($payment_booking->type == PAYMENT_TRANSFER) {
                    $payment_format['type'] = 'Chuyển khoản ngân hàng';
                } elseif ($payment_booking->type == PAYMENT_ONEPAY_CREDIT) {
                    $payment_format['type'] = 'Thẻ tín dụng / Ghi nợ';
                } elseif ($payment_booking->type == PAYMENT_ONEPAY_ATM) {
                    $payment_format['type'] = 'ATM / Tài khoản ngân hàng';
                } elseif ($payment_booking->type == PAYMENT_ONEPAY_QR) {
                    $payment_format['type'] = 'Quét mã QR';
                }
            } else {
                $payment_format['status'] = 0;
            }
            if($booking->type == LANDTOUR) {
                $booking = $this->Util->getStatusBookingLandtour($booking, 3);
            } else {
                $booking = $this->Util->getStatusBooking($booking, 3);
            }
            $status_str = $booking->status_str;
            $payment_format['status_str'] = $status_str;

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
                    $booking['land_tours']['medias'] = json_decode($booking['land_tours']['media']);
                    break;
                case VOUCHER:
                    $hotline = $booking->vouchers->hotel->hotline;
                    $contractFile = $booking->vouchers->hotel->contract_file;
                    break;
            }
            if ($booking->booking_surcharges) {
                foreach ($booking->booking_surcharges as $k => $surcharge) {
                    $surcharge = $this->HotelSurcharges->find()->where(['surcharge_type' => $surcharge->surcharge_type, 'hotel_id' => $booking->item_id])->first();
                    $booking->booking_surcharges[$k]->surcharge_id = $surcharge->id;
                }
            }
            $allow_payment = false;
            if ($booking->status >= 2 || $booking->type == 3) {
                $allow_payment = true;
            }
            $res['data']['booking'] = $booking;
            $res['data']['allow_payment'] = $allow_payment;
            $res['data']['booking']['payment_format'] = $payment_format;
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

    public function reviewPaymentVinpaerl($id)
    {
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('VinhmsbookingRooms');
        $this->loadModel('Vinpayments');
        $this->loadModel('HotelSurcharges');
        $this->loadModel('Configs');
        $this->loadModel('Hotels');

        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $data = $this->getRequest()->getQuery();
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $role_id = $check['role_id'];
            $user_id = $check['user_id'];
            $booking = $this->Vinhmsbookings->get($id, ['contain' => ['VinhmsbookingRooms', 'Hotels', 'Vinpayments']]);
//            dd($booking);
            if ($booking) {
                $bookingPrice = $booking->price;
                $booking->status = $this->Util->getStatusBookingVinpearl($booking, $role_id);

                $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
                $banks = json_decode($json_banks->value, true);
                if (!$banks) {
                    $banks = [];
                }
                $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
                $bank_invoice = json_decode($json_invoice->value, true);
                $payment_booking = $payment = $this->Vinpayments->find()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
                if (isset($payment->images)) {
                    $payment->images = json_decode($payment->images, true);
                }
                $payment_format = [];
                if ($payment_booking) {
                    if ($payment_booking->invoice == 1) {
                        $payment_format['invoice'] = 'Xuất hóa đơn';
                    } else {
                        $payment_format['invoice'] = 'Không xuất hóa đơn';
                    }
                    if ($payment_booking->type == PAYMENT_ONEPAY_CREDIT || $payment_booking->type == PAYMENT_ONEPAY_ATM || $payment_booking->type == PAYMENT_ONEPAY_QR) {
                        switch ($payment_booking->onepaystatus) {
                            case 0:
                                $status = 1;
                                break;
                            default:
                                $status = 0;
                                break;
                        }
                    } elseif ($payment_booking->type == PAYMENT_BALANCE) {
                        $status = 1;
                    } else {
                        if ($payment_booking->images) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                    }
                    $payment_format['status'] = $status;
                    if ($payment_booking->type == PAYMENT_BALANCE) {
                        $payment_format['type'] = 'Credit';
                    } elseif ($payment_booking->type == PAYMENT_TRANSFER) {
                        $payment_format['type'] = 'Chuyển khoản ngân hàng';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_CREDIT) {
                        $payment_format['type'] = 'Thẻ tín dụng / Ghi nợ';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_ATM) {
                        $payment_format['type'] = 'ATM / Tài khoản ngân hàng';
                    } elseif ($payment_booking->type == PAYMENT_ONEPAY_QR) {
                        $payment_format['type'] = 'Quét mã QR';
                    }
                }
                $status_str = $this->Util->getStatusBookingVinpearl($booking, $role_id);
                $payment_format['status_str'] = $status_str['status_str'];

                $hotline = $booking->hotel['hotline'];
                $contractFile = $booking->hotel->contract_file;
                if ($booking->booking_surcharges) {
                    foreach ($booking->booking_surcharges as $k => $surcharge) {
                        $surcharge = $this->HotelSurcharges->find()->where(['surcharge_type' => $surcharge->surcharge_type, 'hotel_id' => $booking->item_id])->first();
                        $booking->booking_surcharges[$k]->surcharge_id = $surcharge->id;
                    }
                }
                $res['data']['booking'] = $booking;
                $res['data']['booking']['payment_format'] = $payment_format;
                $res['data']['banks'] = $banks;
                $res['data']['bank_invoice'][] = $bank_invoice;
                $res['data']['hotline'] = $hotline;
                $res['data']['contractFile'] = $contractFile;
                $res['data']['payment'] = $payment;
                $res['data']['bookingPrice'] = $bookingPrice;
                $res['data']['agencyPrice'] = $bookingPrice - $booking->revenue;

                $res['message'] = "success";
                $res['status'] = STT_SUCCESS;
            } else {
                $res['message'] = "Không tìm thấy đơn hàng";
                $res['status'] = STT_ERROR;
                $res['data'] = [];
            }
            $this->set([
                'status' => $res['status'],
                'message' => $res['message'],
                'data' => $res['data'],
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_ERROR,
                'message' => "Bạn chưa đăng nhập",
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }

    }


    public function onepayPayment($id, $type, $onepayType, $invoiceType)
    {
        $this->loadModel('Vinhmsbookings');
        if ($type == 1) {
            $booking = $this->Bookings->get($id, ['contain' => ['BookingSurcharges']]);
//            $paymentReturnUrl = \Cake\Routing\Router::url(['_name' => 'booking.reviewPayment', 'code' => $booking->code], true);
            $paymentReturnUrl = \Cake\Routing\Router::url('/Bookings/checkOnePayReturnURL' . '/' . $booking->code, true);
            $paymentAgainUrl = \Cake\Routing\Router::url('/returnOnePayFail', true);
        } else {
            $booking = $this->Vinhmsbookings->get($id);
//            $paymentReturnUrl = \Cake\Routing\Router::url(['_name' => 'booking.reviewVinPayment', 'code' => $booking->code], true);
            $paymentReturnUrl = \Cake\Routing\Router::url('/Bookings/checkOnePayReturnURLVin' . '/' . $booking->code, true);
//            $paymentAgainUrl = \Cake\Routing\Router::url(['_name' => 'booking.paymentVinpearl', 'code' => $booking->code], true);
            $paymentAgainUrl = \Cake\Routing\Router::url('/returnOnePayFail', true);

        }
        if ($type == 1) {
            $price = $booking->price;
            foreach ($booking->booking_surcharges as $booking_surcharge) {
                $price += $booking_surcharge->price;
            }
        } else {
            $price = 0;
            if ($booking->user_id == $booking->sale_id) {
                $price = $booking->price - $booking->agency_discount - $booking->sale_discount;
            } else {
                $price = $booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount;
            }
        }

        switch ($onepayType) {
            case PAYMENT_ONEPAY_CREDIT:
                $price = $price / (100 - 2.75) * 100 + 7150;
                break;
            case PAYMENT_ONEPAY_ATM:
                $price = $price / (100 - 1.1) * 100 + 1760;
                break;
            case PAYMENT_ONEPAY_QR:
                $price = $price / (100 - 1.1) * 100 + 1760;
                break;
        }
        $accessCode = '';
        $merchantId = '';
        $hashCode = '';
        if ($invoiceType == 1) {
//            $accessCode = '6BEB2546';
//            $merchantId = 'TESTONEPAY';
//            $hashCode = '6D0870CDE5F24F34F3915FB0045120DB';
            $accessCode = ACCESSCODE_INVOICE;
            $merchantId = MERCHANT_ID_INVOICE;
            $hashCode = HASHCODE_INVOICE;
        } else {
//            $accessCode = '6BEB2546';
//            $merchantId = 'TESTONEPAY';
//            $hashCode = '6D0870CDE5F24F34F3915FB0045120DB';
            $accessCode = ACCESSCODE_NO_INVOICE;
            $merchantId = MERCHANT_ID_NO_INVOICE;
            $hashCode = HASHCODE_NO_INVOICE;
        }
        $price = round($price);
        $price .= "00";
//        $onepayUrl = 'https://mtf.onepay.vn/paygate/vpcpay.op';
        $onepayUrl = $this->viewVars['onepayUrl'];

        $arrayData = [];
        $arrayData['vpc_Version'] = 2;
        $arrayData['vpc_Currency'] = 'VND';
        $arrayData['vpc_Command'] = 'pay';
        $arrayData['vpc_AccessCode'] = $accessCode;
        $arrayData['vpc_Merchant'] = $merchantId;
        $arrayData['vpc_Locale'] = 'vn';
        $arrayData['vpc_ReturnURL'] = $paymentReturnUrl;
        $arrayData['vpc_MerchTxnRef'] = date('YmdHis');
        $arrayData['vpc_OrderInfo'] = $booking->code;
        $arrayData['vpc_Amount'] = $price;
        $arrayData['vpc_TicketNo'] = $_SERVER['REMOTE_ADDR'];
        $arrayData['AgainLink'] = $paymentAgainUrl;
        $arrayData['Title'] = $booking->code;

        switch ($onepayType) {
            case PAYMENT_ONEPAY_CREDIT:
                $arrayData['vpc_CardList'] = "INTERNATIONAL";
                break;
            case PAYMENT_ONEPAY_ATM:
                $arrayData['vpc_CardList'] = "DOMESTIC";
                break;
            case PAYMENT_ONEPAY_QR:
                $arrayData['vpc_CardList'] = "QR";
                break;
        }
        ksort($arrayData);
        $md5HashData = "";
        $appendAmp = 0;
        $vpcURL = $onepayUrl . "?";
        foreach ($arrayData as $key => $value) {
            if (strlen($value) > 0) {
                // this ensures the first paramter of the URL is preceded by the '?' char
                if ($appendAmp == 0) {
                    $vpcURL .= urlencode($key) . '=' . urlencode($value);
                    $appendAmp = 1;
                } else {
                    $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                }
                //$md5HashData .= $value; sử dụng cả tên và giá trị tham số để mã hóa
                if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                    $md5HashData .= $key . "=" . $value . "&";
                }
            }
        }

        $md5HashData = rtrim($md5HashData, "&");
        if (strlen($hashCode) > 0) {
            //$vpcURL .= "&vpc_SecureHash=" . strtoupper(md5($md5HashData));
            // Thay hàm mã hóa dữ liệu
            $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', $hashCode)));
        }
        return $vpcURL;

    }

    public function getGuaranteeMethod($listReservationId)
    {
        $this->loadModel('VinhmsbookingRooms');
        $this->viewBuilder()->enableAutoLayout(false);
        $testUrl = $this->viewVars['testUrl'];
        $listCommitBooking = [];
        foreach ($listReservationId as $reservation) {
            $dataGetGuaranteeMethod['reservationId'] = $reservation;
            $res = $this->Util->getGuaranteeMethod($testUrl, $dataGetGuaranteeMethod);
            if ($res['isSuccess']) {
                $listCommitBooking[] = [
                    'reservationId' => $res['data']['reservationId'],
                    'guaranteeInfos' => [
                        [
                            'guaranteeRefID' => $res['data']['guaranteeMethods'][0]['detail']['id'],
                            'guaranteePolicyId' => $res['data']['guaranteeMethods'][0]['id'],
                            'guaranteeValue' => str_replace('.0', '', $res['data']['guaranteeMethods'][0]['amount']['amount']['amount'])
                        ]
                    ]
                ];
            }
        }
        $dataCommit = [
            'items' => $listCommitBooking
        ];

        $resCommit = $this->Util->batchCommitBooking($testUrl, $dataCommit);
        $countSuccess = 0;
        if ($resCommit['isSuccess']) {
            if (isset($resCommit['data'])) {
                if (isset($resCommit['data']['items']) && count($resCommit['data']['items']) > 0) {
                    foreach ($resCommit['data']['items'] as $singleRoom) {
                        if (isset($singleRoom['reservation']) && isset($singleRoom['reservation']['reservationID'])) {
                            $vinBookingRoom = $this->VinhmsbookingRooms->find()->where(['vinhms_reservation_id' => $singleRoom['reservation']['reservationID']])->first();
                            if ($vinBookingRoom) {
                                $vinBookingRoom = $this->VinhmsbookingRooms->patchEntity($vinBookingRoom, ['status' => $singleRoom['reservation']['status']]);
                                if ($this->VinhmsbookingRooms->save($vinBookingRoom)) {
                                    $countSuccess++;
                                }
                            }
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
            if ($countSuccess == 0) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    private function _sendVinCodeEmail($booking)
    {
        $this->loadComponent('Email');
        $this->loadModel('Users');
        $bodyEmail = 'Đơn hàng thanh toán cho booking: ' . $booking->code;

        $subject = "Mustgo.vn - " . $booking->code . " - Xác nhận đặt phòng - " . $booking->hotel->name . " - " . $booking->first_name . " " . $booking->sur_name . " - " . date_format($booking->start_date, "d/m/Y") . " - " . date_format($booking->end_date, "d/m/Y");
        $data_sendEmail = [
            'to' => $booking->email,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $sale = $this->Users->get($booking->sale_id);
        $response = $this->Email->sendVinCodeEmail($data_sendEmail, $sale->email, $sale->email_access_code);

        return $response;
    }

    private function _sendBookingToVin($booking)
    {
        $this->loadComponent('Email');
        $this->loadModel('Users');
        $bodyEmail = 'Đơn hàng thanh toán cho booking: ' . $booking->code;

        $subject = 'Mustgo.vn - Đặt phòng, cập nhật booking ' . $booking->reservation_id . ' - ' . $booking->hotel->name . ' - ' . $booking->first_name . ' ' . $booking->sur_name . ' - ' . date_format($booking->start_date, "d/m/Y") . ' - ' . date_format($booking->end_date, "d/m/Y");
        $mail = json_decode($booking->hotel->email, true);
        $data_sendEmail = [
            'to' => $mail,
            'subject' => $subject,
            'title' => $subject,
            'body' => $bodyEmail,
            'data' => $booking
        ];
        $sale = $this->Users->get($booking->sale_id);
        $response = $this->Email->sendBookingToVin($data_sendEmail, $sale->email, $sale->email_access_code);

        return $response;
    }

}
