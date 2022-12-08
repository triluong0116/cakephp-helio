<?php

namespace App\Controller;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\LandTourSurchargesTable $LandTourSurcharges
 * @property \App\Model\Table\BookingLandtourAccessoriesTable $BookingLandtourAccessories
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\PaymentsTable $Payments
 * @property \App\Model\Table\VinhmsbookingsTable $Vinhmsbookings
 * @property \App\Model\Table\VinhmsbookingRoomsTable $VinhmsbookingRooms
 * @property \App\Model\Table\VinpaymentsTable $Vinpayments
 * @property \App\Model\Table\VinhmsbookingTransportationsTable $VinhmsbookingTransportations
 * @property \App\Model\Table\DepositLogsTable $DepositLogs
 * @property \App\Model\Table\BookingLogsTable $BookingLogs
 * @property \App\Model\Table\ChannelbookingsTable $Channelbookings
 * @property \App\Model\Table\ChannelbookingRoomsTable $ChannelbookingRooms
 * @property \App\Model\Table\ChannelpaymentsTable $Channelpayments
 * @property \App\Controller\Component\UtilComponent $Util
 *
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookingsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Combos']
        ];
        $bookings = $this->paginate($this->Bookings);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_bookings = $this->Bookings->find()->where([
                'OR' => [
                    'Bookings.full_name LIKE' => '%' . $data . '%',
                ]
            ]);

            $number = $list_object_bookings->count();
            $bookings = $this->paginate($list_object_bookings);
            $this->set(compact('bookings', 'number', 'data'));
        } else
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
            'contain' => ['Users', 'Hotels', 'HomeStays', 'Vouchers', 'LandTours']
        ]);
        $this->set('booking', $booking);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addCombo()
    {
        $this->loadModel('Combos');
        $this->loadModel('Users');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $end_date = date('Y-m-d', strtotime($data['end_date'] . ' - 1 day'));
            $priceArray = [];
            $bookingDateArray = $this->_dateRange($data['start_date'], $end_date);
            if (intval($data['days_attended']) == count($bookingDateArray)) {
                $combo = $this->Combos->find()
                    ->contain(['Hotels',
                        'Hotels.PriceHotels'
                    ])
                    ->where(['Combos.id' => $data['item_id']])->first();
                $hotelDates = [];
                $priceCombo = 0;
                $revenue = 0;
                $index = 0;
                foreach ($combo->hotels as $hotel) {
//                    $day_attended += $hotel->_joinData->days_attended;
                    $comboHotelDates = $hotelDates = [];
                    $hotelPrices = [];
                    for ($i = 0; $i < $hotel->_joinData->days_attended; $i++) {
                        $comboHotelDates[] = $bookingDateArray[$index];
                        $revenue += $hotel->price_customer;
                        $index++;
                    }
                    foreach ($hotel->price_hotels as $price) {
//                        debug($price);
                        $price_start_date = date('Y-m-d', strtotime($price->start_date));
                        $price_end_date = date('Y-m-d', strtotime($price->end_date));

                        $tmpPriceHotel = $this->_createDateRangePriceArray($price_start_date, $price_end_date, $price->price);
                        $hotelPrices = array_merge($hotelPrices, $tmpPriceHotel);
                    }

                    foreach ($comboHotelDates as $date) {
                        if (isset($hotelPrices[$date])) {
                            $priceCombo = $priceCombo + $hotelPrices[$date] + $hotel->price_agency + $hotel->price_customer;
                        } else {
                            $priceCombo += ($hotel->price_agency + $hotel->price_customer);
                        }
                    }
                }
//                dd($priceCombo);
                $data['price'] = $priceCombo * $data['amount'];
//                dd($data['price']);
                $data['revenue'] = $revenue * $data['amount'];
                $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd-m-Y');
                $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'd-m-Y');

                $booking = $this->Bookings->newEntity();
                $booking = $this->Bookings->patchEntity($booking, $data);
                if ($this->Bookings->save($booking)) {
                    $response['success'] = true;
                    $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($booking->id, 9, '0', STR_PAD_LEFT)]);
                    $this->Bookings->save($booking);
                } else {
                    $response['message'] = ' Có lỗi xảy ra';
                }
            } else {
                $response['errors'] = ['date' => ['Phải chọn đúng khoảng thời gian Combo cung cấp!']];
            }

//            dd($response);
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function addVoucher()
    {
        $this->loadModel('Vouchers');
        $this->loadModel('Users');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $voucher = $this->Vouchers->get($data['item_id']);
            $data['end_date'] = date('Y-m-d', strtotime($data['start_date'] . ' + ' . $voucher->days_attended . ' days'));
            if ((strtotime($data['start_date']) >= strtotime($voucher->start_date)) && (strtotime($data['end_date']) <= strtotime($voucher->end_date))) {

                $data['sale_id'] = $this->Auth->user() ? $this->Auth->user('parent_id') : 0;
                $data['user_id'] = $this->Auth->user() ? $this->Auth->user('id') : 0;

                $data['type'] = VOUCHER;
                $data['item_id'] = $voucher->id;
                $data['booking_type'] = SYSTEM_BOOKING;
                if (is_numeric($data['amount'])) {
                    $data['price'] = ($voucher->price + $voucher->trippal_price + $voucher->customer_price) * $data['amount'];
                    $data['sale_revenue'] = $voucher->trippal_price * $data['amount'];
                    $data['revenue'] = $voucher->customer_price * $data['amount'];
                }
                $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd-m-Y');
                $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
                $data['payment_deadline'] = $this->Util->formatSQLDate($data['end_date'], 'Y-m-d');
                $data['status'] = 0;
                if (isset($data['other']) && !empty($data['other'])) {
                    $data['note'] = $data['other'];
                }
                $user = $this->Auth->user();
                if ($user) {
                    if ($user['role_id'] == 2 || $user['role_id'] == 1) {
                        $data['is_send_notice'] = 1;
                    }
                }
                $booking = $this->Bookings->newEntity($data, ['validate' => 'addBookingVoucher']);
                if ($booking->getErrors()) {
                    $response['errors'] = $booking->getErrors();
                    $response;
                } else {
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
                    } else {
                        $response['message'] = ' Có lỗi xảy ra';
                    }
                }
            } else {
                $response['errors'] = ['start_date' => ['Vui lòng nhập đúng khoảng thời gian Voucher.']];
            }
//            dd($response);
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function addLandtour()
    {
        $this->loadModel('LandTours');
        $this->loadModel('Users');
        $this->loadModel('LandTourSurcharges');
        $this->loadModel('LandTourUserPrices');
        $this->loadModel('BookingLandtourAccessories');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'errors' => [], 'booking_code' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingLandtour', 'associated' => ['BookingLandtours']]);
            if ($validate->getErrors()) {
                $response['errors'] = $validate->getErrors();
                $response;
            } else {
                if ($this->Auth->user()) {
                    if ($this->Auth->user('landtour_parent_id') != 0) {
                        $saleId = $this->Auth->user('landtour_parent_id');
                    } else {
                        $landtourSale = $this->Users->find()
                            ->where(['role_id' => 5])
                            ->order('rand()')
                            ->firstOrFail();
                        $currentUser = $this->Users->get($this->Auth->user('id'));
                        $currentUser = $this->Users->patchEntity($currentUser, ['landtour_parent_id' => $landtourSale->id]);
                        $this->Users->save($currentUser);
                        $saleId = $landtourSale->id;
                    }
                } else {
                    $saleId = 0;
                }
                $data['sale_id'] = $saleId;
                $data['user_id'] = $this->Auth->user() ? $this->Auth->user('id') : 0;
                if (!isset($data['accessory'])) {
                    $data['accessory'] = [0];
                }
                if (!isset($data['pickup_id'])) {
                    $data['pickup_id'] = 0;
                }
                if (!isset($data['drop_id'])) {
                    $data['drop_id'] = 0;
                }
                $landtour = $this->LandTours->get($data['land_tour_id'], ['contain' =>
                    [
                        'LandTourAccessories' => function ($q) use ($data) {
                            return $q->where(['id IN' => $data['accessory']]);
                        },
                        'LandTourUserPrices' => function ($q) use ($data) {
                            return $q->where(['user_id' => $data['user_id']]);
                        },
                        'LandTourDrivesurchages' => function ($q) use ($data) {
                            return $q->where(['id IN' => [$data['pickup_id'], $data['drop_id']]]);
                        }
                    ]]);
                $data['type'] = LANDTOUR;
                $data['item_id'] = $landtour->id;
                $data['booking_type'] = SYSTEM_BOOKING;
                $data['amount'] = intval($data['num_adult']) + intval($data['num_children']) + intval($data['num_kid']);

                $data['booking_landtour']['num_adult'] = $data['num_adult'];
                $data['booking_landtour']['num_children'] = $data['num_children'];
                $data['booking_landtour']['num_kid'] = $data['num_kid'];
                $data['booking_landtour']['pickup_id'] = $data['pickup_id'];
                $data['booking_landtour']['detail_pickup'] = $data['detail_pickup'];
                $data['booking_landtour']['drop_id'] = $data['drop_id'];
                $data['booking_landtour']['detail_drop'] = $data['detail_drop'];
                $data['booking_landtour']['landtour_id'] = $data['item_id'];


                $price = 0;
                if (isset($data['accessory']) && !empty($data['accessory'])) {
                    $booking_landtour_accessories = [];
                    foreach ($data['accessory'] as $k => $id) {
                        $booking_landtour_accessories[$k]['land_tour_accessory_id'] = $id;
                    }
                    $data['booking_landtour_accessories'] = $booking_landtour_accessories;
                    unset($data['accessory']);
                }
                $priceDefault = $landtour->price + $landtour->customer_price;
                $userPrice = $this->LandTourUserPrices->find()->where(['user_id' => $data['user_id'], 'land_tour_id' => $data['item_id']])->first();
                if ($userPrice) {
                    $priceDefault += $userPrice->price;
                    $data['sale_revenue'] = $userPrice->price * $data['booking_landtour']['num_adult'] + $userPrice->price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $userPrice->price * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                } else {
                    $priceDefault += $landtour->trippal_price;
                    $data['sale_revenue'] = $landtour->customer_price * $data['booking_landtour']['num_adult'] + $landtour->customer_price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $landtour->customer_price * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                }
                $data['revenue'] = $landtour->customer_price * $data['booking_landtour']['num_adult'] + $landtour->customer_price * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $landtour->customer_price * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                foreach ($landtour->land_tour_accessories as $accessory) {
                    $priceDefault += $accessory->adult_price;
                }
                $price = $priceDefault * $data['booking_landtour']['num_adult'] + $priceDefault * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $priceDefault * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
                $data['amount'] = $data['booking_landtour']['num_adult'] + $data['booking_landtour']['num_children'] + $data['booking_landtour']['num_kid'];
                $tempDriveSurchage = 0;
                if (count($landtour->land_tour_drivesurchages) == 1) {
                    if ($data['pickup_id'] == 0 || $data['drop_id'] == 0) {
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
                $price += $tempDriveSurchage;
                $data['booking_landtour']['drive_surchage'] = $tempDriveSurchage;

                if (isset($data['amount'])) {
                    if (is_numeric($data['amount'])) {
                        $data['price'] = $price;
                    }
                }
                $user = $this->Auth->user();
                if ($user) {
                    if ($user['role_id'] == 2 || $user['role_id'] == 1) {
                        $data['is_send_notice'] = 1;
                    }
                }
                $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd-m-Y');
                $data['end_date'] = $data['start_date'];
                $data['payment_deadline'] = $data['end_date'];
                $data['status'] = 0;
                if (isset($data['other']) && !empty($data['other'])) {
                    $data['note'] = $data['other'];
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
                $isEdit = false;
                if (isset($data['booking_id'])) {
                    $booking = $this->Bookings->get($data['booking_id'], ['contain' => 'BookingLandtours']);
                    $data['booking_landtour']['id'] = $booking->booking_landtour->id;
                    foreach ($data['booking_landtour_accessories'] as $k => $booking_landtour_accessory) {
                        $booking_landtour_accessories = $this->BookingLandtourAccessories->find()->where(['booking_id' => $booking->id, 'land_tour_accessory_id' => $booking_landtour_accessory['land_tour_accessory_id']])->first();
                        if ($booking_landtour_accessories) {
                            $data['booking_landtour_accessories'][$k]['id'] = $booking_landtour_accessories->id;
                        }
                    }
                    $isEdit = true;
                } else {
                    $booking = $this->Bookings->newEntity($data);
                }
                $booking = $this->Bookings->patchEntity($booking, $data);
                if ($this->Bookings->save($booking)) {
                    $newBooking = $this->Bookings->get($booking->id);
                    $response['success'] = true;
                    $booking = $this->Bookings->patchEntity($booking, ['code' => "MPQ" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);

                    $this->Bookings->save($booking);
                    if (!$isEdit) {
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
                    } else {
                        if ($booking->sale_id == 0 && $booking->user_id == 0) {
                            $this->Util->notifyCustomerEditBookingTelegram($booking->code);
                        } else {
                            $sale = $this->Users->get($booking->sale_id);
                            if ($sale) {
                                $this->Util->notifyAgentEditBookingTelegram($booking->code, $sale->telegram_id, $sale->telegram_username);
                            } else {
                                $this->Util->notifyCustomerEditBookingTelegram($booking->code);
                            }
                        }
                    }
                    $response['booking_code'] = $booking->code;
                } else {
                    $response['message'] = ' Có lỗi xảy ra';
                }
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function addHotel()
    {
        $this->loadModel('Hotels');
        $this->loadModel('Rooms');
        $this->loadModel('Users');
        $this->loadComponent('Util');
        $this->loadModel('BookingLogs');

        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'errors' => []];

        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $validate = $this->Bookings->newEntity($data, ['validate' => 'addBookingHotel', 'associated' => ['BookingRooms']]);
            if ($validate->getErrors()) {
                $response['errors'] = $validate->getErrors();
            } else {
                $hotel = $this->Hotels->get($data['hotel_id']);

                if (isset($data['booking_rooms']) && !empty($data['booking_rooms'])) {
                    $booking_rooms = $data['booking_rooms'];
                    $totalPrice = $totalSaleRev = $totalRev = 0;
                    $start_date = $end_date = '';
                    $totalAmount = 0;
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
                                        $response['errors']['incorrect_info'] = ['message' => $resPrice['message']];
                                        break;
                                    }
                                }
                                $totalAmount += $booking_room['num_room'];
                            } else {
                                $isAllow = false;
                                $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                            }
                        } else {
                            $isAllow = false;
                            $response['errors']['booking_rooms'][$key] = ['num_people' => ['Không được chọn phép chọn quá TỔNG SỐ LƯỢNG NGƯỜI cho phép của hạng phòng. Tối đa là ' . $roomTotalMaxPeople . ' người.']];
                        }
                        $data['booking_rooms'][$key]['start_date'] = $this->Util->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                        $data['booking_rooms'][$key]['end_date'] = $this->Util->formatSQLDate($booking_room['end_date'], 'd-m-Y');
                        if (isset($booking_room['child_ages']) && !empty($booking_room['child_ages'])) {
                            $data['booking_rooms'][$key]['child_ages'] = json_encode($booking_room['child_ages'], JSON_UNESCAPED_UNICODE);
                        }
                    }

                    if ($isAllow) {
                        foreach ($data['booking_surcharges'] as $key => $booking_surcharge) {
                            if ($booking_surcharge['surcharge_type'] == SUR_WEEKEND
                                || $booking_surcharge['surcharge_type'] == SUR_HOLIDAY
                                || $booking_surcharge['surcharge_type'] == SUR_ADULT
                                || $booking_surcharge['surcharge_type'] == SUR_CHILDREN) {
                                if (isset($booking_surcharge['price'])
                                    && ($booking_surcharge['price'] > 0)) {
                                    $data['booking_surcharges'][$key]['price'] = $this->Util->calHotelSurcharge($hotel, $booking_rooms, $booking_surcharge['surcharge_type'], 0, $booking_surcharge['id']);
                                } else {
                                    unset($data['booking_surcharges'][$key]);
                                }
                            } else {
                                if (isset($booking_surcharge['price'])
                                    && ($booking_surcharge['price'] > 0)
                                    && isset($booking_surcharge['quantity'])
                                    && ($booking_surcharge['quantity'] != '')) {
                                    $data['booking_surcharges'][$key]['price'] = $this->Util->calHotelSurcharge($hotel, $booking_rooms, $booking_surcharge['surcharge_type'], $booking_surcharge['quantity'], $booking_surcharge['id']);
                                } else {
                                    unset($data['booking_surcharges'][$key]);
                                }
                            }
                        }

                        $data['booking_surcharges'] = array_values($data['booking_surcharges']);

                        $user = $this->Users->get($this->Auth->user('id'));
                        if ($this->Auth->user()) {
                            $totalPrice = $totalPrice - $totalRev;
                            $totalRev = 0;
                            $data['payment_method'] = AGENCY_PAY;
                        } else {
                            $data['payment_method'] = AGENCY_PAY;
                        }
                        if ($this->Auth->user()) {
                            $data['sale_id'] = $user->parent_id;
                            $data['user_id'] = $this->Auth->user('id');
                        } else {
                            $sale = $this->Users->find()->where(['username' => 'datphong'])->first();
                            $data['sale_id'] = $sale->id;
                            $data['user_id'] = $sale->id;
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
                        $data['status'] = 0;
                        $data['amount'] = $totalAmount;
                        if (isset($data['other']) && !empty($data['other'])) {
                            $data['note'] = $data['other'];
                        }
                        $user = $this->Auth->user();
                        if ($user) {
                            if ($user['role_id'] == 2 || $user['role_id'] == 1) {
                                $data['is_send_notice'] = 1;
                            }
                        }
                        $isEdit = false;
                        if (!isset($booking['booking_id'])) {
                            $booking = $this->Bookings->newEntity();
                        } else {
                            $booking = $this->Bookings->get($data['booking_id']);
                            $isEdit = true;
                        }
                        $booking = $this->Bookings->patchEntity($booking, $data);
                        if ($this->Bookings->save($booking)) {
                            $newBooking = $this->Bookings->get($booking->id);
                            $response['success'] = true;
                            $booking = $this->Bookings->patchEntity($booking, ['code' => "M" . str_pad($newBooking->index_key, 9, '0', STR_PAD_LEFT)]);
                            $this->Bookings->save($booking);
                            if (!$isEdit) {
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
                            } else {
                                if ($booking->sale_id == 0 && $booking->user_id == 0) {
                                    $this->Util->notifyCustomerEditBookingTelegram($booking->code);
                                } else {
                                    $sale = $this->Users->get($booking->sale_id);
                                    if ($sale) {
                                        $this->Util->notifyAgentEditBookingTelegram($booking->code, $sale->telegram_id, $sale->telegram_username);
                                    } else {
                                        $this->Util->notifyCustomerEditBookingTelegram($booking->code);
                                    }
                                }
                            }
                            $sale = $this->Users->get($booking->sale_id);
                        } else {
                            $response['message'] = ' Có lỗi xảy ra';
                        }
                    }
                    // create log booking

                    $dataLog = [];
                    $dataLog['user_id'] = $booking['user_id'];
                    $dataLog['booking_id'] = $booking['id'];
                    $dataLog['code'] = $booking['code'];
                    $dataLog['title'] = "Booking được đặt";
                    $dataLog['comment'] = "";
                    $dataLog['type'] = 1;
                    $dataLog['status'] = 1;
                    $bookingLogs = $this->BookingLogs->newEntity();
                    $bookingLogs = $this->BookingLogs->patchEntity($bookingLogs, $dataLog);
                    $this->BookingLogs->save($bookingLogs);
                    // end  create log booking

                } else {
                    $response['message'] = 'Khách sạn hiện chưa có hạng phòng';
                    $response['errors'] = [
                        'rooms' => ['Khách sạn hiện chưa có hạng phòng']
                    ];
                }
            }

            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function addHomestay()
    {
        $this->loadModel('Homestays');
        $this->loadModel('Users');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $booking = $this->Bookings->newEntity($data, ['validate' => 'addBookingHomeStay']);
            if ($booking->getErrors()) {
                $response['errors'] = $booking->getErrors();
            } else {
                $date_start_attended = date_create(date('Y-m-d', strtotime($data['start_date'])));
                $date_end_attended = date_create(date('Y-m-d', strtotime($data['end_date'])));
                $end_date = date('d-m-Y', strtotime($data['end_date'] . ' - 1 day'));
                $bookingDateArray = $this->_dateRange($data['start_date'], $end_date);
                $homestay = $this->Homestays->get($data['item_id'], ['contain' => 'PriceHomeStays']);
                $days_attended = date_diff($date_start_attended, $date_end_attended);
                $homestay->days_attended = $days_attended->days;

                $dateArray = $this->Util->_dateRange($data['start_date'], $end_date);
                $totalPrice = 0;
                foreach ($dateArray as $date) {
                    $totalPrice += $this->Util->countingHomeStayPrice($date, $homestay);
                }

                $data['sale_id'] = $this->Auth->user() ? $this->Auth->user('parent_id') : 0;
                $data['user_id'] = $this->Auth->user() ? $this->Auth->user('id') : 0;
                $data['booking_type'] = SYSTEM_BOOKING;
                $data['type'] = HOMESTAY;
                $data['price'] = $totalPrice;
                $data['amount'] = 1;
                $data['revenue'] = $homestay->price_customer * $days_attended->days;
                $data['sale_revenue'] = $homestay->price_customer * $data['amount'];
                $data['start_date'] = $this->Util->formatSQLDate($data['start_date'], 'd-m-Y');
                $data['end_date'] = $this->Util->formatSQLDate($data['end_date'], 'd-m-Y');
                $data['payment_deadline'] = $data['end_date'];
                $data['status'] = 0;
                if (isset($data['other']) && !empty($data['other'])) {
                    $data['note'] = $data['other'];
                }
                $user = $this->Auth->user();
                if ($user) {
                    if ($user['role_id'] == 2 || $user['role_id'] == 1) {
                        $data['is_send_notice'] = 1;
                    }
                }
                $booking = $this->Bookings->newEntity();
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
                } else {
                    $response['message'] = 'Có lỗi xảy ra';
                }
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function bookingSuccess()
    {
        $title = 'Đặt Booking thành công';
        $headerType = 1;
        $this->set(compact('headerType', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
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
        $this->set(compact('booking', 'users', 'combos'));
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

    public function payment()
    {
        $this->loadModel('Configs');
        $code = $this->getRequest()->getParam('code');
        $booking = $this->Bookings->find()->contain(['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'Vouchers', 'LandTours', 'HomeStays', 'Payments'])->where(['code' => $code])->first();
        if (!$booking) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        if ($booking->is_paid == 1) {
            return $this->redirect(['controller' => 'bookings', 'action' => 'reviewPayment', 'code' => $code]);
        }
//        dd($booking);
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
            if (!isset($bank_invoice[0]['bank_name'])) {
                $bank_invoices[] = $bank_invoice;
            } else {
                $bank_invoices = $bank_invoice;
            }

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
            if (!isset($bank_invoice[0]['bank_name'])) {
                $bank_invoices[] = $bank_invoice;
            } else {
                $bank_invoices = $bank_invoice;
            }
        }
        $title = 'Thanh toán cho Booking mã ' . $booking->code;
        $headerType = 1;
        $this->set(compact('headerType', 'title', 'booking', 'banks', 'bank_invoices', 'bookingPrice'));
    }

    public function onepayPayment($id, $type, $onepayType, $invoiceType)
    {
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Channelbookings');
        if ($type == 1) {
            $booking = $this->Bookings->get($id, ['contain' => ['BookingSurcharges']]);
            $paymentReturnUrl = \Cake\Routing\Router::url(['_name' => 'booking.reviewPayment', 'code' => $booking->code], true);
            $paymentAgainUrl = \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $booking->code], true);
        } elseif ($type == 3) {
            $booking = $this->Channelbookings->get($id);
            $paymentReturnUrl = \Cake\Routing\Router::url(['_name' => 'booking.reviewVinPayment', 'code' => $booking->code], true);
            $paymentAgainUrl = \Cake\Routing\Router::url(['_name' => 'booking.paymentChannel', 'code' => $booking->code], true);
        } else {
            $booking = $this->Vinhmsbookings->get($id);
            $paymentReturnUrl = \Cake\Routing\Router::url(['_name' => 'booking.reviewVinPayment', 'code' => $booking->code], true);
            $paymentAgainUrl = \Cake\Routing\Router::url(['_name' => 'booking.paymentVinpearl', 'code' => $booking->code], true);
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
            $accessCode = ACCESSCODE_INVOICE;
            $merchantId = MERCHANT_ID_INVOICE;
            $hashCode = HASHCODE_INVOICE;
        } else {
            $accessCode = ACCESSCODE_NO_INVOICE;
            $merchantId = MERCHANT_ID_NO_INVOICE;
            $hashCode = HASHCODE_NO_INVOICE;
        }
        $price = round($price);
        $price .= "00";
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

    public function reviewPayment()
    {
        if ($this->Auth->user()) {
            $data = $this->request->getQuery();
            $title = 'Xem lại đơn hàng';
            $headerType = 1;
            $this->loadModel('Configs');
            $this->loadModel('Payments');
            $code = $this->getRequest()->getParam('code');
            $booking = $this->Bookings->find()->contain(['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'Vouchers', 'LandTours', 'HomeStays'])->where(['code' => $code])->first();
            if ($booking) {
                $bookingPrice = $booking->price;
                foreach ($booking->booking_surcharges as $booking_surcharge) {
                    $bookingPrice += $booking_surcharge->price;
                }
                $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
                $banks = json_decode($json_banks->value, true);
                if (!$banks) {
                    $banks = [];
                }
                $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
                $bank_invoice = json_decode($json_invoice->value, true);
                $payment = $this->Payments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
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
                        $payment = $this->Payments->patchEntity($payment, ['onepaystatus' => $arrayOnepayData['vpc_TxnResponseCode']]);
                        $this->Payments->save($payment);
                    }
                } else {
                    if ($payment->type == PAYMENT_ONEPAY_QR || $payment->type == PAYMENT_ONEPAY_ATM || $payment->type == PAYMENT_ONEPAY_CREDIT) {
                        $payment = $this->Payments->patchEntity($payment, ['merchtxnref' => $data['vpc_MerchTxnRef'], 'onepaystatus' => $data['vpc_TxnResponseCode']]);
                        $this->Payments->save($payment);
                    }
                    $arrayOnepayData = $data;
                }
                $this->set(compact('headerType', 'title', 'booking', 'banks', 'bank_invoice', 'bookingPrice', 'payment', 'arrayOnepayData'));
            } else {
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }
        } else {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
    }

    public function checkOnePayReturnURL($code)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $data = $this->request->getQuery();
        $title = 'Xem lại đơn hàng';
        $headerType = 1;
        $this->loadModel('Configs');
        $this->loadModel('Payments');
        $booking = $this->Bookings->find()->contain(['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtours.PickUp', 'BookingLandtours.DropDown', 'BookingLandtourAccessories', 'BookingLandtourAccessories.LandTourAccessories', 'Vouchers', 'LandTours', 'HomeStays'])->where(['code' => $code])->first();
        if ($booking) {
            $bookingPrice = $booking->price;
            foreach ($booking->booking_surcharges as $booking_surcharge) {
                $bookingPrice += $booking_surcharge->price;
            }
            $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
            $banks = json_decode($json_banks->value, true);
            if (!$banks) {
                $banks = [];
            }
            $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
            $bank_invoice = json_decode($json_invoice->value, true);
            $payment = $this->Payments->query()->where(['booking_id' => $booking->id])->orderDesc('created')->first();
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
                    $payment = $this->Payments->patchEntity($payment, ['onepaystatus' => $arrayOnepayData['vpc_TxnResponseCode']]);
                    $this->Payments->save($payment);
                }
            } else {
                if ($payment->type == PAYMENT_ONEPAY_QR || $payment->type == PAYMENT_ONEPAY_ATM || $payment->type == PAYMENT_ONEPAY_CREDIT) {
                    $payment = $this->Payments->patchEntity($payment, ['merchtxnref' => $data['vpc_MerchTxnRef'], 'onepaystatus' => $data['vpc_TxnResponseCode']]);
                    $this->Payments->save($payment);
                }
                $arrayOnepayData = $data;
            }
            if (isset($arrayOnepayData['vpc_TxnResponseCode']) && $arrayOnepayData['vpc_TxnResponseCode'] == 0) {
                return $this->redirect(['controller' => 'Bookings', 'action' => 'returnOnePaySuccess']);
            } else {
                return $this->redirect(['controller' => 'Bookings', 'action' => 'returnOnePayFail']);
            }
        } else {
            return $this->redirect(['controller' => 'Bookings', 'action' => 'returnOnePayFail']);
        }
    }

    public function checkOnePayReturnURLVin($code)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Configs');
        $this->loadModel('Payments');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Vinpayments');
        $this->loadModel('VinhmsbookingRooms');
        $booking = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['code' => $code])->first();
        $data = $this->request->getQuery();
        $title = 'Xem lại đơn hàng';
        $headerType = 1;
        if ($booking) {
            $bookingPrice = $booking->price;
            $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
            $banks = json_decode($json_banks->value, true);
            if (!$banks) {
                $banks = [];
            }
            $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
            $bank_invoice = json_decode($json_invoice->value, true);
            $payment = $this->Vinpayments->query()->where(['booking_id' => $booking->id])->first();
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
                    $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['code' => $code])->first();
                    if (!$bookingSendmail->reservation_id) {
                        $res = $this->Util->createBooking($testUrl, $bookingSendmail);
                        if (isset($res['isSuccess']) && $res['isSuccess']) {
                            $listReservationId = [];
                            $bookingSendmail = $this->Vinhmsbookings->patchEntity($bookingSendmail, ['reservation_id' => $res['data']['reservations'][0]['itineraryNumber']]);
                            $this->Vinhmsbookings->save($bookingSendmail);
                            $this->loadModel('VinhmsbookingRooms');
                            foreach ($bookingSendmail->vinhmsbooking_rooms as $vinbkroomKey => $vinhmsbooking_room) {
                                foreach ($vinhmsbooking_room['packages'] as $package) {
                                    $vinroom_savedata = $this->VinhmsbookingRooms->get($package->id);
                                    $vinroom_savedata = $this->VinhmsbookingRooms->patchEntity($vinroom_savedata, [
                                        'vinhms_reservation_id' => $res['data']['reservations'][$vinbkroomKey]['reservationID'],
                                        'vinhms_confirmation_code' => $res['data']['reservations'][$vinbkroomKey]['confirmationNumber']
                                    ]);
                                    $this->VinhmsbookingRooms->save($vinroom_savedata);
                                }
                                $listReservationId[] = $res['data']['reservations'][$vinbkroomKey]['reservationID'];
                            }
                            $resCommit = $this->getGuaranteeMethod($listReservationId);
                            if ($resCommit) {
                                $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['code' => $code])->first();
                                $this->_sendVinCodeEmail($bookingSendmail);
                                $this->_sendBookingToVin($bookingSendmail);
                            }
                        }
                    }
                }

            }
            if (isset($arrayOnepayData['vpc_TxnResponseCode']) && $arrayOnepayData['vpc_TxnResponseCode'] == 0) {
                $booking = $this->Vinhmsbookings->patchEntity($booking, ['agency_pay' => 1, 'is_paid' => 1, 'status' => 2]);
                $this->Vinhmsbookings->save($booking);
            } else {
                return $this->redirect(['controller' => 'Bookings', 'action' => 'returnOnePayFail']);
            }
            return $this->redirect(['controller' => 'Bookings', 'action' => 'returnOnePaySuccess']);
        } else {
            return $this->redirect(['controller' => 'Bookings', 'action' => 'returnOnePayFail']);
        }
    }

    public function returnOnePaySuccess()
    {
        $headerType = 1;
        $title = 'Thanh toán thành công';
        $this->set(compact('headerType', 'title'));
    }

    public function returnOnePayFail()
    {
        $headerType = 1;
        $title = 'Thanh toán thất bại';
        $this->set(compact('headerType', 'title'));
    }

    public function reviewVinPayment()
    {
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Configs');
        $this->loadModel('Payments');
        $code = $this->getRequest()->getParam('code');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Vinpayments');
        $this->loadModel('VinhmsbookingRooms');
        $booking = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['code' => $code])->first();
        $data = $this->request->getQuery();
        $title = 'Xem lại đơn hàng';
        $headerType = 1;
        if ($booking) {
            $bookingPrice = $booking->price;
            $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
            $banks = json_decode($json_banks->value, true);
            if (!$banks) {
                $banks = [];
            }
            $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
            $bank_invoice = json_decode($json_invoice->value, true);
            $payment = $this->Vinpayments->query()->where(['booking_id' => $booking->id])->first();
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
                    $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['code' => $code])->first();
                    if (!$bookingSendmail->reservation_id) {
                        $res = $this->Util->createBooking($testUrl, $bookingSendmail);
                        if ($res['isSuccess']) {
                            $listReservationId = [];
                            $bookingSendmail = $this->Vinhmsbookings->patchEntity($bookingSendmail, ['reservation_id' => $res['data']['reservations'][0]['itineraryNumber']]);
                            $this->Vinhmsbookings->save($bookingSendmail);
                            $this->loadModel('VinhmsbookingRooms');
                            foreach ($bookingSendmail->vinhmsbooking_rooms as $vinbkroomKey => $vinhmsbooking_room) {
                                foreach ($vinhmsbooking_room['packages'] as $package) {
                                    $vinroom_savedata = $this->VinhmsbookingRooms->get($package->id);
                                    $vinroom_savedata = $this->VinhmsbookingRooms->patchEntity($vinroom_savedata, [
                                        'vinhms_reservation_id' => $res['data']['reservations'][$vinbkroomKey]['reservationID'],
                                        'vinhms_confirmation_code' => $res['data']['reservations'][$vinbkroomKey]['confirmationNumber']
                                    ]);
                                    $this->VinhmsbookingRooms->save($vinroom_savedata);
                                }
                                $listReservationId[] = $res['data']['reservations'][$vinbkroomKey]['reservationID'];
                            }
                            $resCommit = $this->getGuaranteeMethod($listReservationId);
                            if ($resCommit) {
                                $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['code' => $code])->first();
                                $this->_sendVinCodeEmail($bookingSendmail);
                                $this->_sendBookingToVin($bookingSendmail);
                            }
                        }
                    }
                }

            }
            if (isset($arrayOnepayData['vpc_TxnResponseCode']) && $arrayOnepayData['vpc_TxnResponseCode'] == 0) {
                $booking = $this->Vinhmsbookings->patchEntity($booking, ['agency_pay' => 1, 'is_paid' => 1, 'status' => 2]);
                $this->Vinhmsbookings->save($booking);
            }
            if ($this->Auth->user('role_id') == 2) {
                $isSaleVinBooking = true;
            } else {
                $isSaleVinBooking = false;
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

            $this->set(compact('headerType', 'title', 'booking', 'banks', 'bank_invoice', 'bookingPrice', 'payment', 'arrayOnepayData', 'isSaleVinBooking'));
        } else {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
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

    public function requestPayment()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Payments');
        $this->loadModel('DepositLogs');
        $this->loadModel('Users');

        $response = ['success' => false, 'errors' => [], 'is_onepay' => false, 'redirect_link' => ''];
        $data = $this->getRequest()->getData();
        $validateType = $this->Payments->newEntity($data, ['validate' => 'paymentType']);
        if ($validateType->getErrors()) {
            $response['errors'] = $validateType->getErrors();
        } else {
            $isValidate = false;
            if (isset($data['type'])) {
                if ($data['type'] == PAYMENT_BALANCE) {
                    $user = $this->Users->get($this->Auth->user('id'));
                    $Booking = $this->Bookings->get($data['booking_id'], ['contain' => 'BookingSurcharges']);
                    $bookingPrice = $Booking->price;
                    foreach ($Booking->booking_surcharges as $surcharge) {
                        $bookingPrice += $surcharge->price;
                    }
                    if ($user->balance < $bookingPrice - $Booking->revenue) {
                        $isValidate = false;
                        $response['not_enough_balance'] = true;
                        $response['errors'] = [
                            'not_enough_balance' => [
                                '_empty' => 'Không đủ số dư trong tài khoản'
                            ]
                        ];
                    } else {
                        $isValidate = true;
                    }
                } elseif ($data['type'] == PAYMENT_TRANSFER) {
                    $validateInvoice = $this->Payments->newEntity($data, ['validate' => 'paymentInvoice']);
                    if ($validateInvoice->getErrors()) {
                        $response['errors'] = $validateInvoice->getErrors();
                    } else {
                        if ($data['invoice'] == 1) {
                            $validateInvoiceExport = $this->Payments->newEntity($data, ['validate' => 'paymentExportInvoice']);
                            if ($validateInvoiceExport->getErrors()) {
                                $response['errors'] = $validateInvoiceExport->getErrors();
                            } else {
                                $isValidate = true;
                            }
                        } else {
                            $isValidate = true;
                        }
                    }
                } elseif ($data['type'] == PAYMENT_ONEPAY_ATM || $data['type'] == PAYMENT_ONEPAY_CREDIT || $data['type'] == PAYMENT_ONEPAY_QR) {
                    $isValidate = true;
                } else {
                    $isValidate = true;
                }
                if ($isValidate) {
                    $booking = $this->Bookings->get($data['booking_id'], ['contain' => ['Hotels', 'Users', 'Payments', 'BookingSurcharges']]);
                    $payment = $this->Payments->find()->where(['booking_id' => $data['booking_id']])->first();
                    if (!$payment) {
                        $payment = $this->Payments->newEntity();
                    }
                    $payment = $this->Payments->patchEntity($payment, $data);
                    $this->Payments->save($payment);
                    if ($data['type'] == PAYMENT_ONEPAY_ATM || $data['type'] == PAYMENT_ONEPAY_CREDIT || $data['type'] == PAYMENT_ONEPAY_QR) {
                        $response['is_onepay'] = true;
                        $response['redirect_link'] = $this->onepayPayment($data['booking_id'], 1, $data['type'], $data['invoice']);
                    } elseif ($data['type'] == PAYMENT_BALANCE) {
                        $user = $this->Users->get($this->Auth->user('id'));
                        // Tạo log mới
                        $bookingPrice = $booking->price;
                        foreach ($booking->booking_surcharges as $surcharge) {
                            $bookingPrice += $surcharge->price;
                        }
                        $depositLog = $this->DepositLogs->newEntity();
                        $depositLog = $this->DepositLogs->patchEntity($depositLog, [
                            'user_id' => $this->Auth->user('id'),
                            'title' => 'Thanh toán booking mã ' . $booking->code,
                            'amount' => 0 - ($bookingPrice - $booking->revenue - $booking->agency_discount),
                            'balance' => $user->balance - ($bookingPrice - $booking->revenue),
                            'type' => 2,
                            'status' => 1,
                            'booking_type' => $booking->type,
                            'booking_id' => $booking->id
                        ]);
                        $user = $this->Users->patchEntity($user, ['balance' => $user->balance - ($bookingPrice - $booking->revenue)]);
                        $this->Users->save($user);

                        $this->DepositLogs->save($depositLog);

                        $depositLog = $this->DepositLogs->patchEntity($depositLog, ['code' => "MTT" . str_pad($depositLog->id, 9, '0', STR_PAD_LEFT)]);
                        $this->DepositLogs->save($depositLog);

                        // Trừ balance
//                            $user = $this->Users->patchEntity($user, ['balance' => $user->balance - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount)]);
//                            $this->Users->save($user);
                    }
                    if ($payment->images) {
                        $saveBooking = $this->Bookings->get($data['booking_id']);
                        $saveBooking = $this->Bookings->patchEntity($saveBooking, ['agency_pay' => 1]);
                        $this->Bookings->save($saveBooking);
                    }

                    $booking = $this->Bookings->get($data['booking_id']);
                    $booking = $this->Bookings->patchEntity($booking, ['is_paid' => 1]);
                    $this->Bookings->save($booking);
                    $response['success'] = true;
                }
            } else {
                $response['success'] = true;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function requestVinPayment()
    {
        $testUrl = $this->viewVars['testUrl'];
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Vinpayments');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Users');
        $this->loadModel('DepositLogs');

        $response = ['success' => false, 'errors' => [], 'is_onepay' => false, 'redirect_link' => '', 'booking_code' => ''];
        $error_balance = false;
        $data = $this->getRequest()->getData();
        if ($data['type'] == PAYMENT_BALANCE) {
            $data['invoice_information'] = $data['invoice_information_balance'];
        }
        $validateType = $this->Vinpayments->newEntity($data, ['validate' => 'paymentType']);
        if ($validateType->getErrors()) {
            $response['errors'] = $validateType->getErrors();
        } else {
            $isValidate = false;
            if (isset($data['type'])) {
                if ($data['type'] == PAYMENT_BALANCE) {
                    $user = $this->Users->get($this->Auth->user('id'));
                    $vinBooking = $this->Vinhmsbookings->get($data['booking_id']);
                    if ($user->balance < $vinBooking->price - $vinBooking->agency_discount) {
                        $isValidate = false;
                        $response['not_enough_balance'] = true;
                        $response['errors'] = [
                            'not_enough_balance' => [
                                '_empty' => 'Không đủ số dư trong tài khoản'
                            ]
                        ];
                    } else {
                        $isValidate = true;
                    }
                } else if ($data['type'] == PAYMENT_TRANSFER) {
                    $validateInvoice = $this->Vinpayments->newEntity($data, ['validate' => 'paymentInvoice']);
                    if ($validateInvoice->getErrors()) {
                        $response['errors'] = $validateInvoice->getErrors();
                    } else {
                        if ($data['invoice'] == 1) {
                            $validateInvoiceExport = $this->Vinpayments->newEntity($data, ['validate' => 'paymentExportInvoice']);
                            if ($validateInvoiceExport->getErrors()) {
                                $response['errors'] = $validateInvoiceExport->getErrors();
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
                            $response['is_onepay'] = true;
                            $response['redirect_link'] = $this->onepayPayment($data['booking_id'], 2, $data['type'], $data['invoice']);
                        } else {

                        }
                    } elseif ($data['type'] == PAYMENT_BALANCE) {
                        $user = $this->Users->get($this->Auth->user('id'));
                        // Gửi booking mới
                        $enoughPackage = $this->Util->checkEnoughPackage($testUrl, $booking);
                        if ($enoughPackage) {
                            $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['Vinhmsbookings.id' => $booking->id])->first();
                            // block send vin
//                            $resBookingVin = $this->Util->createBooking($testUrl, $bookingSendmail);
//                            if (isset($resBookingVin['isSuccess']) && !empty($resBookingVin['isSuccess'])) {
                            // block send vin
                            if ($user) {
                                $bookingSendmail = $this->Vinhmsbookings->patchEntity($bookingSendmail, ['reservation_id' => $resBookingVin['data']['reservations'][0]['itineraryNumber']]);
                                $this->Vinhmsbookings->save($bookingSendmail);
                                $listReservationId = [];
                                foreach ($bookingSendmail->vinhmsbooking_rooms as $vinbkroomKey => $vinhmsbooking_room) {
                                    $this->loadModel('VinhmsbookingRooms');
                                    foreach ($vinhmsbooking_room['packages'] as $package) {
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
                                        'user_id' => $this->Auth->user('id'),
                                        'title' => 'Thanh toán booking mã ' . $booking->code,
                                        'amount' => 0 - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                                        'balance' => $user->balance - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                                        'type' => 2,
                                        'status' => 1,
                                        'booking_type' => VINPEARL,
                                        'booking_id' => $booking->id
                                    ]);
                                    $this->DepositLogs->save($depositLog);

                                    $depositLog = $this->DepositLogs->patchEntity($depositLog, ['code' => "MTT" . str_pad($depositLog->id, 9, '0', STR_PAD_LEFT)]);
                                    $this->DepositLogs->save($depositLog);

                                    // Trừ balance
                                    $user = $this->Users->patchEntity($user, ['balance' => $user->balance - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount)]);
                                    $this->Users->save($user);
                                    $vinBooking = $this->Vinhmsbookings->patchEntity($vinBooking, ['mail_type' => 1, 'accountant_id' => 0]);
                                    $this->Vinhmsbookings->save($vinBooking);
                                    $bookingSendmail = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['Vinhmsbookings.id' => $bookingId])->first();
                                    $resSendMail = $this->_sendVinCodeEmail($bookingSendmail);
                                    $this->_sendBookingToVin($bookingSendmail);
                                    if ($resSendMail['success']) {
                                        $bookingSendmail = $this->Vinhmsbookings->patchEntity($bookingSendmail, ['status' => 2]);
                                        $this->Vinhmsbookings->save($bookingSendmail);
                                        $response['success'] = true;
                                        $response['message'] = $resSendMail['message'];
                                    }
                                } else {
                                    $response['message'] = "Không tạo được booking trên Portal";
                                    $error_balance = true;
                                    $response['errors'] = [
                                        'not_enough_balance' => [
                                            '_empty' => 'Có lỗi xảy ra. Vui lòng sử dụng hình thức thanh toán khác hoặc liên hệ với với chúng tôi. '
                                        ]
                                    ];
                                }
                            } else {
                                $error_balance = true;
                                $response['debug'] = $resBookingVin;
                                $response['errors'] = [
                                    'not_enough_balance' => [
                                        '_empty' => 'Có lỗi xảy ra. Vui lòng sử dụng hình thức thanh toán khác hoặc liên hệ với với chúng tôi. '
                                    ]
                                ];
                            }
                        } else {
                            $error_balance = true;
                            $response['errors'] = [
                                'not_enough_balance' => [
                                    '_empty' => 'Có lỗi xảy ra. Thanh toán không thành công do không đủ gói'
                                ]
                            ];
                        }
                    }
                    if ($payment->images) {
                        $saveBooking = $this->Vinhmsbookings->get($data['booking_id']);
                        $saveBooking = $this->Vinhmsbookings->patchEntity($saveBooking, ['agency_pay' => 1]);
                        $this->Vinhmsbookings->save($saveBooking);
                    }

                    $booking = $this->Vinhmsbookings->patchEntity($booking, ['is_paid' => 1]);
                    $this->Vinhmsbookings->save($booking);
                    if (!$error_balance) {
                        $response['success'] = true;
                    } else {
                        $response['success'] = false;
                    }
                    $response['booking_code'] = $booking->code;
                }
            } else {
                $response['success'] = true;
            }
        }
        $response['payment_type'] = $data['type'];

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
//        dd($response);
        return $output;
    }

    public function paymentSuccess()
    {
        $title = 'Thanh toán thành công';
        $headerType = 1;
        $this->set(compact('headerType', 'title'));
    }

    public function paymentVinpearlSuccess()
    {
        $code = $this->getRequest()->getParam('code');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Vinpayments');
        $this->loadModel('Configs');
        $this->loadModel('Users');
        $booking = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms'])->where(['code' => $code])->first();
        $payment = $this->Vinpayments->query()->where(['booking_id' => $booking->id])->first();
        $bookingPrice = $booking->price;
        $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
        $banks = json_decode($json_banks->value, true);
        if (!$banks) {
            $banks = [];
        }
        $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
        $bank_invoice = json_decode($json_invoice->value, true);

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
        $sale = $this->Users->find()->where(['id' => $booking->sale_id])->first();
        $title = 'Thanh toán thành công';
        $headerType = 1;
        $this->set(compact('headerType', 'title', 'booking', 'sale', 'payment', 'bookingPrice', 'bank_invoice'));
    }

    public function paymentVinpearl()
    {
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Configs');
        $this->loadModel('Hotels');
        $code = $this->request->getParam('code');
        $booking = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms'])->where(['code' => $code])->first();
        $numRoom = $numAdult = $numChild = $numKid = 0;

        $isSaleVinBooking = false;

        $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();

        $banks = json_decode($json_banks->value, true);
        if (!$banks) {
            $banks = [];
        }
        $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
        $bank_invoice = json_decode($json_invoice->value, true);
        if (!isset($bank_invoice[0]['bank_name'])) {
            $bank_invoices[] = $bank_invoice;
        } else {
            $bank_invoices = $bank_invoice;
        }

        $user = $this->Users->get($booking->user_id);
        $balance = $user->balance;

        foreach ($booking->vinhmsbooking_rooms as $room) {
            $numRoom++;
            $numAdult += $room->num_adult;
            $numChild += $room->num_child;
            $numKid += $room->num_kid;
        }
        $hotel = $this->Hotels->get($booking->hotel_id);
        $date = date_diff($booking->start_date, $booking->end_date);
        $title = 'Nhập thông tin thanh toán';
        $headerType = 1;
        $this->set(compact('title', 'headerType', 'numRoom', 'numAdult', 'numChild', 'numKid', 'booking', 'banks', 'bank_invoices', 'date', 'isSaleVinBooking', 'hotel', 'balance'));
    }

    public function checkdate()
    {
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
        }
    }

    public function denyBooking($id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $booking = $this->Bookings->get($id);
        if ($booking) {
            if ($this->Auth->user() && $booking->user_id == $this->Auth->user('id')) {
                $booking = $this->Bookings->patchEntity($booking, ['status' => 5]);
                if ($this->Bookings->save($booking)) {
                    $this->redirect($this->referer());
                }
            }
        }
    }

    public function addBookingVin()
    {
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('VinhmsbookingRooms');
        $this->loadModel('VinhmsbookingTransportations');
        $this->loadModel('Users');
        $res = ['success' => true, 'booking_code' => '', 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            if (isset($data['first_name']) && $data['first_name']) {

            } else {
                $res['errors']['first_name'] = 'This field is required';
                $res['success'] = false;
            }
            if (isset($data['sur_name']) && $data['sur_name']) {

            } else {
                $res['errors']['sur_name'] = 'This field is required';
                $res['success'] = false;
            }
            if (isset($data['email']) && $data['email']) {

            } else {
                $res['errors']['email'] = 'This field is required';
                $res['success'] = false;
            }
            if (isset($data['phone']) && $data['phone']) {

            } else {
                $res['errors']['phone'] = 'This field is required';
                $res['success'] = false;
            }
            if ($res['success']) {
                $booking = $this->Vinhmsbookings->newEntity();
                $bookingRooms = $data['vin_room'];
                unset($data['vin_room']);
                $data['status'] = 1;
                if (!isset($data['vin_information'])) {
                    $data['vin_information'] = [];
                }
                $data['vin_information'] = json_encode($data['vin_information'], JSON_UNESCAPED_SLASHES);
                if ($data['sale_id'] == 0 && $data['user_id'] == 0) {
                    $sale = $this->Users->find()->where(['username' => 'datphong'])->first();
                    $data['sale_id'] = $sale->id;
                    $data['user_id'] = $sale->id;
                } else {
                    $user = $this->Users->get($data['user_id']);
                    $data['sale_id'] = $user->parent_id;
                    $data['user_id'] = $user->id;
                }
                $data['sale_revenue_default'] = $data['sale_revenue'];
                $booking = $this->Vinhmsbookings->patchEntity($booking, $data);
                $this->Vinhmsbookings->save($booking);
                $totalPrice = 0;

                if ($data['vin_booking_type'] == 1) {
                    foreach ($bookingRooms as $k => $room) {
                        $totalPrice += str_replace(',', '', $room['price']);
                        $bookingVinRoom = $this->VinhmsbookingRooms->newEntity();
                        $bookingVinRoom = $this->VinhmsbookingRooms->patchEntity($bookingVinRoom, [
                            'room_index' => $k,
                            'vinhms_name' => $room['name'],
                            'vinhmsbooking_id' => $booking->id,
                            'vinhms_package_id' => $room['package_id'],
                            'vinhms_package_code' => $room['package_code'],
                            'vinhms_package_name' => $room['package_name'],
                            'vinhms_room_id' => $room['room_id'],
                            'vinhms_rateplan_id' => $room['rateplan_id'],
                            'vinhms_allotment_id' => $room['allotment_id'],
                            'vinhms_room_type_code' => $room['room_type_code'],
                            'vinhms_rateplan_code' => $room['rateplan_code'],
                            'room_id' => $room['room_id'],
                            'checkin' => $data['start_date'],
                            'checkout' => $data['end_date'],
                            'num_adult' => $room['num_adult'],
                            'num_kid' => $room['num_kid'],
                            'num_child' => $room['num_child'],
                            'customer_note' => '',
                            'detail_by_day' => '',
                            'price' => str_replace(',', '', $room['default_price']),
                            'revenue' => $room['revenue'],
                            'sale_revenue' => $room['sale_revenue']
                        ]);
                        $this->VinhmsbookingRooms->save($bookingVinRoom);
                    }
                } else {
                    foreach ($bookingRooms as $k => $room) {
                        foreach ($room['package'] as $pK => $package) {
                            $totalPrice += str_replace(',', '', $package['price']);
                            $bookingVinRoom = $this->VinhmsbookingRooms->newEntity();
                            $bookingVinRoom = $this->VinhmsbookingRooms->patchEntity($bookingVinRoom, [
                                'room_index' => $k,
                                'vinhms_name' => $room['name'],
                                'vinhmsbooking_id' => $booking->id,
                                'vinhms_package_id' => $package['package_id'],
                                'vinhms_package_code' => $package['package_code'],
                                'vinhms_package_name' => $package['package_name'],
                                'vinhms_room_id' => $package['room_id'],
                                'vinhms_rateplan_id' => $package['rateplan_id'],
                                'vinhms_allotment_id' => $package['allotment_id'],
                                'vinhms_room_type_code' => $package['room_type_code'],
                                'vinhms_rateplan_code' => $package['rateplan_code'],
                                'room_id' => $package['room_id'],
                                'checkin' => date('Y-m-d', strtotime($package['start_date'])),
                                'checkout' => date('Y-m-d', strtotime($package['end_date'])),
                                'num_adult' => $room['num_adult'],
                                'num_kid' => $room['num_kid'],
                                'num_child' => $room['num_child'],
                                'customer_note' => '',
                                'detail_by_day' => '',
                                'price' => str_replace(',', '', $package['default_price']),
                                'revenue' => $package['revenue'],
                                'sale_revenue' => $package['sale_revenue']
                            ]);
                            $this->VinhmsbookingRooms->save($bookingVinRoom);
                        }
                    }
                }
                $booking = $this->Vinhmsbookings->patchEntity($booking, [
                    'code' => "MVP" . str_pad($booking->id, 9, '0', STR_PAD_LEFT),
                    'price' => $totalPrice,
                    'price_default' => $totalPrice
                ]);
                $this->Vinhmsbookings->save($booking);
                $res['success'] = true;
                $res['booking_code'] = $booking->code;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($res));
        return $output;
    }

    public function saleBookingVin()
    {
        if ($this->Auth->user('role_id') == 2) {
            $date = date('d/m/Y') . " - " . date('d/m/Y', strtotime('+1 day'));
            $isSaleVinBooking = true;
            $title = 'Sale đặt booking Vin';
            $dataRoom[] = [
                'num_adult' => 1,
                'num_child' => 0,
                'num_kid' => 0
            ];
            $numPeople = "1 Phòng-1NL-0TE-0EB";
            $this->set(compact('isSaleVinBooking', 'title', 'dataRoom', 'numPeople', 'date'));
        } else {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }

    }

    public function saleAddBookingVin()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $testUrl = $this->viewVars['testUrl'];
        $this->loadModel('Hotels');
        $this->loadModel('Vinrooms');
        $data = $this->request->getData();

        $hotel = $this->Hotels->find()->where(['name' => $data['keyword']])->first();

        $numPeople = $data['num_people'];
        $roomData = explode('-', $data['num_people']);
        $numRoom = str_replace(' Phòng', '', $roomData[0]);
        $numAdult = str_replace('NL', '', $roomData[1]);
        $numChild = str_replace('TE', '', $roomData[2]);
        $numKid = str_replace('EB', '', $roomData[3]);
        $dataRoom = $data['vin_room'];

        $dateParam = $data['fromDate'];
        $date = explode('-', $data['fromDate']);
        $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[0])));
        $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $date[1])));

//        "numberOfAdult" => $data['numberOfAdult'],
//                "otherOccupancies" => $data['otherOccupancies'],


        $singleVinChooseRoom = [];
        $dateDiff = date_diff(date_create($startDate), date_create($endDate));
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
                        $vinRoom = $this->Vinrooms->find()->where(['vin_code' => $ratePackage['roomTypeID'], 'hotel_id' => $hotel->id])->first();
                        if ($vinRoom) {
                            $ratePackage['trippal_price'] = $vinRoom->trippal_price != 0 ? $vinRoom->trippal_price * $dateDiff->days : $hotel->price_agency * $dateDiff->days;
                            $ratePackage['customer_price'] = $vinRoom->customer_price != 0 ? $vinRoom->customer_price * $dateDiff->days : $hotel->price_customer * $dateDiff->days;
                        } else {
                            $ratePackage['trippal_price'] = $hotel->price_agency * $dateDiff->days;
                            $ratePackage['customer_price'] = $hotel->price_customer * $dateDiff->days;
                        }
                        $tmpPrice = $ratePackage['rateAvailablity']['amount'] + $ratePackage['trippal_price'] + $ratePackage['customer_price'];
                        $listRoom[$ratePackage['roomTypeID']]['information']['min_price'] > $tmpPrice ? $listRoom[$ratePackage['roomTypeID']]['information']['min_price'] = $tmpPrice : true;
                        $listRoom[$ratePackage['roomTypeID']]['package'][] = $ratePackage;
                    }
                }
            }
            $listRoom = array_reverse($listRoom);
            $singleVinChooseRoom[] = $listRoom;
        }
        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            $ref = $user['ref_code'];
        } else {
            $user = null;
        }

        $this->set(compact('user', 'listRoom', 'numRoom', 'numAdult', 'numChild', 'numKid', 'dateDiff', 'numPeople', 'dateParam', 'dataRoom', 'startDate', 'endDate', 'hotel', 'singleVinChooseRoom'));
        $this->render('sale_add_vin_booking_room')->body();
//        $response['data'] = $this->render('sale_add_vin_booking_room')->body();
//
//        $output = $this->response;
//        $output = $output->withType('json');
//        $output = $output->withStringBody(json_encode($response));
//        return $output;
    }

    public function saleCreateBookingVin()
    {
        if ($this->Auth->user('role_id') == 2) {
            $isSaleVinBooking = true;
            $this->loadModel('Hotels');
            $this->loadModel('Users');
            $data = $this->request->getData();
            if (!$data) {
                $this->redirect($this->referer());
            }
            $hotel = $this->Hotels->find()->where(['slug' => $this->request->getParam('slug')])->first();
            $title = 'Mustgo Booking Khách Sạn';
            $headerType = 1;
            $data['date_diff'] = date_diff(date_create($data['start_date']), date_create($data['end_date']));
            $totalPrice = 0;
            $totalRevenue = $totalSaleRevenue = 0;
            foreach ($data['vin_room'] as $k => $room) {
                $totalPrice += str_replace(',', '', $room['price']);
                $totalRevenue += $room['revenue'];
                $totalSaleRevenue += $room['sale_revenue'];
            }
            $listAgency = $this->Users->find()->where(['parent_id' => $this->Auth->user('id')]);
            $this->set(compact('title', 'headerType', 'data', 'totalPrice', 'totalRevenue', 'totalSaleRevenue', 'hotel', 'isSaleVinBooking', 'listAgency'));
        } else {
            $this->redirect('/');
        }

    }

    // channel
    public function addBookingChannel()
    {
        $this->loadModel('Channelbookings');
        $this->loadModel('ChannelbookingRooms');
        $this->loadModel('Channelrooms');
        $this->loadModel('Users');
        $res = ['success' => true, 'booking_code' => '', 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            if (isset($data['first_name']) && $data['first_name']) {

            } else {
                $res['errors']['first_name'] = 'This field is required';
                $res['success'] = false;
            }
            if (isset($data['sur_name']) && $data['sur_name']) {

            } else {
                $res['errors']['sur_name'] = 'This field is required';
                $res['success'] = false;
            }
            if (isset($data['email']) && $data['email']) {

            } else {
                $res['errors']['email'] = 'This field is required';
                $res['success'] = false;
            }
            if (isset($data['phone']) && $data['phone']) {

            } else {
                $res['errors']['phone'] = 'This field is required';
                $res['success'] = false;
            }
            if ($res['success']) {
                $booking = $this->Channelbookings->newEntity();
                $bookingRooms = $data['channel_room'];
                unset($data['channel_room']);
                $data['status'] = 1;
                if (!isset($data['information'])) {
                    $data['information'] = [];
                }
                $data['information'] = json_encode($data['information'], JSON_UNESCAPED_SLASHES);
                if ($data['sale_id'] == 0 && $data['user_id'] == 0) {
                    $sale = $this->Users->find()->where(['username' => 'datphong'])->first();
                    $data['sale_id'] = $sale->id;
                    $data['user_id'] = $sale->id;
                } else {
                    $user = $this->Users->get($data['user_id']);
                    $data['sale_id'] = $user->parent_id;
                    $data['user_id'] = $user->id;
                }
                $data['sale_revenue_default'] = $data['sale_revenue'];
                $booking = $this->Channelbookings->patchEntity($booking, $data);
                $this->Channelbookings->save($booking);
//                dd($booking);
                $totalPrice = 0;

                if ($data['channel_booking_type'] == 1) {
                    foreach ($bookingRooms as $k => $room) {
                        $totalPrice += str_replace(',', '', $room['price']);
//                        dd($this->ChannelbookingRooms);
                        $bookingVinRoom = $this->ChannelbookingRooms->newEntity();
                        $bookingVinRoom = $this->ChannelbookingRooms->patchEntity($bookingVinRoom, [
                            'room_index' => $k,
                            'channel_name' => $room['name'],
                            'channelbooking_id' => $booking->id,
//                            'channel_package_id' => $room['package_id'],
//                            'channel_package_code' => $room['package_code'],
//                            'channel_package_name' => $room['package_name'],
                            'channel_room_id' => $room['room_id'],
                            'channelrateplan_id' => $room['rateplan_id'],
//                            'channel_allotment_id' => $room['allotment_id'],
                            'channelroom_code' => $room['room_type_code'],
                            'channelrateplan_code' => $room['rateplan_code'],
//                            'room_id' => $room['room_id'],
                            'checkin' => $data['start_date'],
                            'checkout' => $data['end_date'],
                            'num_adult' => $room['num_adult'],
                            'num_kid' => $room['num_kid'],
                            'num_child' => $room['num_child'],
                            'customer_note' => '',
                            'detail_by_day' => '',
                            'price' => str_replace(',', '', $room['default_price']),
//                            'revenue' => $room['revenue'],
                            'sale_revenue' => $room['sale_revenue'],
                            'date_range' => $room['date_range']
                        ]);
                        $this->ChannelbookingRooms->save($bookingVinRoom);
                    }
                } else {
                    foreach ($bookingRooms as $k => $room) {
                        foreach ($room['package'] as $pK => $package) {
                            $totalPrice += str_replace(',', '', $package['price']);
                            $bookingVinRoom = $this->ChannelbookingRooms->newEntity();
                            $bookingVinRoom = $this->ChannelbookingRooms->patchEntity($bookingVinRoom, [
                                'room_index' => $k,
                                'channel_name' => $room['name'],
                                'channelbooking_id' => $booking->id,
//                            'channel_package_id' => $room['package_id'],
//                            'channel_package_code' => $room['package_code'],
//                            'channel_package_name' => $room['package_name'],
                                'channel_room_id' => $room['room_id'],
                                'channelrateplan_id' => $room['rateplan_id'],
//                            'channel_allotment_id' => $room['allotment_id'],
                                'channelroom_code' => $room['room_type_code'],
                                'channelrateplan_code' => $room['rateplan_code'],
//                            'room_id' => $room['room_id'],
                                'checkin' => $data['start_date'],
                                'checkout' => $data['end_date'],
                                'num_adult' => $room['num_adult'],
                                'num_kid' => $room['num_kid'],
                                'num_child' => $room['num_child'],
                                'customer_note' => '',
                                'detail_by_day' => '',
                                'price' => str_replace(',', '', $room['default_price']),
//                            'revenue' => $room['revenue'],
                                'sale_revenue' => $room['sale_revenue']
                            ]);
                            $this->ChannelbookingRooms->save($bookingVinRoom);
                        }
                    }
                }
                $booking = $this->Channelbookings->patchEntity($booking, [
                    'code' => "MHL" . str_pad($booking->id, 9, '0', STR_PAD_LEFT),
                    'price' => $totalPrice,
                    'price_default' => $totalPrice
                ]);
                $this->Channelbookings->save($booking);
                $res['success'] = true;
                $res['booking_code'] = $booking->code;
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($res));
//        dd($output);
        return $output;
    }

    public function paymentChannel()
    {
        $this->loadModel('Channelbookings');
        $this->loadModel('ChannelbookingRooms');
        $this->loadModel('Channelrooms');
        $this->loadModel('Users');
        $this->loadModel('Configs');
        $this->loadModel('Hotels');
        $code = $this->request->getParam('code');
        $booking = $this->Channelbookings->find()->contain(['ChannelbookingRooms', 'ChannelbookingRooms.Channelrooms'])->where(['code' => $code])->first();
//        dd($booking);
        $numRoom = $numAdult = $numChild = $numKid = 0;

        $isSaleVinBooking = false;

        $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();

        $banks = json_decode($json_banks->value, true);
        if (!$banks) {
            $banks = [];
        }
        $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
        $bank_invoice = json_decode($json_invoice->value, true);
        if (!isset($bank_invoice[0]['bank_name'])) {
            $bank_invoices[] = $bank_invoice;
        } else {
            $bank_invoices = $bank_invoice;
        }

        $user = $this->Users->get($booking->user_id);
        $balance = $user->balance;
//        dd($booking->channelbooking_rooms);
        foreach ($booking->channelbooking_rooms as $room) {
            $numRoom++;
            $numAdult += $room->num_adult;
            $numChild += $room->num_child;
            $numKid += $room->num_kid;
        }
        $hotel = $this->Hotels->get($booking->hotel_id);
        $date = date_diff($booking->start_date, $booking->end_date);
        $title = 'Nhập thông tin thanh toán';
        $headerType = 1;
        $this->set(compact('title', 'headerType', 'numRoom', 'numAdult', 'numChild', 'numKid', 'booking', 'banks', 'bank_invoices', 'date', 'isSaleVinBooking', 'hotel', 'balance'));
    }

    public function requestChannelPayment()
    {
        $testUrl = $this->viewVars['testUrl'];
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Channelpayments');
        $this->loadModel('Channelbookings');
        $this->loadModel('Users');
        $this->loadModel('DepositLogs');

        $response = ['success' => false, 'errors' => [], 'is_onepay' => false, 'redirect_link' => '', 'booking_code' => ''];
        $error_balance = false;
        $data = $this->getRequest()->getData();
//        dd($data);
        if ($data['type'] == PAYMENT_BALANCE) {
            $data['invoice_information'] = $data['invoice_information_balance'];
        }
        $validateType = $this->Channelpayments->newEntity($data, ['validate' => 'paymentType']);
        if ($validateType->getErrors()) {
            $response['errors'] = $validateType->getErrors();
        } else {
            $isValidate = false;
            if (isset($data['type'])) {
                if ($data['type'] == PAYMENT_BALANCE) {
                    $user = $this->Users->get($this->Auth->user('id'));
                    $channelBooking = $this->Channelbookings->get($data['booking_id']);
                    if ($user->balance < $channelBooking->price - $channelBooking->agency_discount) {
                        $isValidate = false;
                        $response['not_enough_balance'] = true;
                        $response['errors'] = [
                            'not_enough_balance' => [
                                '_empty' => 'Không đủ số dư trong tài khoản'
                            ]
                        ];
                    } else {
                        $isValidate = true;
                    }
                } else if ($data['type'] == PAYMENT_TRANSFER) {
                    $validateInvoice = $this->Channelpayments->newEntity($data, ['validate' => 'paymentInvoice']);
                    if ($validateInvoice->getErrors()) {
                        $response['errors'] = $validateInvoice->getErrors();
                    } else {
                        if ($data['invoice'] == 1) {
                            $validateInvoiceExport = $this->Channelpayments->newEntity($data, ['validate' => 'paymentExportInvoice']);
                            if ($validateInvoiceExport->getErrors()) {
                                $response['errors'] = $validateInvoiceExport->getErrors();
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

                //validate data

                if ($isValidate) {
                    $booking = $this->Channelbookings->get($data['booking_id'], ['contain' => ['Hotels', 'ChannelbookingRooms', 'Users']]);
                    $payment = $this->Channelpayments->find()->where(['booking_id' => $data['booking_id']])->first();
                    if (!$payment) {
                        $payment = $this->Channelpayments->newEntity();
                    }
                    $payment = $this->Channelpayments->patchEntity($payment, $data);
                    $this->Channelpayments->save($payment);
                    if ($data['type'] == PAYMENT_ONEPAY_ATM || $data['type'] == PAYMENT_ONEPAY_CREDIT || $data['type'] == PAYMENT_ONEPAY_QR) {
                        $enoughPackage = $this->Util->checkEnoughRoomChannel($booking);
                        if ($enoughPackage['enough']) {
                            $response['is_onepay'] = true;
                            $response['redirect_link'] = $this->onepayPayment($data['booking_id'], 3, $data['type'], $data['invoice']);
                        } else {
                            $response['errors'] = [
                                'not_enough_balance' => [
                                    '_empty' => 'Có lỗi xảy ra. ' . $enoughPackage['message']
                                ]
                            ];
                        }
                    } elseif ($data['type'] == PAYMENT_BALANCE) {
                        $user = $this->Users->get($this->Auth->user('id'));
                        // Gửi booking mới
                        $enoughPackage = $this->Util->checkEnoughRoomChannel($booking);
                        if ($enoughPackage['enough']) {
                            $bookingSendmail = $this->Channelbookings->find()->contain(['ChannelbookingRooms', 'Hotels'])->where(['channelbookings.id' => $booking->id])->first();
                            $resBookingChannel = $this->Util->createBookingChannel($bookingSendmail);
//                            dd($resBookingChannel);
                            if (isset($resBookingChannel['result']) && $resBookingChannel['result']) {
                                $bookingSendmail = $this->Channelbookings->patchEntity($bookingSendmail, ['reservation_id' => $resBookingChannel['data']['BookingId']]);
                                $this->Channelbookings->save($bookingSendmail);
                                //tạo log
                                $depositLog = $this->DepositLogs->newEntity();
                                $depositLog = $this->DepositLogs->patchEntity($depositLog, [
                                    'user_id' => $this->Auth->user('id'),
                                    'title' => 'Thanh toán booking mã ' . $booking->code,
                                    'amount' => 0 - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                                    'balance' => $user->balance - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount),
                                    'type' => 2,
                                    'status' => 1,
                                    'booking_type' => CHANNEL,
                                    'booking_id' => $booking->id
                                ]);
                                $this->DepositLogs->save($depositLog);
                                $depositLog = $this->DepositLogs->patchEntity($depositLog, ['code' => "MTT" . str_pad($depositLog->id, 9, '0', STR_PAD_LEFT)]);
                                $this->DepositLogs->save($depositLog);
                                // Trừ balance
                                $user = $this->Users->patchEntity($user, ['balance' => $user->balance - ($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount)]);

                                $this->Users->save($user);
                                $channelBooking = $this->Channelbookings->patchEntity($channelBooking, ['mail_type' => 1, 'accountant_id' => 0]);
                                $this->Channelbookings->save($channelBooking);
//                                $bookingSendmail = $this->Channelbookings->find()->contain(['ChannelbookingRooms', 'Hotels', 'Channelpayments'])->where(['channelbookings.id' => $bookingId])->first();
//                                $resSendMail = $this->_sendVinCodeEmail($bookingSendmail);
//                                $this->_sendBookingToVin($bookingSendmail);
//                                if ($resSendMail['success']) {
//                                    $bookingSendmail = $this->Channelbookings->patchEntity($bookingSendmail, ['status' => 2]);
//                                    $this->Channelbookings->save($bookingSendmail);
//                                    $response['success'] = true;
//                                    $response['message'] = $resSendMail['message'];
//                                }
                                $response['success'] = true;

                            } else {
                                $error_balance = true;
                                $response['debug'] = $resBookingChannel;
                                $response['errors'] = [
                                    'not_enough_balance' => [
                                        '_empty' => 'Có lỗi xảy ra. Vui lòng sử dụng hình thức thanh toán khác hoặc liên hệ với với chúng tôi. '
                                    ]
                                ];
                            }
                        } else {
                            $error_balance = true;
                            $response['errors'] = [
                                'not_enough_balance' => [
                                    '_empty' => 'Có lỗi xảy ra. Thanh toán không thành công.' . $enoughPackage['message']
                                ]
                            ];
                        }
                    }
                    if ($payment->images) {
                        $saveBooking = $this->Channelbookings->get($data['booking_id']);
                        $saveBooking = $this->Channelbookings->patchEntity($saveBooking, ['agency_pay' => 1]);
                        $this->Channelbookings->save($saveBooking);
                    }
                    $booking = $this->Channelbookings->patchEntity($booking, ['is_paid' => 1]);
                    $this->Channelbookings->save($booking);
                    if (!$error_balance) {
                        $response['success'] = true;
                    } else {
                        $response['success'] = false;
                    }
                    $response['booking_code'] = $booking->code;
                }
            } else {
                $response['success'] = true;
            }
        }
        $response['payment_type'] = $data['type'];

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
//        dd($response);
        return $output;
    }
    public function paymentChannelSuccess(){
        $code = $this->getRequest()->getParam('code');
        $this->loadModel('Channelpayments');
        $this->loadModel('Channelbookings');
        $this->loadModel('Configs');
        $this->loadModel('Users');
        $booking = $this->Channelbookings->find()->contain(['ChannelbookingRooms'])->where(['code' => $code])->first();
        $payment = $this->Channelpayments->query()->where(['booking_id' => $booking->id])->first();
        $bookingPrice = $booking->price;
        $json_banks = $this->Configs->find()->where(['type' => 'bank-account'])->first();
        $banks = json_decode($json_banks->value, true);
        if (!$banks) {
            $banks = [];
        }
        $json_invoice = $this->Configs->find()->where(['type' => 'bank-invoice'])->first();
        $bank_invoice = json_decode($json_invoice->value, true);

        $listRoom = [];
        foreach ($booking->channelbooking_rooms as $room) {
            if (!isset($listRoom[$room->room_index])) {
                $listRoom[$room->room_index]['channel_name'] = $room['channel_name'];
                $listRoom[$room->room_index]['num_adult'] = $room['num_adult'];
                $listRoom[$room->room_index]['num_kid'] = $room['num_kid'];
                $listRoom[$room->room_index]['num_child'] = $room['num_child'];
                $listRoom[$room->room_index]['packages'][] = $room;
            } else {
                $listRoom[$room->room_index]['packages'][] = $room;
            }
        }
        $booking->channelbooking_rooms = $listRoom;
        $sale = $this->Users->find()->where(['id' => $booking->sale_id])->first();
        $title = 'Thanh toán thành công';
        $headerType = 1;
//        dd($booking,$sale,$payment,$bookingPrice,$bank_invoice);
        $this->set(compact('headerType', 'title', 'booking', 'sale', 'payment', 'bookingPrice', 'bank_invoice'));
    }

    //end channel
    private function _dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    private function _createDateRangePriceArray($fromDate, $toDate, $price)
    {
        $current = strtotime($fromDate);
        $last = strtotime($toDate);

        while ($current <= $last) {
            $priceArray[date('Y-m-d', $current)] = $price;
            $current = strtotime('+1 day', $current);
        }
        return $priceArray;
    }

    private function codeToId($text)
    {
        while ($text[0] == 0) {
            $text = substr($text, 1, strlen($text) - 1);
        }
        return $text;
    }


}
