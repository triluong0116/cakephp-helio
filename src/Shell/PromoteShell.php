<?php

namespace App\Shell;

use Cake\Console\Shell;

/**
 * Simple console wrapper around Psy\Shell.
 * @property \App\Model\Table\PromotesTable $Promotes 
 * @property \App\Model\Table\UsersTable $Users 
 * @property \App\Model\Table\UserTransactionsTable $UserTransactions
 * @property \App\Model\Table\BookingsTable $Bookings 
 * @property \App\Model\Table\UserSharesTable $UserShares 
 *
 * 
 */
class PromoteShell extends Shell {

    public function countingPromoteForUser() {
        $this->loadModel('Users');
        $this->loadModel('Promotes');
        $this->loadModel('UserTransactions');
        $this->loadModel('Bookings');
        $this->loadModel('UserShares');
        $this->loadModel('Hotels');
        $firstDayPrevMonth = date('Y-m-d', strtotime("first day of last month"));
        $lastDayPreMonth = date('Y-m-d', strtotime("last day of last month"));
        $today = date('Y-m-d');
        $promotes = $this->Promotes->find()->where(['end_date >=' => $today]);
        foreach ($promotes as $promote) {
            switch ($promote->type) {
                case P_REG_CONNECT:
                    $users = $this->Users->find()->contain(['Fanpages'])->where(['date(created) >=' => $firstDayPrevMonth, 'date(created) <=' => $lastDayPreMonth]);
                    foreach ($users as $user) {
                        if ($user->fanpages) {
                            $userTransaction = $this->UserTransactions->newEntity();
                            $data_transaction = [
                                'user_id' => $user->id,
                                'revenue' => $promote->revenue,
                                'reason' => 'Thưởng cho Đăng ký và Kết nối Fanpage'
                            ];
                            $userTransaction = $this->UserTransactions->patchEntity($userTransaction, $data_transaction);
                            $this->UserTransactions->save($userTransaction);
                            
                            $newRevenue = $user->revenue + $promote->revenue;
                            $user = $this->Users->patchEntity($user, ['revenue' => $newRevenue]);
                            $this->Users->save($user);
                        }
                    }
                    break;
                case P_BOOK_SHARE:
                    $bookings = $this->Bookings->find()
                            ->select(['count' => $this->Bookings->find()->func()->count('*'), 'user_id'])
                            ->where(['date(created) >=' => $firstDayPrevMonth, 'date(created) <=' => $lastDayPreMonth])
                            ->group(['user_id']);
                    $shares = $this->UserShares->find()
                            ->select(['count' => $this->UserShares->find()->func()->count('*'), 'user_id'])
                            ->where(['date(created) >=' => $firstDayPrevMonth, 'date(created) <=' => $lastDayPreMonth])
                            ->group(['user_id']);
                    $users = $this->Users->find();
                    foreach ($users as $user) {
                        $totalBook = $totalShare = 0;
                        foreach ($bookings as $booking) {
                            if ($booking->user_id == $user->id) {
                                $totalBook += $booking->count;
                            }                            
                        }
                        foreach ($shares as $share) {
                            if ($share->user_id == $user->id) {
                                $totalShare += $share->count;
                            }
                        }
                        
                        if ($totalBook >= $promote->num_booking && $totalShare >= $promote->num_share) {
                            $userTransaction = $this->UserTransactions->newEntity();
                            $data_transaction = [
                                'user_id' => $user->id,
                                'revenue' => $promote->revenue,
                                'reason' => 'Thưởng đạt chỉ tiêu share/booking'
                            ];
                            $userTransaction = $this->UserTransactions->patchEntity($userTransaction, $data_transaction);
                            $this->UserTransactions->save($userTransaction);
                            
                            $newRevenue = $user->revenue + $promote->revenue;
                            $user = $this->Users->patchEntity($user, ['revenue' => $newRevenue]);
                            $this->Users->save($user);
                        }
                    }
                    break;
                case P_BOOK_SHARE_HOTEL:
                    $this->loadModel('CombosHotels');
                    $this->loadModel('Vouchers');
                    $this->loadModel('Hotels');
                    $hotel = $this->Hotels->get($promote->object_id);

                    $comboIds = $this->CombosHotels->find()->where(['hotel_id' => $promote->object_id])->extract('combo_id')->toArray();
                    $voucherIds = $this->Vouchers->find()->where(['hotel_id' => $promote->object_id])->extract('id')->toArray();

                    $condition_booking = $condition_share = [];

                    $condition_booking[] = ['type' => HOTEL, 'item_id' => $promote->object_id];
                    $condition_share[] = ['object_type' => HOTEL, 'object_id' => $promote->object_id];
                    if ($comboIds) {
                        $condition_booking[] = ['type' => COMBO, 'item_id IN' => $comboIds];
                        $condition_share[] = ['object_type' => COMBO, 'object_id IN' => $comboIds];
                    }
                    if ($voucherIds) {
                        $condition_booking[] = ['type' => VOUCHER, 'item_id IN' => $voucherIds];
                        $condition_share[] = ['object_type' => VOUCHER, 'object_id IN' => $voucherIds];
                    }
//                    dd($condition_share);
                    $bookings = $this->Bookings->find()
                            ->select(['count' => $this->Bookings->find()->func()->count('*'), 'user_id'])
                            ->where(['date(created) >=' => $firstDayPrevMonth, 'date(created) <=' => $lastDayPreMonth, 'status' => 1,
                                'OR' => $condition_booking
                            ])
                            ->group(['user_id']);

                    $shares = $this->UserShares->find()
                            ->select(['count' => $this->UserShares->find()->func()->count('*'), 'user_id'])
                            ->where(['date(created) >=' => $firstDayPrevMonth, 'date(created) <=' => $lastDayPreMonth,
                                'OR' => $condition_share
                            ])
                            ->group(['user_id']);

                    $users = $this->Users->find();
                    foreach ($users as $user) {
                        $totalBook = 0;
                        $totalShare = 0;
                        foreach ($bookings as $booking) {
                            if ($booking->user_id == $user->id) {
                                $totalBook += $booking->count;
                            }
                        }
                        foreach ($shares as $share) {
                            if ($share->user_id == $user->id) {
                                $totalShare += $share->count;
                            }
                        }

                        if ($totalBook >= $promote->num_booking && $totalShare >= $promote->num_share ) {
                            $userTransaction = $this->UserTransactions->newEntity();
                            $data_transaction = [
                                'user_id' => $user->id,
                                'revenue' => $promote->revenue,
                                'reason' => 'Thưởng đạt chỉ tiêu share/booking qua Khách sạn ' . $hotel->name
                            ];
                            $userTransaction = $this->UserTransactions->patchEntity($userTransaction, $data_transaction);
                            $this->UserTransactions->save($userTransaction);

                            $newRevenue = $user->revenue + $promote->revenue;
                            $user = $this->Users->patchEntity($user, ['revenue' => $newRevenue]);
                            $this->Users->save($user);
                        }
                    }
                    break;
                case P_BOOK_SHARE_LOCATION:
                    $this->loadModel('CombosHotels');
                    $this->loadModel('Vouchers');
                    $this->loadModel('Hotels');
                    $this->loadModel('Locations');
                    $this->loadModel('LandTours');                    
                    $location = $this->Locations->get($promote->object_id);                    
                    $condition_booking = $condition_share = [];
                    $landTourIds = $this->LandTours->find()->where(['destination_id' => $location->id])->extract('id')->toArray();
                    $hotelIds = $this->Hotels->find()->where(['location_id' => $location->id])->extract('id')->toArray();
                    if ($hotelIds) {
                        $condition_booking[] = ['type' => HOTEL, 'item_id IN' => $hotelIds];
                        $condition_share[] = ['object_type' => HOTEL, 'object_id IN' => $hotelIds];
                        $comboIds = $this->CombosHotels->find()->where(['hotel_id IN' => $hotelIds])->extract('combo_id')->toArray();
                        $voucherIds = $this->Vouchers->find()->where(['hotel_id IN' => $hotelIds])->extract('id')->toArray();
                        if ($comboIds) {
                            $condition_booking[] = ['type' => COMBO, 'item_id IN' => $comboIds];
                            $condition_share[] = ['object_type' => COMBO, 'object_id IN' => $comboIds];
                        }
                        if ($voucherIds) {
                            $condition_booking[] = ['type' => VOUCHER, 'item_id IN' => $voucherIds];
                            $condition_share[] = ['object_type' => VOUCHER, 'object_id IN' => $voucherIds];
                        }
                    }

                    if ($landTourIds) {
                        $condition_booking[] = ['type' => LANDTOUR, 'item_id IN' => $landTourIds];
                        $condition_share[] = ['object_type' => LANDTOUR, 'object_id IN' => $landTourIds];
                    }
//                    dd($condition_share);
                    $bookings = $this->Bookings->find()
                            ->select(['count' => $this->Bookings->find()->func()->count('*'), 'user_id'])
                            ->where(['date(created) >=' => $firstDayPrevMonth, 'date(created) <=' => $lastDayPreMonth, 'status' => 1, 'status' => 1,
                                'OR' => $condition_booking
                            ])
                            ->group(['user_id']);
                    
                    $shares = $this->UserShares->find()
                            ->select(['count' => $this->UserShares->find()->func()->count('*'), 'user_id'])
                            ->where(['date(created) >=' => $firstDayPrevMonth, 'date(created) <=' => $lastDayPreMonth,
                                'OR' => $condition_share
                            ])
                            ->group(['user_id']);
                    
                    $users = $this->Users->find();
                    foreach ($users as $user) {
                        $totalBook = $totalShare = 0;
                        foreach ($bookings as $booking) {
                            if ($booking->user_id == $user->id) {
                                $totalBook += $booking->count;
                            }
                        }
                        foreach ($shares as $share) {
                            if ($share->user_id == $user->id) {
                                $totalShare += $share->count;
                            }
                        }

                        if ($totalBook >= $promote->num_booking && $totalShare >= $promote->num_share) {
                            $userTransaction = $this->UserTransactions->newEntity();
                            $data_transaction = [
                                'user_id' => $user->id,
                                'revenue' => $promote->revenue,
                                'reason' => 'Thưởng đạt chỉ tiêu share/booking qua Khách sạn ' . $hotel->name
                            ];
                            $userTransaction = $this->UserTransactions->patchEntity($userTransaction, $data_transaction);
                            $this->UserTransactions->save($userTransaction);

                            $newRevenue = $user->revenue + $promote->revenue;
                            $user = $this->Users->patchEntity($user, ['revenue' => $newRevenue]);
                            $this->Users->save($user);
                        }
                    }
                    break;
            }
        }
    }

}
