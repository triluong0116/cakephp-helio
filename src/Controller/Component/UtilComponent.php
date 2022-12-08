<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Locator\TableLocator;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Cake\ORM\TableRegistry;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Upload component
 * @property \App\Model\Table\VinhmsAccessTokensTable $VinhmsAccessTokens
 * @property \App\Model\Table\ChannelAccessTokensTable $ChannelAccessTokens
 */
class UtilComponent extends Component
{

    public $components = array('Auth', 'getListHotel');

    public function formatSQLDate($date_str, $format)
    {
        $test = \DateTime::createFromFormat($format, $date_str);
        return date_format($test, 'Y-m-d');
    }

    public function checkDuplicateHotel($array)
    {
        return count($array) !== count(array_unique($array));
    }

    public function getListFanPage($fb, $user_id)
    {
        $this->Fanpages = TableRegistry::get('Fanpages');
        $this->Users = TableRegistry::get('Users');

        $helper = $fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state'] = $_GET['state'];

        try {

            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            echo 'No OAuth data could be obtained from the signed request. User has not authorized your app yet.';
            exit;
        }

        try {
            $user = $this->Users->get($user_id);
            $user = $this->Users->patchEntity($user, ['access_token' => $accessToken->getValue()]);
            $this->Users->save($user);
            $this->Auth->setUser($user);

            $response = $fb->get('me/accounts', $accessToken->getValue());
            $response = $response->getDecodedBody();
            foreach ($response['data'] as $item) {
                $fanpage = $this->Fanpages->find()->where(['page_id' => $item['id']])->first();
                if (!$fanpage) {
                    $fanpage = $this->Fanpages->newEntity();
                }
                $data_fanpage = [
                    'user_id' => $user_id,
                    'name' => $item['name'],
                    'page_id' => $item['id'],
                    'access_token' => $item['access_token']
                ];
                $fanpage = $this->Fanpages->patchEntity($fanpage, $data_fanpage);
                $this->Fanpages->save($fanpage);
            }
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    public function postToFacebook($fb, $access_token, $url_photo, $url_feed, $list_image, $content)
    {
        try {

            $images = json_decode($list_image, true);
            $dataMultiPost = [
                'message' => $content
            ];
            if ($list_image) {

                foreach ($images as $key => $image) {
                    if (file_exists($image)) {
                        $dataUploadPhoto = ['source' => $fb->fileToUpload(WWW_ROOT . $image), 'published' => 'false'];
                        $uploadedPhoto = $fb->post($url_photo, $dataUploadPhoto, $access_token);
                        $uploadPhoto = $uploadedPhoto->getGraphNode()->asArray();
                        $photo = $uploadPhoto['id'];
                        $dataMultiPost['attached_media[' . $key . ']'] = '{"media_fbid":"' . $photo . '"}';
                    }
                }
            }
            $multiPhotoPost = $fb->post($url_feed, $dataMultiPost, $access_token);

            return $multiPhotoPost->getGraphNode()->asArray();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function calculateSaleProfitMonthly($booking, $month)
    {
        switch ($booking->type) {
            case HOTEL:
                break;
            case HOMESTAY:
                break;
            case LANDTOUR:
                break;
            case VOUCHER:
                break;
        }
    }

    public function countingComboPrice($fromDate, $combo)
    {
        $priceArray = [];
        $priceCombo = 0;
        $index = 0;

//
        foreach ($combo->hotels as $hotel) {
            $hotelPrices = [];
            $priceArray[$hotel->id] = [];
            $comboHotelDates = $hotelDates = [];
            $attendedDays = $hotel->_joinData->days_attended - 1;
            $comboHotelDates = $this->_dateRange($fromDate, date('Y-m-d', strtotime($fromDate . ' + ' . $attendedDays . 'days')));
            foreach ($hotel->price_hotels as $price) {
//                debug($price);
                $price_start_date = date('Y-m-d', strtotime($price->start_date));
                $price_end_date = date('Y-m-d', strtotime($price->end_date));
                $tmpPriceHotel = $this->_createDateRangePriceArray($price_start_date, $price_end_date, $price->price);
                $hotelPrices = array_merge($hotelPrices, $tmpPriceHotel);
            }
            foreach ($comboHotelDates as $date) {
                if (isset($hotelPrices[$date])) {
                    $priceCombo = $priceCombo + $hotelPrices[$date] + $hotel->price_customer + $hotel->price_agency;
                } else {
                    $rsPrice = reset($hotelPrices);
                    $priceCombo = $priceCombo + $rsPrice + $hotel->price_customer + $hotel->price_agency;
                }
            }
        }
        return $priceCombo;
    }

    public function countingHotelPrice($fromDate, $hotel)
    {
        $currentDate = date('Y-m-d');
        $priceHotel = 0;
        foreach ($hotel->price_hotels as $price) {
            if ($this->checkBetweenDate($currentDate, $price->start_date, $price->end_date)) {
                $priceHotel = $price->price + $hotel->price_customer + $hotel->price_agency;
                break;
            } else {
                continue;
            }
        }

        return $priceHotel;
    }

    public function countingRoomHotelPrice($chooseDate, $room, $price_customer, $price_agency)
    {
        $priceHotel = 0;
        if ($room->price_rooms) {
            foreach ($room->price_rooms as $price) {
                if ($this->checkBetweenDate($chooseDate, $price->start_date, $price->end_date)) {
                    $priceHotel = $price->price + $price_customer + $price_agency;
                    break;
                } else {
                    continue;
                }
            }
        } else {
            $priceHotel = $price_customer + $price_agency;
        }
        if ($priceHotel == 0) {
            $priceHotel = $price_customer + $price_agency;
        }
        return $priceHotel;
    }

    public function countingHomeStayPrice($today, $homestay)
    {
        $unixTimestamp = strtotime($today);
        $dayOfWeek = date("l", $unixTimestamp);
        $arrayWeekDay = [
            'Monday' => 41,
            'Tuesday' => 41,
            'Wednesday' => 41,
            'Thursday' => 41,
            'Friday' => 42,
            'Saturday' => 42,
            'Sunday' => 42,
        ];
        $priceHomestay = 0;
        foreach ($homestay->price_home_stays as $price) {
            if ($price->type == $arrayWeekDay[$dayOfWeek]) {
                $priceHomestay = $price->price + $homestay->price_agency + $homestay->price_customer;
            }
        }
        return $priceHomestay;
    }

    public function countBookingHotelPrice($fromDate, $toDate, $hotel)
    {
        $currentDate = date('Y-m-d');
        $totalPrice = $hotel->price_customer + $hotel->price_agency;

    }


    public function checkBetweenDate($currentDate, $fromDate, $toDate)
    {
//echo $paymentDate; // echos today!
        $dateStart = date('Y-m-d', strtotime($fromDate));
        $dateEnd = date('Y-m-d', strtotime($toDate));

        if (($currentDate >= $dateStart) && ($currentDate <= $dateEnd)) {
            return true;
        } else {
            return false;
        }
    }

    public function createListSurcharge()
    {
        $surcharges = [
            SUR_WEEKEND => 'Phụ thu Cuối tuần',
            SUR_HOLIDAY => 'Phụ thu Lễ Tết',
            SUR_ADULT => 'Phụ thu Người lớn',
            SUR_CHILDREN => 'Phụ thu Trẻ em',
            SUR_BONUS_BED => 'Phụ thu Giường phụ',
            SUR_BREAKFAST => 'Phụ thu Ăn sáng',
            SUR_CHECKIN_SOON => 'Phụ thu Check In sớm',
            SUR_CHECKOUT_LATE => 'Phụ thu Check Out muộn',
            SUR_OTHER => 'Phụ thu khác',
        ];
        return $surcharges;
    }

    public function getSurchargeId($type, $other_id = '', $isInput = true)
    {
        $strId = '';
        switch ($type) {
            case SUR_WEEKEND:
                $strId = 'sur-weekend';
                break;
            case SUR_HOLIDAY:
                $strId = 'sur-holiday';
                break;
            case SUR_ADULT:
                $strId = 'sur-adult';
                break;
            case SUR_CHILDREN:
                $strId = 'sur-children';
                break;
            case SUR_BONUS_BED:
                $strId = 'sur-bonus-bed';
                break;
            case SUR_BREAKFAST:
                $strId = 'sur-breakfast';
                break;
            case SUR_CHECKIN_SOON:
                $strId = 'sur-checkin-soon';
                break;
            case SUR_CHECKOUT_LATE:
                $strId = 'sur-checkout-late';
                break;
            case SUR_OTHER:
                $strId = $other_id;
                break;
        }
        if ($isInput) {
            $str = 'input-' . $strId;
        } else {
            $str = 'show-price-' . $strId;
        }

        return $str;
    }

    public function _createDateRangePriceArray($fromDate, $toDate, $price)
    {
        $dates = array();
        $current = strtotime($fromDate);
        $last = strtotime($toDate);

        while ($current <= $last) {
            $priceArray[date('Y-m-d', $current)] = $price;
            $current = strtotime('+1 day', $current);
        }
        return $priceArray;
    }

    public function _dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
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

    public function calculateHotelPrice($hotel, $roomId, $today = null, $isRegular = false)
    {
        $res = ['status' => true, 'message' => '', 'price' => 0];
        if (!$today) {
            $today = date('Y-m-d');
        }
        $this->RoomPrices = TableRegistry::getTableLocator()->get('RoomPrices');
        $weekends = json_decode($hotel['weekend'], true);
        if (!$weekends) {
            $weekends = [];
        }
        $holidayDates = json_decode($hotel['holidays'], true);
        $holidays = [];
        if ($holidayDates) {
            foreach ($holidayDates as $holidayDate) {
                $holidayDate = explode(' - ', $holidayDate);
                $holidayStartDate = $this->formatSQLDate($holidayDate[0], 'd/m/Y');
                $holidayEndDate = $this->formatSQLDate($holidayDate[1], 'd/m/Y');
                $holidays = array_merge($this->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
            }
        }
        $datename = date('l', strtotime($today));
        $dateType = WEEK_DAY;
        if (in_array($datename, $weekends)) {
            $dateType = WEEK_END;
        }
        if (in_array($today, $holidays)) {
            $dateType = HOLIDAY;
        }
        $roomPrice = $this->RoomPrices->find()->where(['room_id' => $roomId, 'room_day' => $today, 'type' => $dateType])->first();

        if (!$roomPrice || !$roomPrice->price) {
            $roomPrice = $this->RoomPrices->find()->where(['room_id' => $roomId, 'room_day' => $today, 'type' => WEEK_DAY])->first();
        }

        if ($roomPrice) {
            if ($isRegular) {
                $res['price'] = $roomPrice->price;
            } else {
                $price_agency = ($roomPrice->price_agency != 0) ? $roomPrice->price_agency : $hotel['price_agency'];
                $price_customer = ($roomPrice->price_customer != 0) ? $roomPrice->price_customer : $hotel['price_customer'];
                $res['price'] = $roomPrice->price + $price_agency;
                $res['revenue'] = 0;
            }
            $res['available_count'] = $roomPrice->available;
        } else {
            $res['status'] = false;
            $res['available_count'] = null;
            $res['message'] = 'Giá ngày ' . date('d-m-Y', strtotime($today)) . ' chưa được cập nhật';

        }
        return $res;
    }

    public function calculateHotelPriceByListRoomIds($hotel, $rooms, $dates, $isRegular = false)
    {
        $res = ['status' => true, 'message' => '', 'price' => 0];
        $this->RoomPrices = TableRegistry::getTableLocator()->get('RoomPrices');
        $weekends = json_decode($hotel['weekend'], true);
        if (!$weekends) {
            $weekends = [];
        }
        $holidayDates = json_decode($hotel['holidays'], true);
        $holidays = [];
        if ($holidayDates) {
            foreach ($holidayDates as $holidayDate) {
                $holidayDate = explode(' - ', $holidayDate);
                $holidayStartDate = $this->formatSQLDate($holidayDate[0], 'd/m/Y');
                $holidayEndDate = $this->formatSQLDate($holidayDate[1], 'd/m/Y');
                $holidays = array_merge($this->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
            }
        }
        $dataRoomPrices = $roomIds = [];
        foreach ($rooms as $room) {
            $dataRoomPrices[$room['id']] = [
                'singlePrice' => 0,
                'available_count' => 0,
                'totalPrice' => 0,
                'revenue' => 0
            ];
            $roomIds[] = $room['id'];
        }

        foreach ($dates as $key => $date) {
            $datename = date('l', strtotime($date));
            $dateType = WEEK_DAY;
            if (in_array($datename, $weekends)) {
                $dateType = WEEK_END;
            }
            if (in_array($date, $holidays)) {
                $dateType = HOLIDAY;
            }
            if ($roomIds) {
                $roomPrices = $this->RoomPrices->find()->where(['room_id IN' => $roomIds, 'room_day' => $date, 'type' => $dateType]);

                foreach ($roomPrices as $k => $roomPrice) {

                    if ($isRegular) {
                        $price = $roomPrice->price;
                    } else {
                        $price_agency = ($roomPrice->price_agency) ? $roomPrice->price_agency : $hotel['price_agency'];
                        $price_customer = ($roomPrice->price_customer) ? $roomPrice->price_customer : $hotel['price_customer'];
                        $price = $roomPrice->price + $price_agency + $price_customer;
                        $revenue = $price_customer;
                    }
                    if ($key == 0) {
                        $dataRoomPrices[$roomPrice->room_id]['singlePrice'] = $price;
                        $dataRoomPrices[$roomPrice->room_id]['singleRevenue'] = $revenue;
                        $dataRoomPrices[$roomPrice->room_id]['available_count'] = $roomPrice->available;
                    }
                    $dataRoomPrices[$roomPrice->room_id]['totalPrice'] += $price;
                    $dataRoomPrices[$roomPrice->room_id]['revenue'] += $revenue;
                }
            }
        }

        return $dataRoomPrices;
    }

    public function calculateHotelRevenue($hotel, $roomId, $today = null)
    {
        if (!$today) {
            $today = date('Y-m-d');
        }
        $datename = date('l', strtotime($today));

        $weekends = json_decode($hotel['weekend'], true);
        if (!$weekends) {
            $weekends = [];
        }
        $holidayDates = json_decode($hotel['holidays'], true);
        $holidays = [];
        if ($holidayDates) {
            foreach ($holidayDates as $holidayDate) {
                $holidayDate = explode(' - ', $holidayDate);
                $holidayStartDate = $this->formatSQLDate($holidayDate[0], 'd/m/Y');
                $holidayEndDate = $this->formatSQLDate($holidayDate[1], 'd/m/Y');
                $holidays = array_merge($this->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
            }
        }

        $dateType = WEEK_DAY;
        if (in_array($datename, $weekends)) {
            $dateType = WEEK_END;
        }
        if (in_array($today, $holidays)) {
            $dateType = HOLIDAY;
        }

        $this->RoomPrices = TableRegistry::getTableLocator()->get('RoomPrices');
        $roomPrice = $this->RoomPrices->find()->where(['room_id' => $roomId, 'room_day' => $today, 'type' => $dateType])->first();
        if ($roomPrice && $roomPrice->price_customer) {
            return $roomPrice->price_customer;
        } else {
            $roomPrice = $this->RoomPrices->find()->where(['room_id' => $roomId, 'room_day' => $today, 'type' => WEEK_END])->first();
            if ($roomPrice && $roomPrice->price_customer) {
                return $roomPrice->price_customer;
            } else {
                $roomPrice = $this->RoomPrices->find()->where(['room_id' => $roomId, 'room_day' => $today, 'type' => WEEK_DAY])->first();
                if ($roomPrice && $roomPrice->price_customer) {
                    return $roomPrice->price_customer;
                } else {
                    return $hotel['price_customer'];
                }
            }
        }
    }

    public function calculateHotelSaleRevenue($hotel, $roomId, $today = null)
    {
        if (!$today) {
            $today = date('Y-m-d');
        }
        $datename = date('l', strtotime($today));
        $weekends = json_decode($hotel['weekend'], true);
        if (!$weekends) {
            $weekends = [];
        }
        $holidayDates = json_decode($hotel['holidays'], true);
        $holidays = [];
        if ($holidayDates) {
            foreach ($holidayDates as $holidayDate) {
                $holidayDate = explode(' - ', $holidayDate);
                $holidayStartDate = $this->formatSQLDate($holidayDate[0], 'd/m/Y');
                $holidayEndDate = $this->formatSQLDate($holidayDate[1], 'd/m/Y');
                $holidays = array_merge($this->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
            }
        }

        $dateType = WEEK_DAY;
        if (in_array($datename, $weekends)) {
            $dateType = WEEK_END;
        }
        if (in_array($today, $holidays)) {
            $dateType = HOLIDAY;
        }

        $this->RoomPrices = TableRegistry::getTableLocator()->get('RoomPrices');
        $roomPrice = $this->RoomPrices->find()->where(['room_id' => $roomId, 'room_day' => $today, 'type' => $dateType])->first();
        if ($roomPrice) {
            if ($roomPrice->price_agency) {
                return $roomPrice->price_agency;
            } else {
                return $hotel['price_agency'];
            }
        } else {
            return $hotel['price_agency'];
        }
    }

    public function checkFreeChildSurcharge($ages, $standard_age, $total_standard_child = 1)
    {
        $countStandardChild = 1;
        foreach ($ages as $key => $age) {
            if ($age <= $standard_age) {
                $countStandardChild++;
                unset($ages[$key]);
                if ($countStandardChild > $total_standard_child) {
                    break;
                }
            }
        }
        $newAges = array_values($ages);
        return $newAges;
    }

    public function calChildSurcharge($ages, $hotelSurChild)
    {
        $surcharge = 0;
        if ($hotelSurChild) {
            $childSurOptions = json_decode($hotelSurChild->options, true);
            foreach ($ages as $age) {
                foreach ($childSurOptions as $option) {
                    if ($age >= $option['start'] && $age <= $option['end']) {
                        $surcharge += $option['price'];
                        break;
                    }
                }
            }
        }
        return $surcharge;
    }

    public function calHotelSurcharge($hotel, $booking_rooms, $surcharge_type, $quantity, $other_id)
    {
        $surcharge_price = 0;

        $weekends = json_decode($hotel->weekend, true);
        $holidayDates = json_decode($hotel->holidays, true);
        $holidays = [];
        if ($holidayDates) {
            foreach ($holidayDates as $holidayDate) {
                $holidayDate = explode(' - ', $holidayDate);
                $holidayStartDate = $this->formatSQLDate($holidayDate[0], 'd/m/Y');
                $holidayEndDate = $this->formatSQLDate($holidayDate[1], 'd/m/Y');
                $holidays = array_merge($this->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
            }
        }
        $this->HotelSurcharges = TableRegistry::getTableLocator()->get('HotelSurcharges');
        $this->Rooms = TableRegistry::getTableLocator()->get('Rooms');
        switch ($surcharge_type) {
            case SUR_WEEKEND:
                $hotelSurWeekend = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_WEEKEND])->first();
                $hotelSurHoliday = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_HOLIDAY])->first();

                foreach ($booking_rooms as $booking_room) {
                    $calSDate = $this->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                    $calEDate = $this->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                    $dates = $this->_dateRange($calSDate, $calEDate);
                    $tmpSurWeekend = 0;

                    foreach ($dates as $date) {
                        if ($holidays) {
                            if (in_array($date, $holidays) && $hotelSurHoliday) {
                                continue;
                            }
                        }

                        if ($weekends) {
                            if (in_array(date('l', strtotime($date)), $weekends) && $hotelSurWeekend) {
                                $tmpSurWeekend += $hotelSurWeekend->price;
                            }
                        }
                    }
                    $surcharge_price += $tmpSurWeekend * $booking_room['num_room'];
                }
                break;
            case SUR_HOLIDAY:
                $hotelSurHoliday = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_HOLIDAY])->first();
                foreach ($booking_rooms as $booking_room) {
                    $calSDate = $this->formatSQLDate($booking_room['start_date'], 'd-m-Y');
                    $calEDate = $this->formatSQLDate(date('d-m-Y', strtotime($booking_room['end_date'] . "-1 days")), 'd-m-Y');
                    $dates = $this->_dateRange($calSDate, $calEDate);
                    $tmpSurHoliday = 0;

                    foreach ($dates as $date) {
                        if ($holidays) {
                            if (in_array($date, $holidays) && $hotelSurHoliday) {
                                $tmpSurHoliday += $hotelSurHoliday->price;
                            }
                        }
                    }
                    $surcharge_price += $tmpSurHoliday * $booking_room['num_room'];
                }
                break;
            case SUR_ADULT:
                $hotelSurAdult = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_ADULT])->first();
                $surcharge_price = 0;
                foreach ($booking_rooms as $booking_room) {
                    $total_day = $this->dateDiffInDays($booking_room['start_date'], $booking_room['end_date']);
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                            if ($booking_room['num_adult'] >= $roomTotalAdult) {
                                if ($hotelSurAdult) {
                                    $surcharge_price += ($hotelSurAdult->price * ($booking_room['num_adult'] - $roomTotalAdult)) * $total_day;
                                }
                            }
                        }
                    }
                }
                break;
            case SUR_CHILDREN:
                $hotelSurChild = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => SUR_CHILDREN])->first();
                foreach ($booking_rooms as $booking_room) {
                    $total_day = $this->dateDiffInDays($booking_room['start_date'], $booking_room['end_date']);
                    $room = $this->Rooms->get($booking_room['room_id']);
                    $roomTotalMaxAdult = $room->max_adult * $booking_room['num_room'];
                    $roomTotalAdult = $room->num_adult * $booking_room['num_room'];
                    $roomTotalChildren = $room->num_children * $booking_room['num_room'];
                    $roomTotalMaxPeople = $room->max_people * $booking_room['num_room'];
                    if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                        if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                            if (isset($booking_room['child_ages'])) {
                                $child_ages = $booking_room['child_ages'];
                                if ($booking_room['num_adult'] < $roomTotalAdult) {
                                    $bonusAdult = ($roomTotalAdult - $booking_room['num_adult']);
                                    rsort($booking_room['child_ages']);
                                    $child_ages = array_slice($booking_room['child_ages'], $bonusAdult, count($booking_room['child_ages']) - $bonusAdult);
                                }
                                $total_standard_child = $booking_room['num_room'] * $room->num_children;
                                $child_ages = $this->checkFreeChildSurcharge($child_ages, $room->standard_child_age, $total_standard_child);
                                $childSurcharge = $this->calChildSurcharge($child_ages, $hotelSurChild);
                                $surcharge_price += $childSurcharge * $total_day;
                            } else {
                                $surcharge_price += 0;
                            }

                        }
                    }
                }
                break;
            case SUR_BONUS_BED:
            case SUR_BREAKFAST:
                $hotel_surcharge = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => $surcharge_type])->first();
                $surcharge_price = $hotel_surcharge->price * $quantity;
                break;
            case SUR_CHECKIN_SOON:
            case SUR_CHECKOUT_LATE:
                $hotel_surcharge = $this->HotelSurcharges->find()->where(['hotel_id' => $hotel->id, 'surcharge_type' => $surcharge_type])->first();
                $options = json_decode($hotel_surcharge->options, true);

                foreach ($booking_rooms as $booking_room) {
                    $total_room_price = 0;
                    if ($surcharge_type == SUR_CHECKIN_SOON) {
                        $calDate = $booking_room['start_date'];
                    }
                    if ($surcharge_type == SUR_CHECKOUT_LATE) {
                        $calDate = $booking_room['end_date'];
                    }

                    $room_price = 0;
                    $calDate = $this->formatSQLDate($calDate, 'd-m-Y');
                    if ($calDate) {
                        $resPrice = $this->calculateHotelPrice($hotel, $booking_room['room_id'], $calDate, true);
                        $room_price = $resPrice['price'];
                        $total_room_price += $room_price * $booking_room['num_room'];
                    }
                    foreach ($options as $option) {
                        $cTime = \DateTime::createFromFormat('H:i', $quantity);

                        $sTime = \DateTime::createFromFormat('H:i', $option['start']);
                        $eTime = \DateTime::createFromFormat('H:i', $option['end']);
                        if ($cTime >= $sTime && $cTime <= $eTime) {
                            $surcharge_price += ceil($total_room_price * $option['price'] / 100);
                            break;
                        }
                    }
                }
                break;
            case SUR_OTHER:
                $hotel_surcharge = $this->HotelSurcharges->get($other_id);
                $surcharge_price = $hotel_surcharge->price * $quantity;
                break;
        }
        return $surcharge_price;
    }

    public function saveRoomPrice($hotel, $room)
    {
        // Delete all record
        $this->RoomPrices = TableRegistry::get('RoomPrices');
        $this->RoomPrices->deleteAll(['room_id' => $room->id]);
        $conn = ConnectionManager::get('default');
        $conn->execute("ALTER TABLE `room_prices` AUTO_INCREMENT=1;");
        // insert Holidays price
        if (isset($room->holiday_price)) {
            $holidays = json_decode($hotel->holidays, true);
            $holiday_datas = [];
            if (is_array($holidays)) {
                foreach ($holidays as $holiday) {
                    $dates = explode(' - ', $holiday);
                    $sDate = $this->formatSQLDate($dates[0], 'd/m/Y');
                    $eDate = $this->formatSQLDate($dates[1], 'd/m/Y');
                    $dateRange = $this->_dateRange($sDate, $eDate);
                    foreach ($dateRange as $day) {
                        $item = [
                            'room_id' => $room->id,
                            'room_day' => $day,
                            'price' => $room->holiday_price,
                            'available' => NULL,
                            'type' => HOLIDAY,
                            'price_agency' => isset($room->holiday_price_agency) ? $room->holiday_price_agency : $hotel->price_agency,
                            'price_customer' => isset($room->holiday_price_customer) ? $room->holiday_price_customer : $hotel->price_customer
                        ];
                        $holiday_datas[] = $item;
                    }
                }
                $roomPriceHoliday = $this->RoomPrices->newEntities($holiday_datas);
                $this->RoomPrices->saveMany($roomPriceHoliday);
            }
        }
        // insert not holiday price
        $price_rooms = json_decode($room->list_price, true);
        if ($price_rooms) {
            foreach ($price_rooms as $price) {
                if (isset($price['weekday']) && isset($price['weekend']) && isset($price['price_agency']) && isset($price['price_customer'])) {
                    $dates = explode(' - ', $price['dates']);
                    $sDate = $this->formatSQLDate($dates[0], 'd/m/Y');
                    $eDate = $this->formatSQLDate($dates[1], 'd/m/Y');
                    $dateRange = $this->_dateRange($sDate, $eDate);
                    $dataWeekday = $dataWeekend = [];
                    foreach ($dateRange as $day) {
                        $weekday_data_item = [
                            'room_id' => $room->id,
                            'room_day' => $day,
                            'price' => str_replace(',', '', $price['weekday']),
                            'available' => NULL,
                            'type' => WEEK_DAY,
                            'price_agency' => str_replace(',', '', $price['price_agency']),
                            'price_customer' => str_replace(',', '', $price['price_customer'])
                        ];
                        $weekend_data_item = [
                            'room_id' => $room->id,
                            'room_day' => $day,
                            'price' => str_replace(',', '', $price['weekend']),
                            'available' => NULL,
                            'type' => WEEK_END,
                            'price_agency' => str_replace(',', '', $price['price_agency']),
                            'price_customer' => str_replace(',', '', $price['price_customer'])
                        ];
                        $dataWeekday[] = $weekday_data_item;
                        $dataWeekend[] = $weekend_data_item;
                    }
                    $roomPriceWeekday = $this->RoomPrices->newEntities($dataWeekday);
                    $roomPriceWeekend = $this->RoomPrices->newEntities($dataWeekend);
                    $this->RoomPrices->saveMany($roomPriceWeekday);
                    $this->RoomPrices->saveMany($roomPriceWeekend);
                }
            }
        }
    }

    private function dateDiffInDays($date1, $date2)
    {
        // Calulating the difference in timestamps
        $diff = strtotime($date2) - strtotime($date1);

        // 1 day = 24 hours
        // 24 * 60 * 60 = 86400 seconds
        return intval(abs(round($diff / 86400)));
    }

    private function _createListSurcharge()
    {
        $surcharges = [
            SUR_WEEKEND => 'Phụ thu Cuối tuần',
            SUR_HOLIDAY => 'Phụ thu Lễ Tết',
            SUR_ADULT => 'Phụ thu Người lớn',
            SUR_CHILDREN => 'Phụ thu Trẻ em',
            SUR_BONUS_BED => 'Phụ thu Giường phụ',
            SUR_BREAKFAST => 'Phụ thu Ăn sáng',
            SUR_CHECKIN_SOON => 'Phụ thu Check In sớm',
            SUR_CHECKOUT_LATE => 'Phụ thu Check Out muộn',
            SUR_OTHER => 'Phụ thu khác',
        ];
        return $surcharges;
    }

    public function writeLogFile($data, $type)
    {
        $logFile = file('./files/log.txt');
        switch ($type) {
            case HOTEL:
                $text = "Khách sạn";
                break;
            case HOMESTAY:
                $text = "Homestay";
                break;
            case LANDTOUR:
                $text = "Landtour";
                break;
            case VOUCHER:
                $text = "Voucher";
                break;
        }
        if (count($logFile) > 0) {
            $logFile[count($logFile) - 1] .= "\r\n";
        }
        $logFile[] = date('H:i:s d-m-Y') . " " . $text . ": " . json_encode($data, JSON_UNESCAPED_UNICODE);
        file_put_contents("./files/log.txt", $logFile);
    }

    public function writeLogFileApi($data)
    {
        $logFile = file('./files/log_api.txt');
        if (count($logFile) > 0) {
            $logFile[count($logFile) - 1] .= "\r\n";
        }
        $logFile[] = date('H:i:s d-m-Y') . " data: " . ": " . json_encode($data, JSON_UNESCAPED_UNICODE);
        file_put_contents("./files/log_api.txt", $logFile);
    }

    public function getSurchargeName($type)
    {
        $surcharges = self::_createListSurcharge();
        if (isset($surcharges[$type])) {
            return $surcharges[$type];
        } else {
            return '';
        }
    }

    public function newGeoCoding($address)
    {
        $url = "https://locationiq.com/v1/search.php?key=" . LOCATIONIQ_TOKEN . "&q=" . urlencode($address) . "&format=json";
//        if (file_get_contents($url)) {
//            $jsonGeo = file_get_contents($url);
//            $geocoding = json_decode($jsonGeo, true);
//        } else {
//            $geocoding = '';
//        }
        $geocoding = '';

        return $geocoding;
    }

    public function getJson($url, $header)
    {
        // Prepare new cURL resource
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        // Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Submit the POST request
        $result_json = curl_exec($ch);

        // Close cURL session handle
        curl_close($ch);
        $result = json_decode($result_json, true);
        return $result;
    }

    public function postJson($url, $data, $header)
    {
        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($data);
//        $header[] = 'Content-Length: ' . strlen($jsonDataEncoded);
//        dd($header);

        // Prepare new cURL resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header,
        ));
        $result_json = curl_exec($ch);

        // Close cURL session handle
        curl_close($ch);
        $result = json_decode($result_json, true);
        return $result;
    }

    public function postJsonEncoded($url, $data, $header)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);


// receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        return $server_output;
    }

    public function getToken($testUrl)
    {
        $currentTime = date('Y-m-d H:i:s');
        $this->VinhmsAccessTokens = TableRegistry::get('VinhmsAccessTokens');
        $token = $this->VinhmsAccessTokens->find()->where([
            'start_time <=' => $currentTime,
            'expire_time >=' => $currentTime
        ])->first();
        if (!$token) {
            $url = $testUrl . "/crs-partner/v1/anonymous/token";
            $header = [
                'Content-Type: application/json'
            ];
            $data = [
                'username' => 'bookingvinpearl@mustgo.vn',
                'password' => '27TDHnguoidonghanh'
            ];
            $res = $this->postJson($url, $data, $header);
            if ($res['authentication_token']) {
                $newToken = $this->VinhmsAccessTokens->newEntity();
                $newToken = $this->VinhmsAccessTokens->patchEntity($newToken, [
                    'access_token' => $res['authentication_token'],
                    'start_time' => date('Y-m-d H:i:s', time()),
                    'expire_time' => date('Y-m-d H:i:s', time() + $res['expires_in'])
                ]);
                $this->VinhmsAccessTokens->save($newToken);
                return $res['authentication_token'];
            }
        } else {
            return $token->access_token;
        }
    }

    public function getListHotel($testUrl, $page, $limit)
    {
        $url = $testUrl . "/pms-property/v1/hotels/info?page=" . $page . "&limit=" . $limit;
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $res = $this->getJson($url, $header);
        return $res;
    }

    public function getDetailHotel($testUrl, $hotelId)
    {
        $url = $testUrl . "/pms-property/v1/hotels/info/" . $hotelId;
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $res = $this->getJson($url, $header);
        return $res;
    }

    public function searchRoomAvailability($testUrl, $data, $page = 0, $limit = 100)
    {
        $url = $testUrl . "/proxy-booking-portal/v1/get-room-availability";
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json'
        ];

        $dataPost = [
            'arrivalDate' => $data['arrivalDate'],
            'departureDate' => $data['departureDate'],
            'propertyID' => $data['propertyID'],
            'numberOfRoom' => $data['numberOfRoom'],
            "distributionChannel" => "10dcfec5-8698-4a6f-8398-f50fcc648a10",
            'roomOccupancy' => $data['roomOccupancy'],
        ];

        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function detailHotelHmsAvailability($testUrl, $data)
    {
        $url = $testUrl . "/res-booking/booking/get-room-detail-availability";
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json'
        ];

        $dataPost = [
            'arrivalDate' => $data['arrivalDate'],
            'departureDate' => $data['departureDate'],
            'propertyID' => $data['propertyID'],
            'numberOfRoom' => $data['numberOfRoom'],
            "distributionChannelId" => "10dcfec5-8698-4a6f-8398-f50fcc648a10",
            'roomOccupancy' => $data['roomOccupancy'],
            'isFilteredByRoomTypeId' => true,
            'isFilteredByRatePlanId' => true,
            'ratePlanId' => $data['ratePlanId'],
            'roomTypeId' => $data['roomTypeId'],
        ];

        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getDetailRoomAvailability($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json'
        ];
        $dataPost = [
            "organization" => "hms",
            "arrivalDate" => $data['arrivalDate'],
            "departureDate" => $data['departureDate'],
            "numberOfRoom" => $data['numberOfRoom'],
            "roomOccupancy" => [
                "numberOfAdult" => $data['numberOfAdult'],
                "otherOccupancies" => $data['otherOccupancies'],
            ],
            "propertyId" => $data['propertyId'],
            "roomTypeId" => $data['roomTypeId'],
            "ratePlanId" => $data['ratePlanId'],
        ];

        $url = $testUrl . "/crs-partner/v1/public/get-room-availability";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getBookablePackage($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
            "requestId" => $data['requestId'],
            "languageCode" => $data['languageCode'],
            "organization" => $data['organization'],
            "organizationId" => $data['organizationId'],
            "pageIndex" => $data['pageIndex'],
            "pageSize" => $data['pageSize'],
            "sorts" => $data['sorts'],
            "arrivalDate" => $data['arrivalDate'],
            "departureDate" => $data['departureDate'],
            "roomOccupancy" => [
                "numberOfAdult" => $data['numberOfAdult'],
                "otherOccupancies" => $data['otherOccupancies'],
            ],
            "ratePlanId" => $data['ratePlanId'],
            "roomTypeId" => $data['roomTypeId'],
        ];

        $url = $testUrl . "/res-booking/booking/get-bookable-package-availability";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getHotelAvailability($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
            "arrivalDate" => $data["arrivalDate"],
            "departureDate" => $data["departureDate"],
            "numberOfRoom" => $data["numberOfRoom"],
            "propertyIds" => $data["propertyIds"],
            "roomOccupancy" => [
                "numberOfAdult" => $data["numberOfAdult"],
                "otherOccupancies" => $data["otherOccupancies"],
            ],
        ];

        $url = $testUrl . "/crs-partner/v1/public/get-room-availability";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function createBooking($testUrl, $booking)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
            'Content-Type : application/json',
            'Content-Type: text/plain'
        ];

        $dataPost = [
            "propertyId" => $booking->hotel->vinhms_code,
            "arrivalDate" => date('Y-m-d', strtotime($booking->start_date)),
            "departureDate" => date('Y-m-d', strtotime($booking->end_date)),
            "reservations" => []
        ];

        $listRoom = [];
        foreach ($booking->vinhmsbooking_rooms as $room) {
            if (!isset($listRoom[$room->room_index])) {
                $listRoom[$room->room_index]['vinhms_name'] = $room['vinhms_name'];
                $listRoom[$room->room_index]['num_adult'] = $room['num_adult'];
                $listRoom[$room->room_index]['num_kid'] = $room['num_kid'];
                $listRoom[$room->room_index]['num_child'] = $room['num_child'];
                $listRoom[$room->room_index]['total_price'] = $room['price'];
                $listRoom[$room->room_index]['packages'][] = $room;
            } else {
                $listRoom[$room->room_index]['total_price'] += $room['price'];
                $listRoom[$room->room_index]['packages'][] = $room;
            }
        }
        $booking->vinhmsbooking_rooms = $listRoom;

//        dd($booking->vinhmsbooking_rooms);

        foreach ($booking->vinhmsbooking_rooms as $k => $vinhmsbooking_room) {
            $dataPost['reservations'][$k] = [
                "roomOccupancy" => [
                    "numberOfAdult" => $vinhmsbooking_room['num_adult'],
                    "otherOccupancies" => [
                        [
                            "otherOccupancyRefID" => "child",
                            "otherOccupancyRefCode" => "child",
                            "quantity" => $vinhmsbooking_room['num_child'],
                        ],
                        [
                            "otherOccupancyRefID" => "infant",
                            "otherOccupancyRefCode" => "infant",
                            "quantity" => $vinhmsbooking_room['num_kid'],
                        ]
                    ]
                ],
                "totalAmount" => [
                    "amount" => $vinhmsbooking_room['total_price'],
                    "currencyCode" => "VND"
                ],
                "isSpecialRequestSpecified" => false,
                "specialRequests" => [],
                "isProfilesSpecified" => true,
                "profiles" => [
                    [
                        "profileRefID" => "ccf0d708-780a-4cb8-80f3-4544c357ff7f",
                        "firstName" => "VIMITRAVEL",
                        "profileType" => "TravelAgent",
                        "travelAgentCode" => "VIMITRAVEL",
                        "isPrimary" => true
                    ],
                    [
                        "firstName" => $booking->first_name,
                        "lastName" => $booking->sur_name,
                        "email" => $booking->email,
                        "phoneNumber" => $booking->phone,
                        "primarySearchValues" => [
                            "email" => $booking->email,
                            "phoneNumber" => $booking->phone
                        ],
                        "profileType" => "Guest"
                    ],
                    [
                        "firstName" => 'VIMITRAVEL',
                        "lastName" => 'VIMITRAVEL',
                        "email" => 'bookingvinpearl@mustgo.vn',
                        "phoneNumber" => '0925959777 ',
                        "primarySearchValues" => [
                            "email" => 'bookingvinpearl@mustgo.vn',
                            "phoneNumber" => '0925959777 '
                        ],
                        "travelAgentCode" => "VIMITRAVEL",
                        "profileType" => "Booker"
                    ]
                ],
                "isRoomRatesSpecified" => true,
                "roomRates" => [],
                "isPackagesSpecified" => false,
                "packages" => [],
                "distributionChannel" => "10dcfec5-8698-4a6f-8398-f50fcc648a10",
                "sourceCode" => "TOS"
            ];
            foreach ($vinhmsbooking_room['packages'] as $pK => $package) {
                $dateRange = $this->_dateRange(date('Y-m-d', strtotime($package->checkin)), date('Y-m-d', strtotime($package->checkout)));
                array_pop($dateRange);
                foreach ($dateRange as $singleDayKey => $day) {
                    $dataPost['reservations'][$k]['roomRates'][] = [
                        "stayDate" => $day,
                        "roomTypeRefID" => $package->vinhms_room_id,
                        "roomTypeCode" => $package->vinhms_room_type_code,
                        "allotmentId" => $package->vinhms_allotment_id,
                        "ratePlanRefID" => $package->vinhms_rateplan_id,
                        "ratePlanCode" => $package->vinhms_rateplan_code,
                    ];

//                $dataPost['reservations'][$k]['packages'][] = [
//                    "usedDate" => $day,
//                    "packageRefId" => $vinhmsbooking_room->vinhms_package_id,
//                    "packageCode" => $vinhmsbooking_room->vinhms_package_code,
//                    "ratePlanId" => $vinhmsbooking_room->vinhms_rateplan_id,
//                    "quantity" => 1
//                ];
                }
            }
        }
        $url = $testUrl . "/res-booking/booking";
//        $str = "curl --location --request POST '" . $url . "' --header 'Authorization " . $token . "' --data-raw '" . json_encode($dataPost) . "'";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function checkEnoughPackage($testUrl, $vinBooking)
    {
        $listAllotment = [];
        $totalAdult = $totalChild = $totalKid = 0;
        //get package
        foreach ($vinBooking->vinhmsbooking_rooms as $singleRoom) {
            $totalAdult += $singleRoom->num_adult;
            $totalChild += $singleRoom->num_child;
            $totalKid += $singleRoom->num_kid;
            if (count($listAllotment) == 0) {
                $listAllotment[] = [
                    'vinhms_room_id' => $singleRoom->vinhms_room_id,
                    'vinhms_package_code' => $singleRoom->vinhms_package_code,
                    'total_package' => 1
                ];
            } else {
                foreach ($listAllotment as $k => $allot) {
                    if ($allot['vinhms_room_id'] == $singleRoom->vinhms_room_id && $allot['vinhms_package_code'] == $singleRoom->vinhms_package_code) {
                        $listAllotment[$k]['total_package']++;
                    } else {
                        $listAllotment[] = [
                            'vinhms_room_id' => $singleRoom->vinhms_room_id,
                            'vinhms_package_code' => $singleRoom->vinhms_package_code,
                            'total_package' => 1
                        ];
                    }
                }
            }
        }
        $startDate = date('Y-m-d', strtotime($vinBooking->start_date));
        $endDate = date('Y-m-d', strtotime($vinBooking->end_date));
        $data = [
            "arrivalDate" => $startDate,
            "departureDate" => $endDate,
            "numberOfRoom" => 1,
            "propertyIds" => [$vinBooking->hotel->vinhms_code],
            "roomOccupancy" => []
        ];
        $roomOccupancy = [
            'numberOfAdult' => 2,
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
        $dataApi = $this->SearchHotelHmsAvailability($testUrl, $data);
        $enoughPackage = true;
//        dd($testUrl,$data);
        if (!empty($dataApi['data']['rates'])) {
            $havePacket = [];
            foreach ($listAllotment as $index => $singleAllot) {
                $havePacket[] = true;
                foreach ($dataApi['data']['rates'][0]['rates'] as $k => $ratePackage) {
                    if ($singleAllot['vinhms_room_id'] == $ratePackage['roomTypeID'] && $singleAllot['vinhms_package_code'] == $ratePackage['rateAvailablity']['ratePlanCode']) {
                        $havePacket[$index] = true;
                        if ($singleAllot['total_package'] > $ratePackage['rateAvailablity']['allotments'][0]['quantity']) {
                            $enoughPackage = false;
                        }
                    }
                }
            }
            foreach ($havePacket as $item) {
                if ($item == false) {
                    $enoughPackage = false;
                }
            }
        } else {
            $enoughPackage = false;
        }
//        return true;
        return $enoughPackage;
    }

    public function getReservationDetail($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
            'requestId' => '',
            'languageCode' => '',
            'organization' => '',
            'organizationId' => '',
            'reservationID' => '',
        ];

        $url = $testUrl . "/res-booking/reservation/search-reservations";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getReservationDetailAdvance($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
            'requestId' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
            'languageCode' => 'string',
            'organization' => 'string',
            'organizationId' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
            'pageIndex' => 0,
            'pageSize' => 0,
            'sorts' => [
                0 => [
                    'sortColumn' => 'string',
                    'sortOrder' => 'ASC',
                ],
            ],
            'groupFields' => [
                0 => [
                    'fields' => [
                        0 => [
                            'field' => 'NotSpecific',
                            'operation' => 'NotSpecific',
                            'compareOperator' => [
                            ],
                        ],
                    ],
                    'operation' => 'NotSpecific',
                ],
            ],
            'returnQRCode' => true,
            'isExport' => true,
        ];

        $url = $testUrl . "/res-booking/reservation/search-reservations";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getGuaranteeMethod($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
            "organization" => "VINPEARL"
        ];
        $url = $testUrl . "/res-booking/booking/" . $data['reservationId'] . "/guarantee-methods";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getGuaranteeMethodForMultiReservations($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
        ];
        $dataPost = [
            "reservations" => $data['reservations']
        ];
        $url = $testUrl . "/res-booking/booking/guarantee-methods";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function commitBooking($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
            "requestId" => $data['requestId'],
            "languageCode" => $data['languageCode'],
            "organization" => $data['organization'],
            "organizationId" => $data['organizationId'],
            "guaranteeInfos" => $data['guaranteeInfos'],
            "reservationId" => $data['reservationId'],
        ];
        $url = $testUrl . "/res-booking/booking/" . $data["reservationId"] . "/commit";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function batchCommitBooking($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
            'Accept: application/json',
            'Content-Type: application/json'
        ];
        $dataPost = [
            "organization" => "hms",
            "sendToBooker" => true,
            "sendToGuest" => false,
            "items" => $data['items'],
        ];
        $url = $testUrl . "/res-booking/booking/batch-commit";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getCancelationMethods($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
        ];
        $url = $testUrl . "/res-booking/booking/" . $data['reservationId'] . "/cancel-methods";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function cancelBooking($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token
        ];
        $dataPost = [
            "requestId" => $data["requestId"],
            "languageCode" => $data["languageCode"],
            "organization" => $data["organization"],
            "organizationId" => $data["organizationId"],
            "cancelInfos" => $data["cancelInfos"],
            "reservationId" => $data["reservationId"],
        ];
        $url = $testUrl . "/res-booking/booking/" . $data["reservationId"] . "/cancel";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function notifyNewAgentBookingTelegram($bookingCode, $telegramId, $telegramUsername)
    {
        $url = "https://api.telegram.org/bot1712965407:AAEgOhEeg_18C6J5R9-Z2ZyxkHpBDzPSfeo/sendMessage?chat_id=-1001145382889&parse_mode=Markdown&text=";
        $text = "[" . $telegramUsername . "](tg://user?id=" . $telegramId . ") vừa có 1 booking mới mã " . $bookingCode;
        $text = str_replace(" ", "%20", $text);
        $url .= $text;
        $header = [];
        $res = $this->getJson($url, $header);
        return $res;
    }

    public function notifyNewCustomerBookingTelegram($bookingCode)
    {
        $url = "https://api.telegram.org/bot1712965407:AAEgOhEeg_18C6J5R9-Z2ZyxkHpBDzPSfeo/sendMessage?chat_id=-1001145382889&parse_mode=Markdown&text=";
        $text = "Khách lẻ vừa đặt booking mã " . $bookingCode;
        $text = str_replace(" ", "%20", $text);
        $url .= $text;
        $header = [];
        $res = $this->getJson($url, $header);
        return $res;
    }

    public function notifyCountNewBooking($sale_id)
    {
        $firestore = new FirestoreClient([
            'projectId' => 'mustgoproj',
        ]);
        try {
            $this->Bookings = TableRegistry::get('Bookings');
            $this->Vinhmsbookings = TableRegistry::get('Vinhmsbookings');
            $countNewBooking = $this->Bookings->find()->where(['status' => 1, 'sale_id' => $sale_id])->count();
            $countNewBookingVinpearl = $this->Vinhmsbookings->find()->where(['status' => 1, 'sale_id' => $sale_id])->count();
            if ($firestore->collection('booking')->document($sale_id)->snapshot()->exists()) {
                $document = $firestore->collection('booking')->document($sale_id);
                $document->set([
                    'new_booking_count' => $countNewBooking + $countNewBookingVinpearl
                ]);
            } else {
                $firestore->collection('booking')->document($sale_id)->create([
                    'new_booking_count' => $countNewBooking + $countNewBookingVinpearl
                ]);
            }
        } catch (Exception $exception) {

        }
        return true;
    }

    public function notifyAgentEditBookingTelegram($bookingCode, $telegramId, $telegramUsername)
    {
        $url = "https://api.telegram.org/bot1712965407:AAEgOhEeg_18C6J5R9-Z2ZyxkHpBDzPSfeo/sendMessage?chat_id=-1001145382889&parse_mode=Markdown&text=";
        $text = "[" . $telegramUsername . "](tg://user?id=" . $telegramId . ") khách hàng vừa sửa booking mã " . $bookingCode;
        $text = str_replace(" ", "%20", $text);
        $url .= $text;
        $header = [];
        $res = $this->getJson($url, $header);
        return $res;
    }

    public function notifyCustomerEditBookingTelegram($bookingCode)
    {
        $url = "https://api.telegram.org/bot1712965407:AAEgOhEeg_18C6J5R9-Z2ZyxkHpBDzPSfeo/sendMessage?chat_id=-1001145382889&parse_mode=Markdown&text=";
        $text = "Khách hàng vừa sửa booking mã " . $bookingCode;
        $text = str_replace(" ", "%20", $text);
        $url .= $text;
        $header = [];
        $res = $this->getJson($url, $header);
        return $res;
    }

    public function generateRandomCode($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // API VINHMS V1
    public function SearchHotelHmsAvailability($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json'
        ];
        $dataPost = [
            "arrivalDate" => $data['arrivalDate'],
            "departureDate" => $data['departureDate'],
            "numberOfRoom" => $data['numberOfRoom'],
            "roomOccupancy" => $data['roomOccupancy'],
            "propertyIds" => $data["propertyIds"],
            "distributionChannelId" => "10dcfec5-8698-4a6f-8398-f50fcc648a10",
        ];
        $url = $testUrl . "/res-booking/booking/get-hotel-availability";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getVinBookablePackage($testUrl, $data)
    {
        $token = $this->getToken($testUrl);
        $header = [
            'Authorization: ' . $token,
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json'
        ];
        $dataPost = [
            "arrivalDate" => $data['arrivalDate'],
            "departureDate" => $data['departureDate'],
            "numberOfRoom" => $data['numberOfRoom'],
            "roomOccupancy" => $data['roomOccupancy'],
            "propertyID" => $data["propertyIds"],
            "ratePlanId" => $data["ratePlanId"],
            "roomTypeId" => $data["roomTypeId"],
            "distributionChannelId" => "10dcfec5-8698-4a6f-8398-f50fcc648a10",
        ];

        $url = $testUrl . "/res-booking/booking/get-bookable-package-availability";
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getStatusBooking($booking, $role_id)
    {
        $booking->status_str = "";
        $booking->status_color = "";
        $booking->sort = 1;

        if ($role_id == 2) {
            switch ($booking->status) {
                case -1:
                    $booking->status_str = "Khách đã gửi đơn đặt phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 0:
                    $booking->status_str = "Đại lý mới đặt";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 1:
                    $booking->status_str = "Chờ KS xác nhận rồi gửi mail đề nghị thanh toán";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 2;
                    break;
                case 2:
                    $booking->status_str = $booking->agency_pay == 1 ? "ĐL đã TT, chờ KT TT" : "Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 0;
//                            $booking->status_str = "Đang chờ CTV thanh toán ";
                    break;
                case 3:
                    $booking->status_str = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? 'Đã thanh toán' : 'Đã thanh toán';
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 4:
                    $booking->status_str = "Hoàn thành";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 5:
                    $booking->status_str = "Đơn hàng đã bị hủy";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 99:
                    $booking->status_str = 'Đã thanh toán';
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
            }
        } else {
            switch ($booking->status) {
                case -1:
                    $booking->status_str = "Đã gửi đơn đặt phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 0:
                case 1:
                    $booking->status_str = "Đã đặt , Chờ kiểm tra tình trạng phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 2;
                    break;
                case 2:
                    if ($booking->mail_type == 0) {
                        if ($booking->payment && $booking->payment->images) {
                            $booking->status_str = "Đã thanh toán, chờ mã xác nhận";
                            $booking->status_color = "#f0ad4e";
                            $booking->sort = 3;
                        } else {
                            $booking->status_str = "Còn phòng , đề nghị thanh toán đơn hàng ";
                            $booking->status_color = "#f0ad4e";
                            $booking->sort = 0;
                        }
                    } else {
                        $booking->status_str = "Đã thanh toán";
                        $booking->status_color = "#f0ad4e";
                        $booking->sort = 3;
                    }
                    break;
                case 3:
                case 4:
                    $booking->status_str = "Hoàn thành";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 5:
                    $booking->status_str = "Đơn hàng đã bị hủy";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
            }
        }
        return $booking;
    }

    public function getStatusBookingVinpearl($booking, $role_id)
    {
        $booking->status_str = "";
        $booking->status_color = "";
        $booking->sort = 1;

        if ($role_id == 2) {
            switch ($booking->status) {
                case -1:
                    $booking->status_str = "Khách đã gửi đơn đặt phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 0:
                    $booking->status_str = "Đại lý mới đặt";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 1:
                    $booking->status_str = "Chờ KS xác nhận rồi gửi mail đề nghị thanh toán";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 2;
                    break;
                case 2:
                    $booking->status_str = $booking->agency_pay == 1 ? "ĐL đã TT, chờ KT TT" : "Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 0;
//                            $booking->status_str = "Đang chờ CTV thanh toán ";
                    break;
                case 3:
                    $booking->status_str = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? 'Đã thanh toán' : 'Đã thanh toán';
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 4:
                    $booking->status_str = "Hoàn thành";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 5:
                    $booking->status_str = "Đơn hàng đã bị hủy";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
            }
        } else {
            switch ($booking->status) {
                case -1:
                    $booking->status_str = "Đã gửi đơn đặt phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 0:
                    $booking->status_str = "Đề nghị thanh toán đơn hàng";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 0;
                    break;
                case 1:
                case 2:
                    if (isset($booking->vinpayment) && $booking->vinpayment->type != 0) {
                        if ($booking->vinpayment->type == PAYMENT_TRANSFER) {
                            if ($booking->vinpayment->images) {
                                $booking->status_str = "Đã gửi UNC, chờ Mustgo xác nhận tiền nổi, tình trạng phòng có thể hết";
                            } else {
                                $booking->status_str = "Đề nghị thanh toán đơn hàng";
                            }
                        } else if ($booking->vinpayment->type == PAYMENT_BALANCE) {
                            $booking->status_str = "Đã thanh toán";
                        } else {
                            if ($booking->vinpayment->onepaystatus == 0) {
                                $booking->status_str = "Đã thanh toán";
                            } else {
                                $booking->status_str = "Thanh toán Onepay thất bại";
                            }
                        }
                        $booking->status_color = "#f0ad4e";
                        $booking->sort = 3;
                    } else {
                        $booking->status_str = "Đề nghị thanh toán đơn hàng";
                        $booking->status_color = "#f0ad4e";
                        $booking->sort = 0;
                    }
                    break;
                case 3:
                case 4:
                    $booking->status_str = "Hoàn thành";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 5:
                    $booking->status_str = "Đơn hàng đã bị hủy";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
            }
        }
        return $booking;
    }

    public function getStatusBookingLandtour($booking, $role_id)
    {

        $booking->status_str = "";
        $booking->status_color = "";
        $booking->sort = 1;

        if ($role_id == 2) {
            switch ($booking->status) {
                case -1:
                    $booking->status_str = "Khách đã gửi đơn đặt phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 0:
                    $booking->status_str = "Đại lý mới đặt";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 1:
                    $booking->status_str = "Chờ KS xác nhận rồi gửi mail đề nghị thanh toán";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 2;
                    break;
                case 2:
                    $booking->status_str = $booking->agency_pay == 1 ? "ĐL đã TT, chờ KT TT" : "Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 0;
//                            $booking->status_str = "Đang chờ CTV thanh toán ";
                    break;
                case 3:
                    $booking->status_str = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? 'Đã thanh toán' : 'Đã thanh toán';
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 4:
                    $booking->status_str = "Hoàn thành";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 5:
                    $booking->status_str = "Đơn hàng đã bị hủy";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
            }
        } else {
            switch ($booking->status) {
                case -1:
                    $booking->status_str = "Đã gửi đơn đặt phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 1;
                    break;
                case 0:
                    $booking->status_str = "Đã đặt , Chờ kiểm tra tình trạng phòng";
                    $booking->status_color = "#337ab7";
                    $booking->sort = 2;
                    break;
                case 1:
                    $booking->status_str = "Đã đặt , Chờ kiểm tra tình trạng phòng";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 2;
                    break;
                case 2:
                    $booking->status_str = "Còn phòng , đề nghị thanh toán đơn hàng ";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 0;
                    break;
                case 3:
                    $booking->status_str = "Đã thanh toán, chờ mã xác nhận";
                    $booking->status_color = "#f0ad4e";
                    $booking->sort = 4;
                    break;
                case 4:
                    $booking->status_str = "Hoàn thành";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
                case 5:
                    $booking->status_str = "Đơn hàng đã bị hủy";
                    $booking->status_color = "#d9534f";
                    $booking->sort = 4;
                    break;
            }
        }
        return $booking;
    }

    public function sendNotifical($postData)
    {
        $ch = curl_init();
// Set cURL opts
        curl_setopt($ch, CURLOPT_URL, EXPO_API_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        curl_close($ch);
//        echo $response;
    }

    // hotel link api
    public function getHotelLinkToken()
    {
        $currentTime = date('Y-m-d H:i:s');
        $this->ChannelAccessTokens = TableRegistry::get('ChannelAccessTokens');
        $token = $this->ChannelAccessTokens->find()->where([
            'start_time <=' => $currentTime,
            'expire_time >=' => $currentTime
        ])->first();

        if (!$token) {
            $url = "http://api.hotellinksolutions-staging.com/external/oAuth/token";
            $header = [
                'Authorization: Basic {b3RhOm90YQ==}',
            ];
            $res = $this->getJson($url, $header);
            if ($res['data']['access_token']) {
                $newToken = $this->ChannelAccessTokens->newEntity();
                $newToken = $this->ChannelAccessTokens->patchEntity($newToken, [
                    'access_token' => $res['data']['access_token'],
                    'start_time' => date('Y-m-d H:i:s', time()),
                    'expire_time' => date('Y-m-d H:i:s', time() + $res['data']['expires_in'])
                ]);
                $this->ChannelAccessTokens->save($newToken);
                return $res['data']['access_token'];
            }
        } else {
            return $token->access_token;
        }
    }

    public function getRatePlans($hotelLinkCode)
    {
        $url = "http://api.hotellinksolutions-staging.com/external/ota/getRatePlans";
        $token = $this->getHotelLinkToken();
        $header = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        $dataPost = [
            'Credential' => [
                'HotelId' => $hotelLinkCode,
                "HotelAuthenticationChannelKey" => "74dd9b27c6d1fb5fb1289fae19878cac"
            ],
            'Lang' => "en",
        ];

        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getInventory($hotelLinkCode, $ratePlans, $fromDate, $toDate)
    {
        $url = "http://api.hotellinksolutions-staging.com/external/ota/getInventory";
        $token = $this->getHotelLinkToken();
        $header = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];
        $dataPost = [
            'RatePlans' => $ratePlans,
            'DateRange' => [
                'From' => $fromDate,
                'To' => $toDate,
            ],
            'Credential' => [
                'HotelId' => $hotelLinkCode,
                "HotelAuthenticationChannelKey" => "74dd9b27c6d1fb5fb1289fae19878cac"
            ],
            'Lang' => "en",
        ];

        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function getMeals($meal)
    {
        $meals = ['Breakfast' => 1, 'Lunch' => 2, 'Dinner' => 3, 'AllInclusive' => 4];
        foreach ($meal as $key => $value) {
            if ($value) {
                return $meals[$key];
            }
        }
        return 1;
    }

    public function getMealsShow($meal)
    {
        $meals = [1 => 'Breakfast', 2 => 'Lunch', 3 => 'Dinner', 4 => 'AllInclusive'];
        return $meals[$meal];
    }

    public function checkEnoughRoomChannel($channelBooking)
    {
        $rateplane = [];
        $roombumber = [];
        $enoughRoom = ['enough' => true, 'message' => ''];
        foreach ($channelBooking->channelbooking_rooms as $item) {
            $rateplane[] = $item->channelrateplan_code;
            if (isset($roombumber[$item->channelroom_code])) {
                $roombumber[$item->channelroom_code]++;
            } else {
                $roombumber[$item->channelroom_code] = 1;
            }
        }
        $res = $this->getInventory($channelBooking->hotel->hotel_link_code, $rateplane, $channelBooking->start_date, $channelBooking->end_date);
        if ($res['result']) {
            foreach ($res['data']['Inventories'] as $value) {
                foreach ($value['Availabilities'] as $availability) {
                    if ($availability['Quantity'] < $roombumber[$value['RoomId']]) {
                        $enoughRoom['enough'] = false;
                        $enoughRoom['message'] = 'Hết phòng ' . $value['RoomId'] . ' ngày : ' . $availability['DateRange']['From'] . ' đến ' . $availability['DateRange']['From'];
                    }
                }
                foreach ($value['RatePackages'] as $ratePackage) {
                    if (isset($ratePackage['StopSell']) && $ratePackage['StopSell'] == 1) {
                        $enoughRoom['enough'] = false;
                        $enoughRoom['message'] = 'Dừng bán gói ' . $ratePackage['RatePlanId'] . ' ngày : ' . $ratePackage['DateRange']['From'] . ' đến ' . $availability['DateRange']['From'];
                    }
                }
            }
        } else {
            $enoughRoom['enough'] = false;
            $enoughRoom['message'] = 'Không kết nối được với khách sạn.';
        }
        return $enoughRoom;
    }

    public function createBookingChannel($booking)
    {
//        dd($booking);
        $a = [
            "result" => true,
            "data" => [
                "BookingId" => "OTAB571664332350"
            ],
            "message" => "Success",
            "error" => 0,
        ];
        return $a;
        $url = "http://api.hotellinksolutions-staging.com/external/ota/saveBooking";
        $token = $this->getHotelLinkToken();
        $header = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];
        $dataRoom = [];
        foreach ($booking->channelbooking_rooms as $channelbooking_room) {
            $dateRanges = json_decode($channelbooking_room->date_range);
            $RatePerNights = [];
            foreach ($dateRanges as $dateRange) {
//                dd($dateRanges);
                $numDay = date_diff(date_create($dateRange->DateRange->From), date_create($dateRange->DateRange->To))->days;
                for ($i = 0; $i < $numDay; $i++) {
                    $RatePerNights[] = [
                        "Date" => date('Y-m-d', strtotime($dateRange->DateRange->From . '+' . $i . ' day')),
                        "Rate" => $dateRange->price,
                    ];
                }
            }
            $dataRoom[] = [
                'RatePlanId' => $channelbooking_room->channelrateplan_code,
                'Adults' => $channelbooking_room->num_adult,
                'Children' => $channelbooking_room->num_kid,
                'ExtraAdults' => 0,
                'ExtraChildren' => 0,
                'TaxFee' => 0,
                'TaxFeeArrival' => 0,
                'Discount' => 0,
                'Deposit' => 0,
                'Amount' => $channelbooking_room->price,
                'RoomRate' => [
                    "Commission" => 0,
                    "RatePerNights" => $RatePerNights
                ]
            ];
        }
        $dataPost = [
            'NotificationType' => 'New',
            'Currency' => 'VND',
            'CheckIn' => date('Y-m-d', strtotime($booking->start_date)),
            'CheckOut' => date('Y-m-d', strtotime($booking->end_date)),
            'PayAtHotel' => 0,
            'GuestDetail' => [
                'Title' => $this->checkGender($booking->gender),
                'FirstName' => $booking->first_name,
                'LastName' => $booking->sur_name,
                'Email' => $booking->email,
                'Phone' => $booking->phone,
                'Country' => $booking->nation,
            ],
            'Rooms' => $dataRoom,
            'Credential' => [
                'HotelId' => $booking->hotel->hotel_link_code,
                "HotelAuthenticationChannelKey" => "74dd9b27c6d1fb5fb1289fae19878cac"
            ],
            'Lang' => "en",
        ];
//        dd($dataPost);
        $res = $this->postJson($url, $dataPost, $header);
        return $res;
    }

    public function checkGender($number)
    {
        switch ($number) {
            case 1 :
                $res = 'Mr';
                break;
            case 2 :
                $res = 'Mrs';
            default :
                $res = 'Mr';
        }
        return $res;
    }
    //end hotel link api
}
