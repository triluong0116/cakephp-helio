<?php

namespace App\Controller;

use App\Controller\AppController;
use Mpdf\Tag\P;

/**
 * LandTours Controller
 *
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\LandTourSurchargesTable $LandTourSurcharges
 * @method \App\Model\Entity\LandTour[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LandToursController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Departures', 'Destinations']
        ];
        $landTours = $this->paginate($this->LandTours);

        $this->set(compact('landTours'));
    }

    /**
     * View method
     *
     * @param string|null $id Land Tour id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('Users');
        $slug = $this->request->getParam('slug');

        if ($this->Auth->user()) {
            $userId = $this->Auth->user('id');
        } else {
            $userId = 0;
        }

        $combo = $this->LandTours->find()
            ->contain(['Departures', 'Destinations', 'LandTourAccessories', 'LandTourUserPrices' => function ($q) use ($userId) {
                return $q->where(['id' => $userId]);
            }])
            ->where(['LandTours.slug' => $slug])->first();
        if (!$combo) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        $combo->date_start = $combo->start_date;
        $combo->date_end = $combo->end_date;
//        dd($combo->toArray());
        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            $ref = $user['ref_code'];
        }
        $title = $combo->name;
        $headerType = 1;
        $this->set('combo', $combo);
        $this->set(compact('title', 'headerType', 'ref'));
    }

    public function booking()
    {
        if ($this->Auth->user() && $this->Auth->user()['role_id'] == 2) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        } else {
            $this->loadModel('LandTourSurcharges');
            $this->loadModel('LandTourAccessories');
            $this->loadModel('LandTourDrivesurchages');
            $title = 'Mustgo Booking Landtour';
            $headerType = 1;
            $slug = $this->getRequest()->getParam('slug');
            $fromDate = $this->getRequest()->getQuery('fromDate');
            $num_adult = $num_children = $revenue = 0;
            if (!$fromDate) {
                $fromDate = date('d-m-Y');
            };
            if ($this->request->is('post')) {
                $data = $this->getRequest()->getData();
                $num_adult = $data['num_adult'];
                $num_children = $data['num_children'];
                $num_kids = $data['num_kid'];
                $accessory = [];
                if (!isset($data['accessory'])) {
                    $accessory[] = 0;
                } else {
                    $accessory = $data['accessory'];
                }
                $fromDate = $data['fromDate'];
            }
            $landTour = $this->LandTours->find()
                ->where(['LandTours.slug' => $slug])->contain(['LandTourAccessories', 'LandTourDrivesurchages', 'LandTourUserPrices'])->first();
            if (!$landTour) {
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }
            $this->set(compact('headerType', 'title', 'landTour', 'fromDate', 'num_adult', 'num_children', 'num_kids', 'accessory'));
        }
    }

    public function LandTourAddSelectChildAge()
    {
        $response = ['success' => false, 'data' => '', 'errors' => []];
        $this->viewBuilder()->enableAutoLayout(false);
        $data = $this->getRequest()->getData();
        $numChildren = $data['num_children'];
        $this->set(compact('numChildren'));
        $response['data'] = $this->render('add_select_child_age')->body();
        $response['success'] = true;
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function getLandtourPrice()
    {
        $this->loadModel('LandTourSurcharges');
        $this->loadModel('LandTours');
        $this->viewBuilder()->enableAutoLayout('false');
        $response = ['success' => false, 'message' => '', 'price' => 0, 'result' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            if ($this->Auth->user()) {
                $user_id = $this->Auth->user('id');
            } else {
                $user_id = 0;
            }
        }
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
                'LandTourUserPrices' => function ($q) use ($user_id) {
                    return $q->where(['user_id' => $user_id]);
                },
                'LandTourDrivesurchages' => function ($q) use ($data) {
                    return $q->where(['id IN' => [$data['pickup_id'], $data['drop_id']]]);
                }
            ]]);
        if ($data['num_adult']) {
            $defaultPrice = $landtour->price + $landtour->customer_price;
            $defaultProfit = $landtour->customer_price;
            $drive_surcharge = 0;
            if (count($landtour->land_tour_user_prices) > 0) {
                $defaultPrice += $landtour->land_tour_user_prices[0]->price;

            } else {
                $defaultPrice += $landtour->trippal_price;
            }
            foreach ($landtour->land_tour_accessories as $accessory) {
                $defaultPrice += $accessory->adult_price;
            }
            $price = $defaultPrice * $data['num_adult'] + $defaultPrice * $data['num_children'] * $landtour->child_rate / 100 + $defaultPrice * $data['num_kid'] * $landtour->kid_rate / 100;
            $profit = $defaultProfit * $data['num_adult'] + $defaultProfit * $data['num_children'] * $landtour->child_rate / 100 + $defaultProfit * $data['num_kid'] * $landtour->kid_rate / 100;
            $tempPrice = 0;
            if (count($landtour->land_tour_drivesurchages) == 2) {
                $tempPrice = $landtour->land_tour_drivesurchages[0]->price_adult * $data['num_adult'] * 0.5 + $landtour->land_tour_drivesurchages[1]->price_adult * $data['num_adult'] * 0.5;
                if ($tempPrice > ($landtour->land_tour_drivesurchages[0]->price_crowd * 0.5 + $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5)) {
                    $tempPrice = $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5 + $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5;
                }
            } elseif (count($landtour->land_tour_drivesurchages) == 1) {
                $tempPrice = $landtour->land_tour_drivesurchages[0]->price_adult * $data['num_adult'];
                if ($tempPrice > $landtour->land_tour_drivesurchages[0]->price_crowd) {
                    $tempPrice = $landtour->land_tour_drivesurchages[0]->price_crowd;
                }
                if($data['pickup_id'] == 0 || $data['drop_id'] == 0){
                    $tempPrice = $tempPrice / 2;
                }
            }
            $price += $tempPrice;

            if (isset($data['payment_method']) && ($data['payment_method'] == AGENCY_PAY)) {
                $price = $price - $profit;
                $profit = 0;
            }
            if(isset($data['payment_method']) && $data['payment_method'] == MUSTGO_DEPOSIT) {
                $price = $price - $profit;
                $profit = $data['mustgo_deposit'] - $price;
            }

            $response['price'] = number_format($price);
            $response['profit'] = number_format($profit);

            $response['success'] = true;
            $response['result'] = 'Land Tour "' . $landtour->name . '", đi ngày ' . $data['start_date'] . ', Số lượng "' . intval($data['num_adult']) . ' NL, ' . intval($data['num_children'])
                . ' TE, ' . intval($data['num_kid']) . ' EB", ';
            foreach ($landtour->land_tour_accessories as $k => $acess) {
                if ($k != count($landtour->land_tour_accessories) - 1) {
                    $response['result'] .= $acess->name . ", ";
                } else {
                    $response['result'] .= $acess->name . ". ";
                }
            }
            $response['result'] .= '"Giá: ' . $response['price'] . '"';
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function location()
    {
        $this->loadModel('Locations');
        $locations = $this->Locations->find()->where(['landtour_count >' => 0])->toArray();
        $title = 'Tất cả điểm đến';
        $headerType = 1;
        $this->set(compact('headerType', 'locations', 'title'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $landTour = $this->LandTours->newEntity();
        if ($this->request->is('post')) {
            $landTour = $this->LandTours->patchEntity($landTour, $this->request->getData());
            if ($this->LandTours->save($landTour)) {
                $this->Flash->success(__('The land tour has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The land tour could not be saved. Please, try again.'));
        }
        $users = $this->LandTours->Users->find('list', ['limit' => 200]);
        $departures = $this->LandTours->Departures->find('list', ['limit' => 200]);
        $destinations = $this->LandTours->Destinations->find('list', ['limit' => 200]);
        $this->set(compact('landTour', 'users', 'departures', 'destinations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Land Tour id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $landTour = $this->LandTours->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $landTour = $this->LandTours->patchEntity($landTour, $this->request->getData());
            if ($this->LandTours->save($landTour)) {
                $this->Flash->success(__('The land tour has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The land tour could not be saved. Please, try again.'));
        }
        $users = $this->LandTours->Users->find('list', ['limit' => 200]);
        $departures = $this->LandTours->Departures->find('list', ['limit' => 200]);
        $destinations = $this->LandTours->Destinations->find('list', ['limit' => 200]);
        $this->set(compact('landTour', 'users', 'departures', 'destinations'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Land Tour id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $landTour = $this->LandTours->get($id);
        if ($this->LandTours->delete($landTour)) {
            $this->Flash->success(__('The land tour has been deleted.'));
        } else {
            $this->Flash->error(__('The land tour could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function processLandtourPrice()
    {
        $this->loadModel('LandTours');
        $this->loadModel('LandTourSurcharges');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => true, 'price' => 0, 'result' => '', 'data' => '', 'profit' => 0, 'adult_price' => 0, 'child_price' => 0, 'kid_price' => 0];
        $data = $this->getRequest()->getQuery();
        if ($this->Auth->user('id')) {
            $userId = $this->Auth->user('id');
        } else {
            $userId = 0;
        }
        if (!isset($data['accessory'])) {
            $data['accessory'][] = 0;
        }
        $landTour = $this->LandTours->get($data['landtour_id'],
            ['contain' => [
                'LandTourAccessories' => function ($q) use ($data) {
                    return $q->where(['id IN' => $data['accessory']]);
                },
                'LandTourUserPrices' => function ($q) use ($userId) {
                    return $q->where(['user_id' => $userId]);
                }
            ]]);
        $numb_people = intval($data['num_adult']) + intval($data['num_children']);
        $data = $this->getRequest()->getQuery();
        $adultPrice = 0;
        if (count($landTour->land_tour_user_prices) > 0) {
            $saleProfit = (intval($data['num_adult'])) * $landTour->land_tour_user_prices[0]->price + (intval($data['num_children'])) * $landTour->land_tour_user_prices[0]->price * $landTour->child_rate / 100 + (intval($data['num_kid'])) * $landTour->land_tour_user_prices[0]->price * $landTour->kid_rate / 100;
            $adultPrice += $landTour->land_tour_user_prices[0]->price;
        } else {
            $saleProfit = (intval($data['num_adult'])) * $landTour->trippal_price + (intval($data['num_children'])) * $landTour->trippal_price * $landTour->child_rate / 100 + (intval($data['num_kid'])) * $landTour->trippal_price * $landTour->kid_rate / 100;
            $adultPrice += $landTour->trippal_price;
        }
        $profit = (intval($data['num_adult'])) * $landTour->customer_price + (intval($data['num_children'])) * $landTour->customer_price * $landTour->child_rate / 100 + (intval($data['num_kid'])) * $landTour->customer_price * $landTour->kid_rate / 100;
        $price = (intval($data['num_adult'])) * ($landTour->price) + (intval($data['num_children'])) * ($landTour->price) * $landTour->child_rate / 100 + (intval($data['num_kid'])) * ($landTour->price) * $landTour->kid_rate / 100;
        $price += $profit + $saleProfit;
        $adultPrice += ($landTour->price + $landTour->customer_price);
        foreach ($landTour->land_tour_accessories as $k => $accessory) {
            $price += (intval($data['num_adult'])) * $accessory->adult_price + (intval($data['num_children'])) * $accessory->adult_price * $landTour->child_rate / 100 + (intval($data['num_kid'])) * $accessory->adult_price * $landTour->kid_rate / 100;
            $adultPrice += $accessory->adult_price;
        }
        $response['price'] = $price ? number_format($price) . ' VNĐ' : 'Không xác định';
        $response['profit'] = $profit ? number_format($profit) . ' VNĐ' : 'Không xác định';
        $response['result'] = 'Land Tour "' . $landTour->name . '", đi ngày ' . $data['fromDate'] . ', Số lượng "' . intval($data['num_adult']) . ' NL, ' . intval($data['num_children'])
            . ' TE, ' . intval($data['num_kid']) . ' EB", ';
        foreach ($landTour->land_tour_accessories as $k => $accessory) {
            if ($k != count($landTour->land_tour_accessories) - 1) {
                $response['result'] .= $accessory->name . ", ";
            } else {
                $response['result'] .= $accessory->name . ". ";
            }
        }
        $response['result'] .= '"Giá: ' . $response['price'] . '"';

        $childPrice = $adultPrice * $landTour->child_rate / 100;
        $kidPrice = $adultPrice * $landTour->kid_rate / 100;

        $response['adult_price'] = number_format($adultPrice) . " VNĐ";
        $response['child_price'] = number_format($childPrice) . " VNĐ";
        $response['kid_price'] = number_format($kidPrice) . " VNĐ";

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        $this->set(compact('landTour', 'price', 'profit', 'numb_people'));
        $response['data'] = $this->render('process_landtour_price')->body();
        return $output;
    }

    public function calLandtourDriveSurcharge()
    {
        $response = ['success' => true, 'drive_surcharge' => 0];
        $this->viewBuilder()->enableAutoLayout(false);
        if($this->request->is('ajax')){
            $data = $this->request->getData();
            if(!isset($data['pickup_id'])){
                $data['pickup_id'] = 0;
            }
            if(!isset($data['drop_id'])){
                $data['drop_id'] = 0;
            }
            $landtour = $this->LandTours->get($data['land_tour_id'], ['contain' => ['LandTourDrivesurchages' => function ($q) use ($data) {
                return $q->where(['id IN' => [$data['pickup_id'], $data['drop_id']]]);
            }]]);
            $tempDriveSurchage = 0;
            if (count($landtour->land_tour_drivesurchages) == 1) {
                if($data['pickup_id'] == 0 || $data['drop_id'] == 0){
                    $tempDriveSurchage = $data['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult * 0.5;
                    if ($tempDriveSurchage > $landtour->land_tour_drivesurchages[0]->price_crowd  * 0.5) {
                        $tempDriveSurchage = $landtour->land_tour_drivesurchages[0]->price_crowd  * 0.5;
                    }
                } else {
                    $tempDriveSurchage = $data['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult;
                    if ($tempDriveSurchage > $landtour->land_tour_drivesurchages[0]->price_crowd) {
                        $tempDriveSurchage = $landtour->land_tour_drivesurchages[0]->price_crowd;
                    }
                }
            } elseif (count($landtour->land_tour_drivesurchages) == 2) {
                $tempDriveSurchage1 = $data['num_adult'] * $landtour->land_tour_drivesurchages[0]->price_adult * 0.5;
                if ($tempDriveSurchage1 > $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5) {
                    $tempDriveSurchage1 = $landtour->land_tour_drivesurchages[0]->price_crowd  * 0.5;
                }
                $tempDriveSurchage2 = $data['num_adult'] * $landtour->land_tour_drivesurchages[1]->price_adult * 0.5;
                if ($tempDriveSurchage2 > $landtour->land_tour_drivesurchages[1]->price_crowd  * 0.5) {
                    $tempDriveSurchage2 = $landtour->land_tour_drivesurchages[1]->price_crowd  * 0.5;
                }
                $tempDriveSurchage = $tempDriveSurchage1 + $tempDriveSurchage2;
            }
            $response['drive_surcharge'] = number_format($tempDriveSurchage);
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function editBooking()
    {
        if ($this->Auth->user()) {
            $this->loadModel('Bookings');
            $title = 'Xem lại đơn hàng';
            $headerType = 1;
            $this->loadModel('Configs');
            $this->loadModel('Payments');
            $code = $this->getRequest()->getParam('code');
            $booking = $this->Bookings->find()->contain(['BookingSurcharges', 'BookingRooms', 'BookingRooms.Rooms', 'BookingLandtours', 'BookingLandtourAccessories', 'Vouchers', 'LandTours', 'HomeStays'])->where(['code' => $code])->first();
            if ($booking && $booking->status < 3) {
                $this->loadModel('LandTourSurcharges');
                $this->loadModel('LandTourAccessories');
                $this->loadModel('LandTourDrivesurchages');
                $this->loadModel('LandTours');
                $title = 'Mustgo Booking Landtour';
                $headerType = 1;
                $fromDate = $this->getRequest()->getQuery('fromDate');
                $landTour = $this->LandTours->find()
                    ->where(['LandTours.id' => $booking->item_id])->contain(['LandTourAccessories', 'LandTourDrivesurchages', 'LandTourUserPrices'])->first();
                $accessory = [];
                foreach ($booking->booking_landtour_accessories as $k => $access){
                    $accessory[] = $access->land_tour_accessory_id;
                }
                if (!$landTour) {
                    return $this->redirect(['controller' => 'pages', 'action' => 'home']);
                }
                $this->set(compact( 'headerType', 'title', 'booking', 'landTour', 'fromDate', 'accessory'));
            } else {
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }
        } else {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
    }
}
