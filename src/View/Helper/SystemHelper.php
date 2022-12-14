<?php

/**
 * Created by PhpStorm.
 * User: Ban
 * Date: 9/13/2017
 * Time: 10:42 PM
 */

namespace App\View\Helper;

use Cake\View\Helper;
use \Datetime;
use Cake\ORM\TableRegistry;

class SystemHelper extends Helper
{

    public function splitByWords($text, $splitLength)
    {
        $string = strip_tags($text);
        if (strlen($string) > $splitLength) {
            $string = mb_substr($string, 0, $splitLength);
            if ($string[$splitLength - 1] == " ") {
                $string[$splitLength - 1] = ".";
                return $string . "..";
            } else {
                return $string . "...";
            }
        }
        return $string;
    }

    public function diffDate($date)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = new DateTime("now");
        $created = new DateTime($date);
        $interval = $created->diff($now);
        $day = $interval->d;
        return $day;
    }

    public function printObjectItemThumbnail()
    {

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

    public function countComboPrice($fromDate, $combo)
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

    public function calRoomHotelPrice($date, $room, $price_customer, $price_agency)
    {
        $priceHotel = 0;
        foreach ($room->price_rooms as $price) {
            if ($this->checkBetweenDate($date, $price->start_date, $price->end_date)) {
                $priceHotel = $price->price + $price_customer + $price_agency;
                break;
            } else {
                continue;
            }
        }
        if ($priceHotel == 0) {
            $priceHotel += $price_customer + $price_agency;
        }

        return $priceHotel;
    }

    public function convertHomestayType($type)
    {
        $name = '';
        switch ($type) {
            case WEEK_DAY:
                $name = 'T??? th??? 2 ?????n th??? 5';
                break;
            case WEEK_END:
                $name = 'T??? th??? 6 ?????n ch??? nh???t';
                break;
        }
        return $name;
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

    public function hightLightTextSearch($content, $keyword)
    {
        $replace = '<span class="text-main">' . $keyword . '</span>';
        $str = str_ireplace($keyword, $replace, $content);
        return $str;
    }

    public function calculateHotelPrice($hotel)
    {
        $this->RoomPrices = TableRegistry::get('RoomPrices');
        $weekends = json_decode($hotel['weekend'], true);
        if (!$weekends) {
            $weekends = [];
        }
        $holidayDates = json_decode($hotel['holidays'], true);
        $holidays = [];
        if ($holidayDates) {
            foreach ($holidayDates as $holidayDate) {
                $holidayDate = explode('-', $holidayDate);
                $holidayStartDate = $this->formatSQLDate(trim($holidayDate[0]), 'd/m/Y');
                $holidayEndDate = $this->formatSQLDate(trim($holidayDate[1]), 'd/m/Y');
                $holidays = array_merge($this->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
            }
        }
        $today = date('Y-m-d');
        $datename = date('l', strtotime($today));
        $dateType = WEEK_DAY;
        if (in_array($datename, $weekends)) {
            $dateType = WEEK_END;
        }
        if (in_array($today, $holidays)) {
            $dateType = HOLIDAY;
        }
        $roomPrice = $this->RoomPrices->find()->where(['room_id' => $hotel['rooms'][0]->id, 'room_day' => $today, 'type' => $dateType])->first();
        if ($roomPrice) {
            return $roomPrice->price + $hotel['price_agency'] + $hotel['price_customer'];
        } else {
            return 0;
        }

    }

    public function formatSQLDate($date_str, $format)
    {
        $test = \DateTime::createFromFormat($format, $date_str);
        return date_format($test, 'Y-m-d');
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

    private function _createListSurcharge()
    {
        $surcharges = [
            SUR_WEEKEND => 'Ph??? thu Cu???i tu???n',
            SUR_HOLIDAY => 'Ph??? thu L??? T???t',
            SUR_ADULT => 'Ph??? thu Ng?????i l???n',
            SUR_CHILDREN => 'Ph??? thu Tr??? em',
            SUR_BONUS_BED => 'Ph??? thu Gi?????ng ph???',
            SUR_BREAKFAST => 'Ph??? thu ??n s??ng',
            SUR_CHECKIN_SOON => 'Ph??? thu Check In s???m',
            SUR_CHECKOUT_LATE => 'Ph??? thu Check Out mu???n',
            SUR_OTHER => 'Ph??? thu kh??c',
        ];
        return $surcharges;
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

    public function stripVN($str) {
        $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'a', $str);
        $str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'e', $str);
        $str = preg_replace("/(??|??|???|???|??)/", 'i', $str);
        $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'o', $str);
        $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'u', $str);
        $str = preg_replace("/(???|??|???|???|???)/", 'y', $str);
        $str = preg_replace("/(??)/", 'd', $str);

        $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'A', $str);
        $str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'E', $str);
        $str = preg_replace("/(??|??|???|???|??)/", 'I', $str);
        $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'O', $str);
        $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'U', $str);
        $str = preg_replace("/(???|??|???|???|???)/", 'Y', $str);
        $str = preg_replace("/(??)/", 'D', $str);
        return $str;
    }

    public function calBookingHotelDefault($totalPrice, $booking) {
        $price = $totalPrice - $booking->sale_revenue - $booking->revenue;
        return $price;
    }

    public function calBookingHotelAgencyPay($totalPrice, $booking) {
        if ($booking->sale_id != $booking->user_id) {
            $price = $totalPrice - $booking->revenue - $booking->agency_discount;
        } else {
            $price = $totalPrice - $booking->agency_discount;
        }
        return $price;
    }

    public function calBookingHotelSaleProfit($totalPrice, $booking) {
        if ($booking->sale_id != $booking->user_id) {
            $price = $booking->sale_revenue;
        } else {
            $price = $booking->sale_revenue + $booking->revenue - $booking->agency_discount;
        }
        return $price;
    }
}
