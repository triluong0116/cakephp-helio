<?php

namespace App\Shell;

use App\Controller\AppController;
use Cake\Console\Shell;
use Cake\Controller\ComponentRegistry;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Controller\Component\UtilComponent;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Simple console wrapper around Psy\Shell.
 *
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\PriceRoomsTable $PriceRooms
 * @property \App\Model\Table\RoomPricesTable $RoomPrices
 * @property \App\Model\Table\BookingsTable $Bookings
 *
 */
class BackendShell extends Shell
{

    public function initialize() {
        $this->Util = new UtilComponent(new ComponentRegistry(), []);
    }

    public function updateRefCodeUser()
    {
        $this->loadModel('Users');
        $users = $this->Users->find()->where([
            'OR' => [
                ['role_id' => 2],
                ['role_id' => 3]
            ]
        ]);
        foreach ($users as $user) {
            if (!$user->ref_code) {
                $user = $this->Users->patchEntity($user, ['ref_code' => $this->_generateRandomString(24)]);
                $this->Users->save($user);
            }
        }
    }

    private function _generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function formatPhoneNumber()
    {
        $this->loadModel('Users');
        $users = $this->Users->find();
        foreach ($users as $user) {
            $user->phone = preg_replace('/\s+/', '', $user->phone);
            $user = $this->Users->patchEntity($user, ['phone' => $user->phone]);
            $this->Users->save($user);
        }
    }

    public function convertImageThumb()
    {

        $target_dir = WWW_ROOT . "files" . DS . "uploads";
        $files = array_diff(scandir($target_dir), array('.', '..'));
        $key = 0;
        $arrayExtension = ['jpeg', 'gif', 'png', 'jpg'];
        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($extension, $arrayExtension)) {
                $file_name = pathinfo($file, PATHINFO_FILENAME);
                if (!$this->endsWith($file_name, '_thumb')) {
                    $target_file = $file_name . '_thumb.' . $extension;
                    $old_file = $target_dir . DS . $file;
                    $thumb_file = $target_dir . DS . $target_file;

                    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
                    $detectedType = exif_imagetype($old_file);
                    $checkFileType = in_array($detectedType, $allowedTypes);
                    $haveThumb = false;
                    $endString = '_thumb.' . $extension;
                    $haveThumb = $this->endsWith($file, $endString);

                    if ($checkFileType && !$haveThumb) {
                        $key++;
                        $image = new \App\Utility\ImageResize();
                        $image->load($old_file);
                        $image->resizeToWidth(250);
                        $image->save($thumb_file);

                        echo $key . ". done \r\n";

                    }
                }
            }

        }

    }

    public function convertLonLat()
    {
        $this->loadModel('Hotels');
        $hotels = $this->Hotels->find();
        foreach ($hotels as $hotel) {
            echo "Hotel: " . $hotel->id . "\r\n";
            $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($hotel->address) . '&key=' . MAP_API);
            $output = json_decode($geocode);
            $lat = $output->results[0]->geometry->location->lat;
            $long = $output->results[0]->geometry->location->lng;

            $hotel = $this->Hotels->patchEntity($hotel, ['lon' => $long, 'lat' => $lat]);
            $this->Hotels->save($hotel);
        }

    }

    public function converLonLatReview()
    {
        $this->loadModel('Reviews');
        $reviews = $this->Reviews->find();
        foreach ($reviews as $review) {
            $places = json_decode($review->place, true);
            $newPlaces = [];
            if ($places) {
                foreach ($places as $place) {
                    if (isset($place['address'])) {
                        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($place['address']) . '&key=' . MAP_API);
                        $output = json_decode($geocode);
                        $lat = $output->results[0]->geometry->location->lat;
                        $long = $output->results[0]->geometry->location->lng;
                        $place['lat'] = $lat;
                        $place['lon'] = $long;
                        $place['long'] = $long;
                        $place['rating'] = $review->rating;
                        $newPlaces[] = $place;
                    }
                }
                $newPlaces = array_values($newPlaces);
                $newPlaces = json_encode($newPlaces, JSON_UNESCAPED_UNICODE);
                $review = $this->Reviews->patchEntity($review, ['place' => $newPlaces]);
                $this->Reviews->save($review);
            }
        }
    }

    public function fixHotelJsonContent($type)
    {
        switch ($type) {
            case 1:
                $this->loadModel('Hotels');
                $hotels = $this->Hotels->find();
                foreach ($hotels as $hotel) {
                    echo "Hotel: " . $hotel->id . "\r\n";
                    $term = json_decode($hotel->term, true);
                    if ($term) {
                        $term = array_values($term);
                    } else {
                        $term = [];
                    }

                    $caption = json_decode($hotel->caption, true);
                    if ($caption) {
                        $caption = array_values($caption);
                    } else {
                        $caption = [];
                    }

                    $email = json_decode($hotel->email, true);
                    if ($email) {
                        $email = array_values($email);
                    } else {
                        $email = [];
                    }
                    $payment_information = json_decode($hotel->payment_information, true);
                    if ($payment_information) {
                        $payment_information = array_values($payment_information);
                    } else {
                        $payment_information = [];
                    }
                    $hotel = $this->Hotels->patchEntity($hotel, [
                        'term' => json_encode($term, JSON_UNESCAPED_UNICODE),
                        'caption' => json_encode($caption, JSON_UNESCAPED_UNICODE),
                        'email' => json_encode($email, JSON_UNESCAPED_UNICODE),
                        'payment_information' => json_encode($payment_information, JSON_UNESCAPED_UNICODE),
                    ]);
                    $this->Hotels->save($hotel);
                }
                break;
            case 2:
                $this->loadModel('HomeStays');
                $homestays = $this->HomeStays->find();
                foreach ($homestays as $homestay) {
                    echo "HomeStay: " . $homestay->id . "\r\n";
                    $term = json_decode($homestay->term, true);
                    if ($term) {
                        $term = array_values($term);
                    } else {
                        $term = [];
                    }

                    $caption = json_decode($homestay->caption, true);
                    if ($caption) {
                        $caption = array_values($caption);
                    } else {
                        $caption = [];
                    }

                    $email = json_decode($homestay->email, true);
                    if ($email) {
                        $email = array_values($email);
                    } else {
                        $email = [];
                    }
                    $payment_information = json_decode($homestay->payment_information, true);
                    if ($payment_information) {
                        $payment_information = array_values($payment_information);
                    } else {
                        $payment_information = [];
                    }
                    $homestay = $this->HomeStays->patchEntity($homestay, [
                        'term' => json_encode($term, JSON_UNESCAPED_UNICODE),
                        'caption' => json_encode($caption, JSON_UNESCAPED_UNICODE),
                        'email' => json_encode($email, JSON_UNESCAPED_UNICODE),
                        'payment_information' => json_encode($payment_information, JSON_UNESCAPED_UNICODE),
                    ]);
                    $this->HomeStays->save($homestay);
                }
                break;
            case 3:
                $this->loadModel('LandTours');
                $landTours = $this->LandTours->find();
                foreach ($landTours as $landTour) {
                    echo "landtour: " . $landTour->id . "\r\n";
                    $term = json_decode($landTour->term, true);
                    if ($term) {
                        $term = array_values($term);
                    } else {
                        $term = [];
                    }

                    $caption = json_decode($landTour->caption, true);
                    if ($caption) {
                        $caption = array_values($caption);
                    } else {
                        $caption = [];
                    }

                    $email = json_decode($landTour->email, true);
                    if ($email) {
                        $email = array_values($email);
                    } else {
                        $email = [];
                    }
                    $payment_information = json_decode($landTour->payment_information, true);
                    if ($payment_information) {
                        $payment_information = array_values($payment_information);
                    } else {
                        $payment_information = [];
                    }
                    $landTour = $this->LandTours->patchEntity($landTour, [
                        'term' => json_encode($term, JSON_UNESCAPED_UNICODE),
                        'caption' => json_encode($caption, JSON_UNESCAPED_UNICODE),
                        'email' => json_encode($email, JSON_UNESCAPED_UNICODE),
                        'payment_information' => json_encode($payment_information, JSON_UNESCAPED_UNICODE),
                    ]);
                    $this->LandTours->save($landTour);
                }
                break;
            case 4:
                $this->loadModel('Vouchers');
                $vouchers = $this->Vouchers->find();
                foreach ($vouchers as $voucher) {
                    echo "Voucher: " . $voucher->id . "\r\n";
                    $term = json_decode($voucher->term, true);
                    if ($term) {
                        $term = array_values($term);
                    } else {
                        $term = [];
                    }

                    $caption = json_decode($voucher->caption, true);
                    if ($caption) {
                        $caption = array_values($caption);
                    } else {
                        $caption = [];
                    }
                    $payment_information = json_decode($voucher->payment_information, true);
                    if ($payment_information) {
                        $payment_information = array_values($payment_information);
                    } else {
                        $payment_information = [];
                    }
                    $voucher = $this->Vouchers->patchEntity($voucher, [
                        'term' => json_encode($term, JSON_UNESCAPED_UNICODE),
                        'caption' => json_encode($caption, JSON_UNESCAPED_UNICODE),
                        'payment_information' => json_encode($payment_information, JSON_UNESCAPED_UNICODE),
                    ]);
                    $this->Vouchers->save($voucher);
                }
                break;
        }
    }

    public function trimUsername()
    {
        $this->loadModel('Users');
        $users = $this->Users->find();
        foreach ($users as $user) {
            echo "User " . $user->id . " done \r\n";
            $newUsername = trim($user->username);
            $user = $this->Users->patchEntity($user, ['username' => $newUsername]);
            $this->Users->save($user);
        }
    }

    public function moveRoomPriceToNewTable()
    {
        $this->loadModel('RoomPrices');
        $this->loadModel('PriceRooms');

        $today = date('Y-m-d');
        $priceRooms = $this->PriceRooms->find()->where(['end_date >=' => $today]);
        foreach ($priceRooms as $priceRoom) {
            $dates = $this->_dateRange($today, $priceRoom->end_date);
            $room_price_datas = [];
            foreach ($dates as $date) {
                $room_price = $this->RoomPrices->find()->where(['room_id' => $priceRoom->room_id, 'room_day' => $date])->first();
                if (!$room_price) {
                    $room_price = $this->RoomPrices->newEntity();
                    $room_price_item = [
                        'room_id' => $priceRoom->room_id,
                        'room_day' => $date,
                        'price' => $priceRoom->price,
                        'available' => NULL,
                        'type' => WEEK_DAY
                    ];
                    $room_price = $this->RoomPrices->patchEntity($room_price, $room_price_item);
                    $this->RoomPrices->save($room_price);
                }

            }
            echo 'Success: ' . $priceRoom->room_id . "\r\n";
        }
    }

    public function editNewMailForm()
    {
        $this->loadModel('Hotels');
        $hotels = $this->Hotels->find()->all();
        foreach ($hotels as $hotel) {
            $data = [];
            $listEmail = json_decode($hotel->email);
            if ($listEmail) {
                $hotel = $this->Hotels->patchEntity($hotel, ['email' => $this->_createMailList($listEmail)]);
                $this->Hotels->save($hotel);
            }
        }
        $this->loadModel('LandTours');
        $landtours = $this->LandTours->find()->all();
        foreach ($landtours as $landtour) {
            $data = [];
            $listEmail = json_decode($landtour->email);
            if ($listEmail) {
                $landtour = $this->LandTours->patchEntity($landtour, ['email' => $this->_createMailList($listEmail)]);
                $this->LandTours->save($landtour);
            }
        }
        $this->loadModel('Vouchers');
        $vouchers = $this->Vouchers->find()->all();
        foreach ($vouchers as $voucher) {
            $data = [];
            $listEmail = json_decode($voucher->email);
            if ($listEmail) {
                $voucher = $this->Vouchers->patchEntity($voucher, ['email' => $this->_createMailList($listEmail)]);
                $this->Vouchers->save($voucher);
            }
        }
        $this->loadModel('HomeStays');
        $home_stays = $this->HomeStays->find()->all();
        foreach ($home_stays as $home_stay) {
            $data = [];
            $listEmail = json_decode($home_stay->email);
            if ($listEmail) {
                $home_stay = $this->HomeStays->patchEntity($home_stay, ['email' => $this->_createMailList($listEmail)]);
                $this->HomeStays->save($home_stay);
            }
        }
    }

    private function _createMailList($listEmail)
    {
        foreach ($listEmail as $singleMail) {
            if (!isset($singleMail->name)) {
                $data[]['name'] = $singleMail;
            } else {
                $data[] = $singleMail;
            }
        }
        $data = array_values($data);
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $data;
    }

    public function generateSaleRevenue()
    {
        $this->loadModel('Bookings');
        $this->loadModel('Hotels');
        $this->loadModel('LandTours');
        $this->loadModel('Vouchers');
        $this->loadModel('HomeStays');
        $bookings = $this->Bookings->find()->all();
        foreach ($bookings as $booking) {
            $saleRevenue = 0;
            if ($booking->booking_type == SYSTEM_BOOKING) {
                switch ($booking->type) {
                    case HOTEL:
                        if ($booking->start_date && $booking->end_date) {
                            $hotel = $this->Hotels->get($booking->item_id);
                            if ($hotel) {
                                $start_date = date('d-m-Y', strtotime($booking->start_date));
                                $end_date = date('d-m-Y', strtotime($booking->end_date . ' - 1 day'));
                                $bookingDateArray = $this->_dateRange($start_date, $end_date);
                                $booking->sale_revenue = count($bookingDateArray) * $booking->amount * $hotel->price_agency;
                                $this->Bookings->save($booking);
                            }
                        }
                        break;
                    case HOMESTAY:
                        if ($booking->start_date && $booking->end_date) {
                            $homeStay = $this->HomeStays->get($booking->item_id);
                            if ($homeStay) {
                                $numbDay = intval(date_diff($booking->start_date, $booking->end_date)->format("%d"));
                                $saleRevenue = $homeStay->price_agency * $booking->amount * ($numbDay > 0 ? $numbDay : 0);
                                $booking->sale_revenue = $saleRevenue;
                                $this->Bookings->save($booking);
                            }
                        }
                        break;
                    case VOUCHER:
                        $voucher = $this->Vouchers->get($booking->item_id);
                        if ($voucher) {
                            $saleRevenue = $voucher->trippal_price * $booking->amount;
                            $booking->sale_revenue = $saleRevenue;
                            $this->Bookings->save($booking);
                        }
                        break;
                    case LANDTOUR:
                        $landTour = $this->LandTours->get($booking->item_id);
                        if ($landTour) {
                            $saleRevenue = $landTour->trippal_price * $booking->amount;
                            $booking->sale_revenue = $saleRevenue;
                            $this->Bookings->save($booking);
                        }
                        break;
                }
            } elseif ($booking->booking_type == ANOTHER_BOOKING) {
                if ($booking->revenue != 0) {
                    $booking->sale_revenue = $booking->revenue;
                    $booking->revenue = 0;
                    $this->Bookings->save($booking);
                }
            }

        }
    }

    private function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

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

    private function calculateHotelSaleRevenue($hotel, $roomId, $today = null)
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
            if ($roomPrice->price_customer) {
                return $roomPrice->price_agency;
            } else {
                return $hotel['price_agency'];
            }
        } else {
            return $hotel['price_agency'];
        }
    }

    public function sendNoticeNewBooking()
    {
        $this->Bookings = TableRegistry::getTableLocator()->get('Bookings');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $emailSaleLists = $this->Users->find()->where(['role_id' => 2])->extract('email')->toArray();
        $firstSale = $emailSaleLists[0];
        unset($emailSaleLists[0]);
        $bookings = $this->Bookings->find()->where(['is_send_notice' => 0]);
        foreach ($bookings as $booking) {
            $bodyEmail = "Hệ thống vừa xuất hiện booking mới với mã là: " . $booking->code;
            $data_sendEmail = [
                'subject' => 'Mustgo New Booking',
                'title' => 'Mustgo New Booking',
                'body' => $bodyEmail
            ];
            if ($booking->sale_id) {
                $sale = $this->Users->get($booking->sale_id);
                $data_sendEmail['to'] = $sale->email;
                $data_sendEmail['cc'] = [];
            } else {
                $data_sendEmail['to'] = $firstSale;
                $data_sendEmail['cc'] = $emailSaleLists;
            }

            self::sendMail($data_sendEmail);
            $booking = $this->Bookings->patchEntity($booking, ['is_send_notice' => 1]);
            $this->Bookings->save($booking);
        }
    }

    private function sendMail($config = [])
    {
        $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail_noti_user', 'from' => 'test'), $config);
        if (filter_var($config['to'], FILTER_VALIDATE_EMAIL)) {
            try {
                $Email = new Email();
                $Email->setFrom('noreply@mustgo.vn', 'The Mustgo Team')
                    ->setTemplate('themetemplate', 'themelayout')
                    ->setTo($defaults['to'])
                    ->setCc($defaults['cc'])
                    ->setSubject($defaults['subject'])
                    ->setEmailFormat($defaults['sendAs'])
                    ->setTransport($defaults['transport'])
                    ->setViewVars(['title' => $config['title'], 'content' => $config['body'], 'email' => $defaults['to']]);

                if ($Email->send()) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateStatus()
    {
        $this->loadModel('Bookings');
        $bookings = $this->Bookings->find()->all();
        foreach ($bookings as $booking) {
            if ($booking->booking_type == ANOTHER_BOOKING) {
                if ($booking->status == 3) {
                    $booking->status = 4;
                    $this->Bookings->save($booking);
                }
            } elseif ($booking->booking_type == SYSTEM_BOOKING) {
                if ($booking->payment_method == AGENCY_PAY && $booking->status >= 2) {
                    $booking->status = 3;
                    $this->Bookings->save($booking);
                }
                if ($booking->user_id != $booking->sale_id) {
                    if ($booking->payment_method == CUSTOMER_PAY && $booking->status == 3) {
                        $booking->status = 4;
                        $this->Bookings->save($booking);
                    }
                    if ($booking->status == 1) {
                        $booking->status = 2;
                        $this->Bookings->save($booking);
                    }
                }
            }
        }
    }

    private function formatSQLDate($date_str, $format)
    {
        $test = \DateTime::createFromFormat($format, $date_str);
        return date_format($test, 'Y-m-d');
    }

    public function updateAmountBookingHotel()
    {
        $this->loadModel('Bookings');
        $bookings = $this->Bookings->find()->contain('BookingRooms')->all();
        foreach ($bookings as $booking) {
            if ($booking->type == HOTEL && !empty($booking->booking_rooms)) {
                $totalAmount = 0;
                foreach ($booking->booking_rooms as $booking_room) {
                    $totalAmount += $booking_room->num_room;
                }
                $booking->amount = $totalAmount;
                $this->Bookings->save($booking);
            }
        }
    }

    public function setSentNoticeBooking()
    {
        $this->loadModel('Bookings');
        $bookings = $this->Bookings->find()->all();
        foreach ($bookings as $booking) {
            $booking = $this->Bookings->patchEntity($booking, ['is_send_notice' => 1]);
            $this->Bookings->save($booking);
        }
    }

    public function sendPostfixEmail($from)
    {
        try {
            $Email = new Email();
            $Email->setFrom($from, 'The Mustgo Team')
                ->setTemplate('themetemplate', 'themelayout')
                ->setTo('cuongbv90@gmail.com')
                ->setSubject('This is Command Line send Email')
                ->setEmailFormat('html')
                ->setTransport('gmailv2');

            if ($Email->send()) {
                echo 'Thành công';
            } else {
                echo 'Có lỗi xảy ra';
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function updateCompleteDate()
    {
//        $this->loadModel("Bookings");
//        $bookings = $this->Bookings->find()->where([
//            'OR' => [
//                ['status' => 4],
//                [
//                    'sale_id = user_id',
//                    'status' => 3,
//                ],
//                [
//                    'payment_method' => AGENCY_PAY,
//                    'status >=' => 3
//                ],
//                [
//                    'booking_type' => ANOTHER_BOOKING
//                ]
//            ]
//        ]);
////        dd($bookings->toArray());
//        foreach ($bookings as $booking) {
//            $booking = $this->Bookings->patchEntity($booking, ['complete_date' => $booking->modified, 'modified' => false]);
//            $this->Bookings->save($booking);
//        }

        $conn = ConnectionManager::get("default");
        $conn->execute("UPDATE bookings bk
                    SET bk.complete_date = (SELECT bk.created FROM bookings bk2 WHERE bk.id = bk2.id)
                    WHERE bk.id >= 1 AND (bk.status = 4 OR (bk.sale_id = bk.user_id AND bk.status = 3) OR (bk.payment_method = 1 AND bk.status >= 3) OR bk.booking_type = 2)");
    }

    public function updateVinHotelName() {
        $testUrl = 'https://premium-api.product.cloudhms.io';
        $this->loadModel('Hotels');
        $listHotelVinpearl = [];
        for ($i = 0; $i <= 5; $i++) {
            $tempListHotelVinpearl = $this->Util->getListHotel($testUrl, $i, 10);
            if ($tempListHotelVinpearl['isSuccess']) {
                foreach ($tempListHotelVinpearl['data']['items'] as $item) {
                    $listHotelVinpearl[] = $item;
                }
            }
        }
        foreach ($listHotelVinpearl as $singleHotel) {
            $vinHotel = $this->Hotels->find()->where(['vinhms_code' => $singleHotel['id']])->first();
            if ($vinHotel) {
                $vinHotel = $this->Hotels->patchEntity($vinHotel, ['name' => $singleHotel['name']]);
                $this->Hotels->save($vinHotel);
            }
        }
    }

    public function createRoomFireBase()
    {
        $this->loadModel('Users');
        $firestore = new FirestoreClient([
            'projectId' => 'test-send-message-24796',
        ]);
        $timeNow = time();
        $users = $this->Users->find()->where(['role_id' => 3]);
        foreach ($users as $user) {
            if ($user->parent_id != 0){
                $roomId = $user->id . '-' . $user->parent_id;
                if ($firestore->collection('chatroom')->document($roomId)->snapshot()->exists()) {
                } else {
                    $firestore->collection('chatroom')->document($roomId)->create([
                        'createdAt' => $timeNow,
                        'sale_id' => $user->parent_id,
                        'latestMessage' => [
                            'createdAt' => $timeNow,
                            'createdBy' => $user->id,
                            'text' => ''
                        ],
                        'is_read' => 0,
                        'is_read_number' => 1,
                        'updatedAt' => $timeNow
                    ]);
                    $document = $firestore->collection('chatroom')->document($roomId);
                    $document->collection('messages')->document($timeNow)->create([
                        'createdAt' => $timeNow,
                        'createdBy' => $user->id,
                        'id' => $timeNow,
                        'text' => $timeNow,
                        'is_read' => 0,
                        'is_read_number' => 1,
                        'type' => 1
                    ]);
                }
            }
        }
    }
}
