<?php

namespace App\Controller\Editor;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
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
        $this->loadModel('Users');
        $this->loadModel('Combos');
        $this->loadModel('Vouchers');
        $this->loadModel('Landtours');
        $this->loadModel('Hotels');
        $user_id = $this->Auth->user('id');
        $bookings = $this->Bookings->find()->contain(['Users'])->where(['Users.id' => $user_id])->orwhere(['Users.parent_id' => $user_id])->order(['Bookings.created' => 'DESC']);
        foreach ($bookings as $booking) {
            $booking->days_attended = date_diff($booking->start_date, $booking->end_date);
//            dd($booking->days_attended);
            switch ($booking->type) {
                case HOTEL:
                    $this->loadModel('Hotels');
                    $hotel = $this->Hotels->get($booking->item_id, ['contain' => 'Locations']);
                    $booking->hotel = $hotel;
                    break;
                case COMBO:
                    $this->loadModel('Combos');
                    $combo = $this->Combos->get($booking->item_id, ['contain' => ['Hotels', 'Destinations']]);
                    $booking->combo = $combo;
                    break;
                case LANDTOUR:
                    $this->loadModel('LandTours');
                    $landTour = $this->LandTours->get($booking->item_id, ['contain' => 'Destinations']);
                    $booking->land_tour = $landTour;
                    break;
                case VOUCHER:
                    $this->loadModel('Vouchers');
                    $voucher = $this->Vouchers->get($booking->item_id, ['contain' => ['Hotels', 'Destinations']]);
                    $booking->voucher = $voucher;
                    break;
            }
        }
//        dd($bookings->toArray());
        $this->set(compact('bookings'));

    }

    public function hotelReport()
    {
        $this->loadModel('Bookings');
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('Hotels');

        $currentYear = date('Y');
        $currentHotel = 0;
        if ($this->request->getData()) {
            $currentYear = $this->request->getData('year');
            $currentHotel = $this->request->getData('hotel_id');
        }
        $conditionHotel = [];
        $conditionVin = [];
        if ($currentHotel) {
            $conditionHotel['Bookings.item_id'] = $currentHotel;
            $conditionVin['Vinhmsbookings.hotel_id'] = $currentHotel;
        }
        $bookings = $this->Bookings->find()->contain(['Hotels', 'Hotels.Locations', 'BookingRooms'])
            ->where([
                $conditionHotel,
                'YEAR(Bookings.complete_date)' => $currentYear,
                'Bookings.type' => HOTEL,
                'OR' => [
                    ['Bookings.status' => 4],
                    [
                        'Bookings.sale_id = Bookings.user_id',
                        'Bookings.status' => 3,
                    ],
                    [
                        'Bookings.payment_method' => AGENCY_PAY,
                        'Bookings.status >=' => 3,
                        'Bookings.status <=' => 4,
                    ],
                    [
                        'Bookings.booking_type' => ANOTHER_BOOKING
                    ]
                ]
            ]);
        $data = [];
        $totalByMonth = [
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
//        dd($bookings->toArray());
        foreach ($bookings as $booking) {
            if ($booking->hotels) {
                $data[$booking->item_id]['name'] = $booking->hotels->name;
                if ($booking->hotels->location) {
                    $data[$booking->item_id]['location'] = $booking->hotels->location->name;
                } else {
                    $data[$booking->item_id]['location'] = "Không có địa điểm";
                }
            }
            $data[$booking->item_id]['month'] = [
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
        }
        foreach ($bookings as $booking) {
            $month = date("n", strtotime($booking->complete_date));
            $totalByBooking = 0;
            if ($booking->booking_rooms) {
                foreach ($booking->booking_rooms as $booking_room) {
                    if ($booking_room->end_date && $booking_room->start_date) {
                        $date = date_diff($booking_room->end_date, $booking_room->start_date);
                        $totalByBooking += $date->days * $booking_room->num_room;
                    }
                }
            } else {
                if ($booking->end_date && $booking->start_date) {
                    $date = date_diff($booking->end_date, $booking->start_date);
                    $totalByBooking = $date->days * $booking->amount;
                }
            }
            $data[$booking->item_id]['month'][$month] += $totalByBooking;
            $totalByMonth[$month] += $totalByBooking;
        }

        $vinbookings = $this->Vinhmsbookings->find()->contain(['Hotels', 'Hotels.Locations', 'VinhmsbookingRooms'])
            ->where([
                $conditionVin,
                'YEAR(Vinhmsbookings.complete_date)' => $currentYear,
                'OR' => [
                    ['Vinhmsbookings.status' => 4],
                    ['Vinhmsbookings.status' => 3],
                ]
            ]);
        foreach ($vinbookings as $vinbooking) {
            if ($vinbooking->hotel) {
                $data[$vinbooking->hotel_id]['name'] = $vinbooking->hotel->name;
                if ($vinbooking->hotel->location) {
                    $data[$vinbooking->hotel_id]['location'] = $vinbooking->hotel->location->name;
                } else {
                    $data[$vinbooking->hotel_id]['location'] = "Không có địa điểm";
                }
            }
            $data[$vinbooking->hotel_id]['month'] = [
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
        }
        foreach ($vinbookings as $vinbooking) {
            $month = date("n", strtotime($vinbooking->complete_date));
            if ($vinbooking->vinhmsbooking_rooms) {
                foreach ($vinbooking->vinhmsbooking_rooms as $singleRoom) {
                    $days = date_diff($singleRoom->checkin, $singleRoom->checkout);
                    $data[$vinbooking->hotel_id]['month'][$month] += $days->days;
                    $totalByMonth[$month] += $days->days;
                }
            }
        }


        $queryHotel = $this->Hotels->find();
        $listHotel = [];
        foreach ($queryHotel as $hotel) {
            $listHotel[$hotel->id] = $hotel->name;
        }

        $this->set(compact(['data', 'totalByMonth', 'currentYear', 'listHotel', 'currentHotel']));
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 4)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'editor'));
        return parent::isAuthorized($user);
    }

}
