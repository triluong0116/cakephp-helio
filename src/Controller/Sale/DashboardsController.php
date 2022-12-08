<?php

namespace App\Controller\Sale;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\VinhmsbookingsTable $Vinhmsbookings
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
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Users');
        $this->loadModel('Combos');
        $this->loadModel('Vouchers');
        $this->loadModel('Landtours');
        $this->loadModel('Hotels');
        $user_id = $this->Auth->user('id');
        $currentYear = date('Y');
        if ($this->request->getData()) {
            $currentYear = $this->request->getData('year');
        }
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
            'BookingSurcharges',
            'BookingRooms',
            'BookingLandtours',
            'BookingLandtourAccessories',
            'BookingLandtourAccessories.LandTourAccessories',
            'BookingLandtours.PickUp',
            'BookingLandtours.DropDown',
        ])->where(['Bookings.sale_id' => $user_id,
            'YEAR(Bookings.complete_date)' => $currentYear,
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
        ])
            ->order(['Bookings.complete_date' => 'DESC'])->toArray();
        $bookinsByMonth = [
            '01' => [],
            '02' => [],
            '03' => [],
            '04' => [],
            '05' => [],
            '06' => [],
            '07' => [],
            '08' => [],
            '09' => [],
            '10' => [],
            '11' => [],
            '12' => [],
        ];
        foreach ($bookings as $bkKey => $booking) {
            $totalSurchargePrice = 0;
            foreach ($booking->booking_surcharges as $surcharge) {
                $totalSurchargePrice += $surcharge->price;
            }
            $booking->total_price = $booking->price +
                ($booking->adult_fee ? $booking->adult_fee : 0)
                + ($booking->children_fee ? $booking->children_fee : 0)
                + ($booking->holiday_fee ? $booking->holiday_fee : 0)
                + ($booking->other_fee ? $booking->other_fee : 0)
                + $totalSurchargePrice;
            $bookinsByMonth[date_format($booking->complete_date, 'm')]['booking'][] = $booking;
        }

        $vinBookings = $this->Vinhmsbookings->find()->contain([
            'Users',
            'Hotels',
            'Hotels.Locations',
            'VinhmsbookingRooms'
        ])->where(['Vinhmsbookings.sale_id' => $user_id,
            'YEAR(Vinhmsbookings.complete_date)' => $currentYear,
            'OR' => [
                ['Vinhmsbookings.status' => 4],
                [
                    'Vinhmsbookings.sale_id = Vinhmsbookings.user_id',
                    'Vinhmsbookings.status' => 3,
                ]
            ]
        ])
            ->order(['Vinhmsbookings.created' => 'DESC'])->toArray();
        foreach ($vinBookings as $bkKey => $booking) {
            $bookinsByMonth[date_format($booking->complete_date, 'm')]['vin_booking'][] = $booking;
        }
        $this->set(compact('bookinsByMonth', 'currentYear'));

    }

    public function indexVin()
    {
        $this->viewBuilder()->setLayout('backend');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Users');
        $this->loadModel('Combos');
        $this->loadModel('Vouchers');
        $this->loadModel('Landtours');
        $this->loadModel('Hotels');
        $user_id = $this->Auth->user('id');
        $currentYear = date('Y');
        if ($this->request->getData()) {
            $currentYear = $this->request->getData('year');
        }
        $bookings = $this->Vinhmsbookings->find()->contain([
            'Users',
            'Hotels',
            'Hotels.Locations',
            'VinhmsbookingRooms'
        ])->where(['Vinhmsbookings.sale_id' => $user_id,
            'YEAR(Vinhmsbookings.created)' => $currentYear,
            'OR' => [
                ['Vinhmsbookings.status' => 4],
            ]
        ])
            ->order(['Vinhmsbookings.created' => 'DESC'])->toArray();
        $bookinsByMonth = [
            '01' => [],
            '02' => [],
            '03' => [],
            '04' => [],
            '05' => [],
            '06' => [],
            '07' => [],
            '08' => [],
            '09' => [],
            '10' => [],
            '11' => [],
            '12' => [],
        ];
        foreach ($bookings as $bkKey => $booking) {
            $bookinsByMonth[date_format($booking->created, 'm')]['booking'][] = $booking;
        }

        $this->set(compact('bookinsByMonth', 'currentYear'));

    }

    public function exportFile()
    {

    }

    public function processDate()
    {
        $this->loadModel('Bookings');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'file_name' => '', 'link' => ''];

        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $date = explode(' - ', $data['reservation']);
            $sDate = $this->Util->formatSQLDate($date[0], 'd/m/Y');
            $eDate = $this->Util->formatSQLDate($date[1], 'd/m/Y');

            $this->processFile($sDate, $eDate);

            $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Doanh thu MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';
            $response['success'] = true;
            $response['link'] = $fileUrl1;
            $response['file_name'] = 'Doanh thu MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';


            return $this->response->withType("application/json")->withStringBody(json_encode($response));

        }
    }

    public function processDateLandtour()
    {
        $this->loadModel('Bookings');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'file_name' => '', 'link' => ''];

        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $date = explode(' - ', $data['reservation']);
            $sDate = $this->Util->formatSQLDate($date[0], 'd/m/Y');
            $eDate = $this->Util->formatSQLDate($date[1], 'd/m/Y');

            $this->processFileLandtour($sDate, $eDate);

            $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Doanh thu Landtour MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';
            $response['success'] = true;
            $response['link'] = $fileUrl1;
            $response['file_name'] = 'Doanh thu Landtour MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';


            return $this->response->withType("application/json")->withStringBody(json_encode($response));

        }
    }

    private
    function processFile($sDate, $eDate)
    {
        $this->loadModel('Bookings');

        $input = WWW_ROOT . "files/exports/template/template.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($input);

        $bookings = $this->Bookings->find()->contain(['Users', 'Hotels', 'HomeStays', 'Vouchers', 'Vouchers.Hotels', 'LandTours', 'BookingSurcharges', 'BookingRooms',])->where([
            'DATE(Bookings.complete_date) >=' => $sDate,
            'DATE(Bookings.complete_date) <=' => $eDate,
            'Bookings.sale_id' => $this->Auth->user('id'),
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
        $current_sheet = $spreadsheet->getSheetByName('Sheet1');
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];


        $indexKey = 4;
        foreach ($bookings as $key => $booking) {
            $days_attended = date_diff($booking->start_date, $booking->end_date);
            $booking->days_attended = $days_attended;
            $default_price = 0;
            $sell_price = 0;
            $revenue = 0;
            $totalPrice = 0;

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

            switch ($booking->type) {
                case VOUCHER:
                    $obj_name = $booking->vouchers->hotel->name;
                    $infors = json_decode($booking->vouchers->payment_information, true);
                    $payment_infomation = "";
                    if ($infors) {
                        foreach ($infors as $infor) {
                            $payment_infomation = $payment_infomation . "Chủ TK: " . $infor['username'] . "\n" . "Số TK: " . $infor['user_number'] . "\n" . "Ngân hàng: " . $infor['user_bank'] . "\n\n";
                        }
                    }
                    $type = "Voucher";
                    break;
                case LANDTOUR:
                    $obj_name = $booking->land_tours['name'];
                    $infors = json_decode($booking->land_tours['payment_information'], true);
                    $payment_infomation = "";
                    if ($infors) {
                        foreach ($infors as $infor) {
                            $payment_infomation = $payment_infomation . "Chủ TK: " . $infor['username'] . "\n" . "Số TK: " . $infor['user_number'] . "\n" . "Ngân hàng: " . $infor['user_bank'] . "\n\n";
                        }
                    }
                    $type = "Landtour";
                    break;
                case HOTEL:
                    $obj_name = $booking->hotels->name;
                    $infors = json_decode($booking->hotels->payment_information, true);
                    $payment_infomation = "";
                    if ($infors) {
                        foreach ($infors as $infor) {
                            $payment_infomation = $payment_infomation . "Chủ TK: " . $infor['username'] . "\n" . "Số TK: " . $infor['user_number'] . "\n" . "Ngân hàng: " . $infor['user_bank'] . "\n\n";
                        }
                    }

                    $type = "Khách sạn";
                    break;
                case HOMESTAY:
                    $obj_name = $booking->home_stays->name;
                    $infors = json_decode($booking->home_stays->payment_information, true);
                    $payment_infomation = "";
                    if ($infors) {
                        foreach ($infors as $infor) {
                            $payment_infomation = $payment_infomation . "Chủ TK: " . $infor['username'] . "\n" . "Số TK: " . $infor['user_number'] . "\n" . "Ngân hàng: " . $infor['user_bank'] . "\n\n";
                        }
                    }
                    $type = "Homestay";
                    break;
            }
            $current_sheet
                ->setCellValue('A' . $indexKey, date('d/m/y', strtotime($booking->complete_date)))->getStyle('A' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('B' . $indexKey, $booking->user ? $booking->user->screen_name : 'Khách lẻ')->getStyle('B' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('C' . $indexKey, $obj_name)->getStyle('C' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('D' . $indexKey, $type)->getStyle('D' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('E' . $indexKey, $booking->full_name)->getStyle('E' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('F' . $indexKey, $booking->amount)->getStyle('F' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('G' . $indexKey, $days_attended->days)->getStyle('G' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('H' . $indexKey, date('d/m/y', strtotime($booking->start_date)))->getStyle('H' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('I' . $indexKey, date('d/m/y', strtotime($booking->end_date)))->getStyle('I' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet->setCellValue('J' . $indexKey, $default_price)->getStyle('J' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet->setCellValue('K' . $indexKey, $sell_price)->getStyle('K' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet->setCellValue('L' . $indexKey, $revenue)->getStyle('L' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet
                ->setCellValue('M' . $indexKey, "Cọc " . ($booking->customer_deposit / 1000000) . "tr")->getStyle('M' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('N' . $indexKey, ($booking->agency_pay == 1) ? "Rồi" : "Chưa")->getStyle('N' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('O' . $indexKey, ($booking->pay_hotel == 1) ? "Rồi" : "Chưa")->getStyle('O' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('P' . $indexKey, $payment_infomation)->getStyle('P' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('Q' . $indexKey, "")->getStyle('Q' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('R' . $indexKey, $booking->code)->getStyle('R' . $indexKey)->getAlignment()->setWrapText(true);
            $indexKey++;
        }

        $dateRange = 'Từ ngày ' . date('d/m/y', strtotime($sDate)) . ' đến ngày ' . date('d/m/y', strtotime($eDate));

        $current_sheet
            ->setCellValue('A1', $dateRange)->getStyle('A1')->getAlignment()->setHorizontal('center')->setWrapText(true);

        $merge_sum = 'A3:I3';
        $current_sheet->mergeCells($merge_sum)->setCellValue('A3', 'Tổng')
            ->getStyle('A3')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->setCellValue('J3', "=SUM(J:J)")->getStyle('J3')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $current_sheet->setCellValue('K3', "=SUM(K:K)")->getStyle('K3')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $current_sheet->setCellValue('L3', "=SUM(L:L)")->getStyle('L3')->getNumberFormat()
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

        $filePath = WWW_ROOT . '/files/exports/Doanh thu MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

        return true;
    }

    private
    function processFileLandtour($sDate, $eDate)
    {
        $this->loadModel('Bookings');

        $input = WWW_ROOT . "files/exports/template/template_landtour.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($input);

        $bookings = $this->Bookings->find()->contain([
            'Users',
            'Sales',
            'LandTours',
            'LandTours.Destinations',
            'BookingLandtours',
            'BookingLandtourAccessories',
            'BookingLandtourAccessories.LandTourAccessories',
            'BookingLandtours.PickUp',
            'BookingLandtours.DropDown',
        ])->where([
            'DATE(Bookings.complete_date) >=' => $sDate,
            'DATE(Bookings.complete_date) <=' => $eDate,
            'Bookings.sale_id' => $this->Auth->user('id'),
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
        $current_sheet = $spreadsheet->getSheetByName('Sheet1');
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];


        $indexKey = 4;
        foreach ($bookings as $key => $booking) {
            $note = $booking->land_tours->name . PHP_EOL . $booking->full_name . ": " . $booking->phone . PHP_EOL . "Số khách :"
            . ($booking->booking_landtour && $booking->booking_landtour->num_adult != 0 ? $booking->booking_landtour->num_adult . " NL" : "")
            . ($booking->booking_landtour && $booking->booking_landtour->num_children != 0 ? " + " . $booking->booking_landtour->num_children . " TE" : "")
            . ($booking->booking_landtour && $booking->booking_landtour->num_kid != 0 ? " + " . $booking->booking_landtour->num_kid . " EB" : "")
            . $booking->note ? "Note: " . $booking->note : "";

            $contactInformation = "Đại diện: " . $booking->full_name . PHP_EOL
                . "SĐT: " . $booking->phone . PHP_EOL
                . "Điểm đón: " . ($booking->booking_landtour && $booking->booking_landtour->pick_up ? $booking->booking_landtour->pick_up->name : "") . " - " . ($booking->booking_landtour && $booking->booking_landtour->detail_pickup ? $booking->booking_landtour->detail_pickup : "") . PHP_EOL
                . "Điểm trả: " . ($booking->booking_landtour && $booking->booking_landtour->drop_down ? $booking->booking_landtour->drop_down->name : "") . " - " . ($booking->booking_landtour && $booking->booking_landtour->detail_drop ? $booking->booking_landtour->detail_drop : "");
            $tourType = "";
            foreach ($booking->booking_landtour_accessories as $accessory) {
                $tourType .= $accessory->land_tour_accessory->name . PHP_EOL;
            }
            $numAdult = $booking->booking_landtour && $booking->booking_landtour->num_adult ? $booking->booking_landtour->num_adult : 0;
            $numChildren = $booking->booking_landtour && $booking->booking_landtour->num_children ? $booking->booking_landtour->num_children : 0;
            $numKid = $booking->booking_landtour && $booking->booking_landtour->num_kid ? $booking->booking_landtour->num_kid : 0;

            $current_sheet
                ->setCellValue('A' . $indexKey, date('d/m/y', strtotime($booking->created)))->getStyle('A' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('B' . $indexKey, $booking->code)->getStyle('B' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('C' . $indexKey, $booking->user ? $booking->user->screen_name : "Khách lẻ")->getStyle('C' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('D' . $indexKey, $note)->getStyle('D' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('E' . $indexKey, $contactInformation)->getStyle('E' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('F' . $indexKey, $numAdult)->getStyle('F' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('G' . $indexKey, $numChildren)->getStyle('G' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('H' . $indexKey, $numKid)->getStyle('H' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('I' . $indexKey, date('d/m/y', strtotime($booking->start_date)))->getStyle('I' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('J' . $indexKey, $tourType)->getStyle('J' . $indexKey)->getAlignment()->setWrapText(true);
            $current_sheet
                ->setCellValue('K' . $indexKey, $booking->price)->getStyle('K' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet
                ->setCellValue('L' . $indexKey, $booking->payment_method == MUSTGO_DEPOSIT ? $booking->mustgo_deposit : 0)->getStyle('L' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $current_sheet
                ->setCellValue('M' . $indexKey, $booking->payment_method == MUSTGO_DEPOSIT ? $booking->mustgo_deposit - $booking->price + $booking->agency_discount : 0)->getStyle('M' . $indexKey)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $indexKey++;
        }

        $dateRange = 'Từ ngày ' . date('d/m/y', strtotime($sDate)) . ' đến ngày ' . date('d/m/y', strtotime($eDate));

        $current_sheet
            ->setCellValue('A1', $dateRange)->getStyle('A1')->getAlignment()->setHorizontal('center')->setWrapText(true);

        $merge_sum = 'A3:I3';
        $current_sheet->mergeCells($merge_sum)->setCellValue('A3', 'Tổng')
            ->getStyle('A3')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->setCellValue('K3', "=SUM(K:K)")->getStyle('K3')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $current_sheet->setCellValue('L3', "=SUM(L:L)")->getStyle('L3')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $current_sheet->setCellValue('M3', "=SUM(M:M)")->getStyle('M3')->getNumberFormat()
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

        $filePath = WWW_ROOT . '/files/exports/Doanh thu Landtour MustGo tu ' . date('d-m-Y', strtotime($sDate)) . ' den ' . date('d-m-Y', strtotime($eDate)) . '.xlsx';
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

        return true;
    }

    public function exportFileCtv()
    {
    }

    public function processSaleListCTV()
    {
        $this->loadModel('Bookings');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'file_name' => '', 'link' => ''];

        if ($this->request->is('ajax')) {
            $this->genListCtv();
            $fileUrl1 = \Cake\Routing\Router::url('/', true) . '/files/exports/Danh sach CTV cua ' . $this->Auth->user('screen_name') . '.xlsx';
            $response['success'] = true;
            $response['link'] = $fileUrl1;
            $response['file_name'] = 'Danh sach CTV cua ' . $this->Auth->user('screen_name') . '.xlsx';
            return $this->response->withType("application/json")->withStringBody(json_encode($response));

        }
    }

    private function genListCtv()
    {
        $this->loadModel('Users');
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $current_sheet = $spreadsheet->getActiveSheet();
        $currentSale = $this->Auth->user();
        $listCtvs = $this->Users->find()->where(['parent_id' => $currentSale['id']]);
        $styling_bold = [
            'font' => [
                'bold' => true,
            ]];

        $mergeHeader = 'A1:E1';
        $current_sheet->mergeCells($mergeHeader)->setCellValue('A1', 'Danh sách Đại lý của ' . $currentSale['screen_name'])
            ->getStyle('A1')->applyFromArray($styling_bold)->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->getStyle('A2')->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->getStyle('B2')->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->getStyle('C2')->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->getStyle('D2')->getAlignment()->setHorizontal('center')->setWrapText(true);
        $current_sheet->getStyle('E2')->getAlignment()->setHorizontal('center')->setWrapText(true);
        $arrayData = [];
        $arrayData[] = [
            'STT', 'Tên hiển thị', 'Email', 'SĐT', 'Zalo'
        ];
        $indexKey = 2;
        foreach ($listCtvs as $key => $ctv) {
            $arrayData[] = [
                $key + 1, $ctv->screen_name, $ctv->email, $ctv->phone, $ctv->zalo
            ];
            $indexKey++;
        }
        $current_sheet->fromArray($arrayData, NULL, 'A2');
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

        $current_sheet->getStyle('A1:E' . $indexKey)->applyFromArray($styleArray);

        $filePath = WWW_ROOT . '/files/exports/Danh sach CTV cua ' . $currentSale['screen_name'] . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

        return true;
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 2 || $user['role_id'] === 5)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }

}
