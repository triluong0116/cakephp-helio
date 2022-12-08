<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * Main component
 */
Configure::write('CakePdf', [
    'engine' => 'CakePdf.DomPdf',
    'orientation' => 'portrait',
    'download' => true
]);

class EmailComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    public $components = ['Auth', 'Util'];

    /*
     * Email Configuration
     * @params : array $config [Cake Email Params]
     *        - $config[to]   - Email of receiver
     *        - $config[from] - Email of sender
     *        - $cofig[message] - Message of email
     *          - $config[subject] - subject of email
     * @return : boolean
     */

    public function createAttachmentFile($booking, $surcharges, $booking_rooms, $bankInvoice, $bankAccounts, $template)
    {
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template($template . '_pdf', 'default');
        $CakePdf->viewVars(array(
            'booking' => $booking,
            'surcharges' => $surcharges,
            'booking_rooms' => $booking_rooms,
            'bankAccount' => $bankAccounts,
            'bankInvoice' => $bankInvoice
        ));
        $destination = 'files' . DS . 'attachments' . DS . $booking['code'] . '_' . $template . '.pdf';
        $CakePdf->write($destination);
        return $destination;
    }

    public function createAttachmentVinFile($booking, $template)
    {
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template($template . '_pdf', 'default');
        $CakePdf->viewVars(array(
            'booking' => $booking,
        ));
        $destination = 'files' . DS . 'attachments' . DS . $booking->code . '_' . $template . '.pdf';
        $CakePdf->write($destination);
        return $destination;
    }

    public function sendEmail($config = array(), $from, $from_secret, $type)
    {
        $config['to'] = trim($config['to'], ' ');
        $from = trim($from, ' ');
        $res = ['success' => false, 'message' => ''];
        if (filter_var($config['to'], FILTER_VALIDATE_EMAIL) && filter_var($from, FILTER_VALIDATE_EMAIL)) {
            switch ($type) {
                case E_PAY_AGENCY:
                    $template = "pay_agency";
                    $template2 = "book_agency";
                    break;
                case E_BOOK_AGENCY:
                    $template = "book_agency";
                    break;
            }
            Email::dropTransport('gmail');
            Email::setConfigTransport('gmail', [
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => $from,
                'password' => $from_secret,
                'className' => 'Smtp',
                'log' => true,
                'context' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]
            ]);
            $this->BookingSurcharges = TableRegistry::get('BookingSurcharges');
            $this->BookingLandtours = TableRegistry::getTableLocator()->get('BookingLandtours');
            $this->Configs = TableRegistry::get('Configs');
            $bankAccounts = $this->Configs->find()->where(['type' => "bank-account"])->first();
            $bankInvoice = $this->Configs->find()->where(['type' => "bank-invoice"])->first();
            if ($bankInvoice) {
                $bankInvoice = json_decode($bankInvoice->value, true);
            } else {
                $bankInvoice = [
                    "bank_name" => "",
                    "account_name" => "",
                    "account_number" => "",
                    "bank_branch" => "",
                ];
            }
            if ($bankAccounts) {
                $bankAccounts = json_decode($bankAccounts->value, true);
            } else {
                $bankAccounts = [];
            }
            if ($config['data']['type'] == HOTEL) {
                $surcharges = $this->BookingSurcharges->find()->where(['booking_id =' => $config['data']['id']])->toArray();
                $this->BookingRooms = TableRegistry::get('BookingRooms');
                $booking_rooms = $this->BookingRooms->find()->where(['booking_id =' => $config['data']['id']])->contain('Rooms')->toArray();
                foreach ($booking_rooms as $key => $booking_room) {
                    $dates = $this->Util->_dateRange($booking_room->start_date, date('Y-m-d', strtotime($booking_room->end_date . "-1 days")));
                    $totalPrice = 0;
                    $totalRevenue = 0;
                    $weekends = json_decode($config['data']['hotels']['weekend'], true);
                    if (!$weekends) {
                        $weekends = [];
                    }
                    $holidayDates = json_decode($config['data']['hotels']['holidays'], true);
                    $holidays = [];
                    if ($holidayDates) {
                        foreach ($holidayDates as $holidayDate) {
                            $holidayDate = explode(' - ', $holidayDate);
                            $holidayStartDate = $this->Util->formatSQLDate($holidayDate[0], 'd/m/Y');
                            $holidayEndDate = $this->Util->formatSQLDate($holidayDate[1], 'd/m/Y');
                            $holidays = array_merge($this->Util->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
                        }
                    }
                    $numb_weekend = 0;
                    $numb_holiday = 0;
                    foreach ($dates as $date) {
                        $resPrice = $this->Util->calculateHotelPrice($config['data']['hotels'], $booking_room['room_id'], $date);
                        if ($resPrice['status']) {
                            $totalPrice += $resPrice['price'] * $booking_room['num_room'];
                            $totalRevenue += $resPrice['revenue'];
                        }
                        if (in_array(date('l', strtotime($date)), $weekends)) {
                            $numb_weekend++;
                        }
                        if (in_array($date, $holidays)) {
                            $numb_holiday++;
                        }
                    }
                    $booking_rooms[$key]['price'] = $totalPrice;
                    $booking_rooms[$key]['revenue'] = $totalRevenue;
                    $booking_rooms[$key]['numb_holiday'] = $numb_holiday;
                    $booking_rooms[$key]['numb_weekend'] = $numb_weekend;
                }
            } else {
                $booking_rooms = null;
                $surcharges = null;
            }
            if ($config['data']['type'] == LANDTOUR) {
                $surcharges = $this->BookingLandtours->find()->where(['booking_id =' => $config['data']['id']])->toArray();
            }
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($defaults['to']))
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars([
                        'title' => $config['title'],
                        'content' => $config['body'],
                        'email' => trim($defaults['to']),
                        'booking' => $config['data'],
                        'surcharges' => $surcharges,
                        'booking_rooms' => $booking_rooms,
                        'signature' => $this->Auth->user('signature'),
                        'bankAccounts' => $bankAccounts,
                        'bankInvoice' => $bankInvoice
                    ]);
                $file = $this->createAttachmentFile($config['data'], $surcharges, $booking_rooms, $bankInvoice, $bankAccounts, $template);
                $file2 = $this->createAttachmentFile($config['data'], $surcharges, $booking_rooms, $bankInvoice, $bankAccounts, $template2);
                if (file_exists($file) && file_exists($file2)) {
                    $Email->attachments([$file, $file2]);
                }
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
        } else {
            $res['message'] = 'Địa chỉ "' . $config['to'] . '" email không đúng định dạng';
        }
        return $res;
    }

    public function sendEmailV2($config = array(), $from, $from_secret, $type)
    {
        $config['to'] = trim($config['to'], ' ');
        $from = trim($from, ' ');
        $res = ['success' => false, 'message' => ''];
        if (filter_var($config['to'], FILTER_VALIDATE_EMAIL) && filter_var($from, FILTER_VALIDATE_EMAIL)) {
            switch ($type) {
                case E_PAY_AGENCY:
                    $template = "pay_agency";
                    $template2 = "book_agency";
                    break;
                case E_BOOK_AGENCY:
                    $template = "book_agency";
                    break;
            }
            Email::setConfigTransport('gmailpostfix', [
                'className' => 'Cake\Mailer\Transport\MailTransport',
                /*
                * The following keys are used in SMTP transports:
                */
                'host' => 'localhost',
                'port' => 25,
                'timeout' => 30,
                'username' => null,
                'password' => null,
                'client' => null,
                'tls' => null,
                'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
                'additionalParameters' => '-f' . $from
            ]);
            $this->BookingSurcharges = TableRegistry::get('BookingSurcharges');
            $this->BookingLandtours = TableRegistry::getTableLocator()->get('BookingLandtours');
            $this->Configs = TableRegistry::get('Configs');
            $bankAccounts = $this->Configs->find()->where(['type' => "bank-account"])->first();
            $bankInvoice = $this->Configs->find()->where(['type' => "bank-invoice"])->first();
            if ($bankInvoice) {
                $bankInvoice = json_decode($bankInvoice->value, true);
            } else {
                $bankInvoice = [
                    "bank_name" => "",
                    "account_name" => "",
                    "account_number" => "",
                    "bank_branch" => "",
                ];
            }
            if ($bankAccounts) {
                $bankAccounts = json_decode($bankAccounts->value, true);
            } else {
                $bankAccounts = [];
            }
            if ($config['data']['type'] == HOTEL) {
                $surcharges = $this->BookingSurcharges->find()->where(['booking_id =' => $config['data']['id']])->toArray();
                $this->BookingRooms = TableRegistry::get('BookingRooms');
                $booking_rooms = $this->BookingRooms->find()->where(['booking_id =' => $config['data']['id']])->contain('Rooms')->toArray();
                foreach ($booking_rooms as $key => $booking_room) {
                    $dates = $this->Util->_dateRange($booking_room->start_date, date('Y-m-d', strtotime($booking_room->end_date . "-1 days")));
                    $totalPrice = 0;
                    $totalRevenue = 0;
                    $weekends = json_decode($config['data']['hotels']['weekend'], true);
                    if (!$weekends) {
                        $weekends = [];
                    }
                    $holidayDates = json_decode($config['data']['hotels']['holidays'], true);
                    $holidays = [];
                    if ($holidayDates) {
                        foreach ($holidayDates as $holidayDate) {
                            $holidayDate = explode(' - ', $holidayDate);
                            $holidayStartDate = $this->Util->formatSQLDate($holidayDate[0], 'd/m/Y');
                            $holidayEndDate = $this->Util->formatSQLDate($holidayDate[1], 'd/m/Y');
                            $holidays = array_merge($this->Util->_dateRange($holidayStartDate, $holidayEndDate), $holidays);
                        }
                    }
                    $numb_weekend = 0;
                    $numb_holiday = 0;
                    foreach ($dates as $date) {
                        $resPrice = $this->Util->calculateHotelPrice($config['data']['hotels'], $booking_room['room_id'], $date);
                        if ($resPrice['status']) {
                            $totalPrice += $resPrice['price'] * $booking_room['num_room'];
                            $totalRevenue += $resPrice['revenue'];
                        }
                        if (in_array(date('l', strtotime($date)), $weekends)) {
                            $numb_weekend++;
                        }
                        if (in_array($date, $holidays)) {
                            $numb_holiday++;
                        }
                    }
                    $booking_rooms[$key]['price'] = $totalPrice;
                    $booking_rooms[$key]['revenue'] = $totalRevenue;
                    $booking_rooms[$key]['numb_holiday'] = $numb_holiday;
                    $booking_rooms[$key]['numb_weekend'] = $numb_weekend;
                }
            } else {
                $booking_rooms = null;
                $surcharges = null;
            }
            if ($config['data']['type'] == LANDTOUR) {
                $surcharges = $this->BookingLandtours->find()->where(['booking_id =' => $config['data']['id']])->toArray();
            }
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmailpostfix', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($defaults['to']))
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars([
                        'title' => $config['title'],
                        'content' => $config['body'],
                        'email' => trim($defaults['to']),
                        'booking' => $config['data'],
                        'surcharges' => $surcharges,
                        'booking_rooms' => $booking_rooms,
                        'signature' => $this->Auth->user('signature'),
                        'bankAccounts' => $bankAccounts,
                        'bankInvoice' => $bankInvoice
                    ]);
                $file = $this->createAttachmentFile($config['data'], $surcharges, $booking_rooms, $bankInvoice, $bankAccounts, $template);
                $file2 = $this->createAttachmentFile($config['data'], $surcharges, $booking_rooms, $bankInvoice, $bankAccounts, $template2);
                if (file_exists($file) && file_exists($file2)) {
                    $Email->attachments([$file, $file2]);
                }
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
            Email::dropTransport('gmailpostfix');
        } else {
            $res['message'] = 'Địa chỉ "' . $config['to'] . '" email không đúng định dạng';
        }
        return $res;
    }

    public function sendHotelEmail($config = array(), $from, $from_secret, $type)
    {
        $res = ['success' => false, 'message' => ''];
        $hasMain = false;
        $listCCMail = [];
        $mainMail = [];
        $failMail = true;
        if ($config['data']->payment && $config['data']->payment->pay_object == PAY_PARTNER) {
            $partnerInfor = json_decode($config['data']->payment->partner_information);
            $sendMainMail = $partnerInfor->email;
            $listCCMail = [];
            if (!filter_var(trim($sendMainMail), FILTER_VALIDATE_EMAIL)) {
                $failMail = false;
                $listFailMail[] = $sendMainMail;
            }
        } else {
            foreach ($config['to'] as $key => $singleMail) {
                if (isset($singleMail['is_main'])) {
                    $hasMain = true;
                    $mainMail = $singleMail;
                    break;
                }
            }
            $listFailMail = [];
            foreach ($config['to'] as $k => $mail) {
                if (!filter_var(trim($mail['name']), FILTER_VALIDATE_EMAIL)) {
                    $failMail = false;
                    $listFailMail[$k] = $mail['name'];
                }
            }
            if ($hasMain) {
                unset($config['to'][$key]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail['name']);
                }
            } else {
                $mainMail = $config['to'][0];
                unset($config['to'][0]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail['name']);
                }
            }
            $sendMainMail = $mainMail['name'];
        }
        if ($failMail) {
            $template = "book_hotel";
            Email::dropTransport('gmail');
            Email::setConfigTransport('gmail', [
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => $from,
                'password' => $from_secret,
                'className' => 'Smtp',
                'log' => true,
                'context' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]
            ]);
            if ($config['data']['type'] == HOTEL) {
                $this->BookingRooms = TableRegistry::get('BookingRooms');
                $this->BookingSurcharges = TableRegistry::get('BookingSurcharges');
                $booking_rooms = $this->BookingRooms->find()->where(['booking_id =' => $config['data']['id']])->contain('Rooms')->toArray();
                $surcharges = $this->BookingSurcharges->find()->where(['booking_id =' => $config['data']['id']])->toArray();
            } else {
                $this->BookingLandtours = TableRegistry::getTableLocator()->get('BookingLandtours');
                $booking_rooms = null;
                $surcharges = $this->BookingLandtours->find()->where(['booking_id =' => $config['data']['id']])->toArray();
            }
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($sendMainMail))
                    ->cc($listCCMail)
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars(['title' => $config['title'], 'content' => $config['body'], 'booking_rooms' => $booking_rooms, 'email' => trim($sendMainMail), 'booking' => $config['data'], 'signature' => $this->Auth->user('signature'), 'surcharges' => $surcharges]);
                $config['data']['signature'] = $this->Auth->user('signature');
                $file = $this->createAttachmentFile($config['data'], $surcharges, $booking_rooms, null, null, $template);
                $listAttachments = [];
                $listAttachments[] = $file;
                if($config['data']['payment']['images'] && !empty($config['data']['payment']['images'])){
                    $images = json_decode($config['data']['payment']['images'], true);
                    foreach ($images as $k => $image){
                        if(file_exists($image)){
                            $listAttachments[] = $image;
                        }
                    }
                }
                if (file_exists($file)) {
                    $Email->attachments($listAttachments);
                }
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
        } else {
            $str = '';
            foreach ($listFailMail as $failMail) {
                if ($failMail != end($listFailMail)) {
                    $str .= $failMail . ', ';
                } else {
                    $str .= $failMail;
                }
            }
            $res['message'] = 'Địa chỉ email "' . $str . '" không đúng định dạng';
        }
        return $res;
    }

    public function sendHotelEmailV2($config = array(), $from, $from_secret, $type)
    {
        $res = ['success' => false, 'message' => ''];
        $hasMain = false;
        $listCCMail = [];
        $mainMail = [];
        $failMail = true;
        if ($config['data']->payment && $config['data']->payment->pay_object == PAY_PARTNER) {
            $partnerInfor = json_decode($config['data']->payment->partner_information);
            $sendMainMail = $partnerInfor->email;
            $listCCMail = [];
            if (!filter_var(trim($sendMainMail), FILTER_VALIDATE_EMAIL)) {
                $failMail = false;
                $listFailMail[] = $sendMainMail;
            }
        } else {
            foreach ($config['to'] as $key => $singleMail) {
                if (isset($singleMail['is_main'])) {
                    $hasMain = true;
                    $mainMail = $singleMail;
                    break;
                }
            }
            $listFailMail = [];
            foreach ($config['to'] as $k => $mail) {
                if (!filter_var(trim($mail['name']), FILTER_VALIDATE_EMAIL)) {
                    $failMail = false;
                    $listFailMail[$k] = $mail['name'];
                }
            }
            if ($hasMain) {
                unset($config['to'][$key]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail['name']);
                }
            } else {
                $mainMail = $config['to'][0];
                unset($config['to'][0]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail['name']);
                }
            }
            $sendMainMail = $mainMail['name'];
        }
        if ($failMail) {
            $template = "book_hotel";

            Email::setConfigTransport('gmailpostfix', [
                'className' => 'Cake\Mailer\Transport\MailTransport',
                /*
                * The following keys are used in SMTP transports:
                */
                'host' => 'localhost',
                'port' => 25,
                'timeout' => 30,
                'username' => null,
                'password' => null,
                'client' => null,
                'tls' => null,
                'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
                'additionalParameters' => '-f' . $from
            ]);
            if ($config['data']['type'] == HOTEL) {
                $this->BookingRooms = TableRegistry::get('BookingRooms');
                $this->BookingSurcharges = TableRegistry::get('BookingSurcharges');
                $booking_rooms = $this->BookingRooms->find()->where(['booking_id =' => $config['data']['id']])->contain('Rooms')->toArray();
                $surcharges = $this->BookingSurcharges->find()->where(['booking_id =' => $config['data']['id']])->toArray();
            } else {
                $this->BookingLandtours = TableRegistry::getTableLocator()->get('BookingLandtours');
                $booking_rooms = null;
                $surcharges = $this->BookingLandtours->find()->where(['booking_id =' => $config['data']['id']])->toArray();
            }
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmailpostfix', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($sendMainMail))
                    ->cc($listCCMail)
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars(['title' => $config['title'], 'content' => $config['body'], 'booking_rooms' => $booking_rooms, 'email' => trim($sendMainMail), 'booking' => $config['data'], 'signature' => $this->Auth->user('signature'), 'surcharges' => $surcharges]);
                $file = $this->createAttachmentFile($config['data'], $surcharges, $booking_rooms, null, null, $template);
                $listAttachments = [];
                $listAttachments[] = $file;
                if ($config['data']['type'] == LANDTOUR){
                    if($config['data']['payment']['images'] && !empty($config['data']['payment']['images'])){
                        $images = json_decode($config['data']['payment']['images'], true);
                        foreach ($images as $k => $image){
                            if(file_exists($image)){
                                $listAttachments[] = $image;
                            }
                        }
                    }
                }
                if (file_exists($file)) {
                    $Email->attachments($listAttachments);
                }
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
            Email::dropTransport('gmailpostfix');
        } else {
            $str = '';
            foreach ($listFailMail as $failMail) {
                if ($failMail != end($listFailMail)) {
                    $str .= $failMail . ', ';
                } else {
                    $str .= $failMail;
                }
            }
            $res['message'] = 'Địa chỉ email "' . $str . '" không đúng định dạng';
        }
        return $res;
    }

    public function sendPaymentEmailToObject($config = array(), $from, $from_secret, $type)
    {
        $res = ['success' => false, 'message' => ''];
        $hasMain = false;
        $listCCMail = [];
        $mainMail = [];
        $failMail = true;
        if ($config['data']->payment && $config['data']->payment->pay_object == PAY_PARTNER) {
            $partnerInfor = json_decode($config['data']->payment->partner_information);
            $sendMainMail = $partnerInfor->email;
            $listCCMail = [];
            if (!filter_var(trim($sendMainMail), FILTER_VALIDATE_EMAIL)) {
                $failMail = false;
                $listFailMail[] = $sendMainMail;
            }
        } else {
            foreach ($config['to'] as $key => $singleMail) {
                if (isset($singleMail->is_main)) {
                    $hasMain = true;
                    $mainMail = $singleMail;
                    break;
                }
            }
            $listFailMail = [];
            foreach ($config['to'] as $k => $mail) {
                if (!filter_var(trim($mail->name), FILTER_VALIDATE_EMAIL)) {
                    $failMail = false;
                    $listFailMail[$k] = $mail->name;
                }
            }
            if ($hasMain) {
                unset($config['to'][$key]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail->name);
                }
            } else {
                $mainMail = $config['to'][0];
                unset($config['to'][0]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail->name);
                }
            }
            $sendMainMail = $mainMail->name;
        }
        if ($failMail) {
            $template = "pay_object";
            Email::dropTransport('gmail');
            Email::setConfigTransport('gmail', [
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => $from,
                'password' => $from_secret,
                'className' => 'Smtp',
                'log' => true,
                'context' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]
            ]);
            $this->BookingSurcharges = TableRegistry::get('BookingSurcharges');
            $this->BookingLandtours = TableRegistry::getTableLocator()->get('BookingLandtours');
            if ($config['data']['type'] == HOTEL) {
                $this->BookingRooms = TableRegistry::get('BookingRooms');

                $booking_rooms = $this->BookingRooms->find()->where(['booking_id =' => $config['data']['id']])->contain('Rooms')->toArray();
            } else {
                $booking_rooms = null;
            }
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail', 'from' => 'test'), $config);
            try {
                $paymentPhotos = json_decode($config['data']->payment->payment_photo);
                $listPhoto = [];
                foreach ($paymentPhotos as $k => $photo) {
                    $listPhoto[] = $photo;
                }
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($sendMainMail))
                    ->cc($listCCMail)
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars(['title' => $config['title'], 'content' => $config['body'], 'booking_rooms' => $booking_rooms, 'email' => trim($sendMainMail), 'booking' => $config['data'], 'signature' => $this->Auth->user('signature')]);
                $Email->attachments($listPhoto);
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
        } else {
            $str = '';
            foreach ($listFailMail as $failMail) {
                if ($failMail != end($listFailMail)) {
                    $str .= $failMail . ', ';
                } else {
                    $str .= $failMail;
                }
            }
            $res['message'] = 'Địa chỉ email "' . $str . '" không đúng định dạng';
        }
        return $res;
    }

    public function sendPaymentEmailToObjectV2($config = array(), $from, $from_secret, $type)
    {
        $res = ['success' => false, 'message' => ''];
        $hasMain = false;
        $listCCMail = [];
        $mainMail = [];
        $failMail = true;
        if ($config['data']->payment && $config['data']->payment->pay_object == PAY_PARTNER) {
            $partnerInfor = json_decode($config['data']->payment->partner_information);
            $sendMainMail = $partnerInfor->email;
            $listCCMail = [];
            if (!filter_var(trim($sendMainMail), FILTER_VALIDATE_EMAIL)) {
                $failMail = false;
                $listFailMail[] = $sendMainMail;
            }
        } else {
            foreach ($config['to'] as $key => $singleMail) {
                if (isset($singleMail->is_main)) {
                    $hasMain = true;
                    $mainMail = $singleMail;
                    break;
                }
            }
            $listFailMail = [];
            foreach ($config['to'] as $k => $mail) {
                if (!filter_var(trim($mail->name), FILTER_VALIDATE_EMAIL)) {
                    $failMail = false;
                    $listFailMail[$k] = $mail->name;
                }
            }
            if ($hasMain) {
                unset($config['to'][$key]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail->name);
                }
            } else {
                $mainMail = $config['to'][0];
                unset($config['to'][0]);
                foreach ($config['to'] as $singleCCMail) {
                    $listCCMail[] = trim($singleCCMail->name);
                }
            }
            $sendMainMail = $mainMail->name;
        }
        if ($failMail) {
            $template = "pay_object";

            Email::setConfigTransport('gmailpostfix', [
                'className' => 'Cake\Mailer\Transport\MailTransport',
                /*
                * The following keys are used in SMTP transports:
                */
                'host' => 'localhost',
                'port' => 25,
                'timeout' => 30,
                'username' => null,
                'password' => null,
                'client' => null,
                'tls' => null,
                'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
                'additionalParameters' => '-f' . $from
            ]);
            $this->BookingSurcharges = TableRegistry::get('BookingSurcharges');
            $this->BookingLandtours = TableRegistry::getTableLocator()->get('BookingLandtours');
            if ($config['data']['type'] == HOTEL) {
                $this->BookingRooms = TableRegistry::get('BookingRooms');

                $booking_rooms = $this->BookingRooms->find()->where(['booking_id =' => $config['data']['id']])->contain('Rooms')->toArray();
            } else {
                $booking_rooms = null;
            }
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmailpostfix', 'from' => 'test'), $config);
            try {
                $paymentPhotos = json_decode($config['data']->payment->payment_photo);
                $listPhoto = [];
                foreach ($paymentPhotos as $k => $photo) {
                    $listPhoto[] = $photo;
                }
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->setTemplate($template, 'themelayout')
                    ->setTo(trim($sendMainMail))
                    ->setCc($listCCMail)
                    ->setSubject($defaults['subject'])
                    ->setEmailFormat($defaults['sendAs'])
                    ->setTransport($defaults['transport'])
                    ->setViewVars(['title' => $config['title'], 'content' => $config['body'], 'booking_rooms' => $booking_rooms, 'email' => trim($sendMainMail), 'booking' => $config['data'], 'signature' => $this->Auth->user('signature')]);
                $Email->attachments($photo);
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
            Email::dropTransport('gmailpostfix');
        } else {
            $str = '';
            foreach ($listFailMail as $failMail) {
                if ($failMail != end($listFailMail)) {
                    $str .= $failMail . ', ';
                } else {
                    $str .= $failMail;
                }
            }
            $res['message'] = 'Địa chỉ email "' . $str . '" không đúng định dạng';
        }
        return $res;
    }

    public function sendVinCodeEmail($config = array(), $from, $from_secret) {
        $config['to'] = preg_replace('/\s+/', '', $config['to']);
        $from = trim($from, ' ');
        $res = ['success' => false, 'message' => ''];
        if (filter_var($config['to'], FILTER_VALIDATE_EMAIL) && filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $template = "vinbooking_send_code";
            $file = $this->createAttachmentVinFile($config['data'], $template);
            Email::dropTransport('gmail');
            Email::setConfigTransport('gmail', [
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => $from,
                'password' => $from_secret,
                'className' => 'Smtp',
                'log' => true,
                'context' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]
            ]);
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($defaults['to']))
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars([
                        'title' => $config['title'],
                        'content' => $config['body'],
                        'email' => trim($defaults['to']),
                        'booking' => $config['data'],
                        'signature' => $this->Auth->user('signature'),
                    ]);
                if (file_exists($file)) {
                    $Email->attachments($file);
                }
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
        } else {
            $res['message'] = 'Địa chỉ "' . $config['to'] . '" email không đúng định dạng';
        }
        return $res;
    }

    public function sendVinRequestPayment($config = array(), $from, $from_secret) {
        $config['to'] = trim(preg_replace('/\s+/', '', $config['to']), ' ');
        $from = trim($from, ' ');
        $res = ['success' => false, 'message' => ''];
        if (filter_var($config['to'], FILTER_VALIDATE_EMAIL) && filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $template = "vinbooking_send_request_payment";
//            Email::dropTransport('gmail');
//            Email::setConfigTransport('gmail', [
//                'host' => 'ssl://smtp.gmail.com',
//                'port' => 465,
//                'username' => $from,
//                'password' => $from_secret,
//                'className' => 'Smtp',
//                'log' => true,
//                'context' => [
//                    'ssl' => [
//                        'verify_peer' => false,
//                        'verify_peer_name' => false,
//                        'allow_self_signed' => true
//                    ]
//                ]
//            ]);
            Email::setConfigTransport('gmailpostfix', [
                'className' => 'Cake\Mailer\Transport\MailTransport',
                /*
                * The following keys are used in SMTP transports:
                */
                'host' => 'localhost',
                'port' => 25,
                'timeout' => 30,
                'username' => null,
                'password' => null,
                'client' => null,
                'tls' => null,
                'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
                'additionalParameters' => '-f' . $from
            ]);

            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmailpostfix', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($defaults['to']))
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars([
                        'title' => $config['title'],
                        'content' => $config['body'],
                        'email' => trim($defaults['to']),
                        'booking' => $config['data'],
                        'signature' => $this->Auth->user('signature'),
                    ]);
                $file = $this->createAttachmentVinFile($config['data'], $template);
                if (file_exists($file)) {
                    $Email->attachments($file);
                }
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
            Email::dropTransport('gmailpostfix');
        } else {
            $res['message'] = 'Địa chỉ "' . $config['to'] . '" email không đúng định dạng';
        }
        return $res;
    }

    public function sendBookingToVin($config = array(), $from, $from_secret) {
        $hasMain = false;
        $listCCMail = [];
        $mainMail = [];
        $failMail = true;
        foreach ($config['to'] as $key => $singleMail) {
            if (isset($singleMail['is_main'])) {
                $hasMain = true;
                $mainMail = $singleMail;
                break;
            }
        }
        $listFailMail = [];
        foreach ($config['to'] as $k => $mail) {
            if (!filter_var(trim($mail['name']), FILTER_VALIDATE_EMAIL)) {
                $failMail = false;
                $listFailMail[$k] = $mail['name'];
            }
        }
        if ($hasMain) {
            unset($config['to'][$key]);
            foreach ($config['to'] as $singleCCMail) {
                $listCCMail[] = trim($singleCCMail['name']);
            }
        } else {
            $mainMail = $config['to'][0];
            unset($config['to'][0]);
            foreach ($config['to'] as $singleCCMail) {
                $listCCMail[] = trim($singleCCMail['name']);
            }
        }
        $sendMainMail = $mainMail['name'];

        $from = trim($from, ' ');
        $res = ['success' => false, 'message' => ''];
        if ($failMail) {
            $template = "vinbooking_send_hotel";
            $file = $this->createAttachmentVinFile($config['data'], $template);
            Email::dropTransport('gmail');
            Email::setConfigTransport('gmail', [
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => $from,
                'password' => $from_secret,
                'className' => 'Smtp',
                'log' => true,
                'context' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]
            ]);
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($sendMainMail))
                    ->cc($listCCMail)
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars([
                        'title' => $config['title'],
                        'content' => $config['body'],
                        'email' => trim($sendMainMail),
                        'booking' => $config['data'],
                        'signature' => $this->Auth->user('signature'),
                    ]);
                if (file_exists($file)) {
                    $Email->attachments($file);
                }
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
        } else {
            $str = '';
            foreach ($listFailMail as $failMail) {
                if ($failMail != end($listFailMail)) {
                    $str .= $failMail . ', ';
                } else {
                    $str .= $failMail;
                }
            }
            $res['message'] = 'Địa chỉ email "' . $str . '" không đúng định dạng';
        }
        return $res;
    }

    public function sendPaymentToVin($config = array(), $from, $from_secret) {
        $hasMain = false;
        $listCCMail = [];
        $mainMail = [];
        $failMail = true;
        foreach ($config['to'] as $key => $singleMail) {
            if (isset($singleMail['is_main'])) {
                $hasMain = true;
                $mainMail = $singleMail;
                break;
            }
        }
        $listFailMail = [];
        foreach ($config['to'] as $k => $mail) {
            if (!filter_var(trim($mail['name']), FILTER_VALIDATE_EMAIL)) {
                $failMail = false;
                $listFailMail[$k] = $mail['name'];
            }
        }
        if ($hasMain) {
            unset($config['to'][$key]);
            foreach ($config['to'] as $singleCCMail) {
                $listCCMail[] = trim($singleCCMail['name']);
            }
        } else {
            $mainMail = $config['to'][0];
            unset($config['to'][0]);
            foreach ($config['to'] as $singleCCMail) {
                $listCCMail[] = trim($singleCCMail['name']);
            }
        }
        $sendMainMail = $mainMail['name'];

        $from = trim($from, ' ');
        $res = ['success' => false, 'message' => ''];
        if ($failMail) {
            $template = "vinbooking_send_payment_hotel";
            Email::dropTransport('gmail');
            Email::setConfigTransport('gmail', [
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => $from,
                'password' => $from_secret,
                'className' => 'Smtp',
                'log' => true,
                'context' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]
            ]);
            $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail', 'from' => 'test'), $config);
            try {
                $Email = new Email();
                $Email->setFrom($from, 'The Mustgo Team')
                    ->template($template, 'themelayout')
                    ->to(trim($sendMainMail))
                    ->cc($listCCMail)
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars([
                        'title' => $config['title'],
                        'content' => $config['body'],
                        'email' => trim($sendMainMail),
                        'booking' => $config['data'],
                        'signature' => $this->Auth->user('signature'),
                    ]);
                if ($Email->send()) {
                    $res['success'] = true;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại sau';
                }
            } catch (\Exception $e) {
                $res['message'] = $e->getMessage();
            }
        } else {
            $str = '';
            foreach ($listFailMail as $failMail) {
                if ($failMail != end($listFailMail)) {
                    $str .= $failMail . ', ';
                } else {
                    $str .= $failMail;
                }
            }
            $res['message'] = 'Địa chỉ email "' . $str . '" không đúng định dạng';
        }
        return $res;
    }

    public function sendEmailForgotPassword($config = array())
    {
        $defaults = array_merge(array('sendAs' => 'html', 'transport' => 'gmail_noti_user', 'from' => 'test'), $config);
        $config['to'] = trim($config['to'], ' ');
        if (filter_var($config['to'], FILTER_VALIDATE_EMAIL)) {
            try {
                $Email = new Email();
                $Email->setFrom('noreply@mustgo.vn', 'The Mustgo Team')
                    ->template('themetemplate', 'themelayout')
                    ->to($defaults['to'])
                    ->subject($defaults['subject'])
                    ->emailFormat($defaults['sendAs'])
                    ->transport($defaults['transport'])
                    ->viewVars(['title' => $config['title'], 'content' => $config['body'], 'email' => $defaults['to']]);

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

}
