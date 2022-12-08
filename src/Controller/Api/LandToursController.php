<?php

namespace App\Controller\Api;


/**
 * LandTours Controller
 *
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\LandTourUserPricesTable $LandTourUserPrices
 *
 * @method \App\Model\Entity\LandTour[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LandToursController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail', 'calPrice', 'calPriceBooking']);
    }

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

    public function lists()
    {
        $this->paginate = [
            'limit' => 10
        ];
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }
        $location_id = $this->getRequest()->getQuery('location_id');
        $rating = $this->getRequest()->getQuery('rating');
        $price = $this->getRequest()->getQuery('price');
        $clientId = $this->getRequest()->getQuery('clientId');
        $keyword = $this->getRequest()->getQuery('keyword');
        $page = $this->getRequest()->getQuery('page');
        $condition = [];
        if ($location_id) {
            $condition['LandTours.destination_id'] = $location_id;
        } else {
            $condition['LandTours.name LIKE'] = '%' . $keyword . '%';
        }
        if ($rating) {
            $condition['LandTours.rating'] = $rating;
        }
        if ($price) {
            $price_arr = explode('-', $price);
        }
        $landtours = $this->LandTours->find()->contain([
            'Favourites' => function ($q) use ($clientId) {
                return $q->where(['clientId' => $clientId]);
            },
            'LandTourAccessories',
            'LandTourUserPrices' => function ($q) use ($user_id) {
                return $q->where(['user_id' => $user_id]);
            }
        ])->where($condition)->toArray();
        foreach ($landtours as $k => $landtour) {
            if ($landtour->favourites) {
                $landtour->is_favourite = true;
            } else {
                $landtour->is_favourite = false;
            }
            unset($landtour->favourites);
            foreach ($landtour->land_tour_accessories as $accessory) {
                $landtour->singlePrice += $accessory->adult_price;
            }
            if (count($landtour->land_tour_user_prices) > 0) {
                $landtour->singlePrice += $landtour->price + $landtour->customer_price + $landtour->land_tour_user_prices[0]->price;
            } else {
                $landtour->singlePrice += $landtour->price + $landtour->trippal_price + $landtour->customer_price;
            }
            if (count($price_arr) == 2) {
                if (is_numeric($price_arr[0]) && is_numeric($price_arr[1])) {
                    if ($landtour->singlePrice < $price_arr[0] || $landtour->singlePrice > $price_arr[1]) {
                        unset($landtours[$k]);
                    }
                }
            }

        }
        $landtours = array_slice($landtours, 10 * ($page - 1), 10);
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $landtours,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id)
    {
        $this->loadModel('LandTourUserPrices');
        $clientId = $this->getRequest()->getQuery('clientId');
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }
        $landtour = $this->LandTours->get($id, ['contain' => [
            'Favourites' => function ($query) use ($clientId) {
                return $query->where(['clientId' => $clientId]);
            },
            'LandTourAccessories',
            'LandTourDrivesurchages'
        ]]);
        $price = $this->LandTourUserPrices->find()->where(['land_tour_id' => $landtour->id, 'user_id' => $user_id])->first();
        if ($price) {
            $landtour->land_tour_user_prices = $price->price;
        } else {
            $landtour->land_tour_user_prices = 0;
        }
        $listCaption = json_decode($landtour->caption, true);
        foreach ($listCaption as $key => $caption) {
            $listCaption[$key]['content'] = strip_tags(html_entity_decode($caption['content']));
        }
        $landtour->caption = $listCaption;
        $landtour->media = json_decode($landtour->media, true);
        $landtour->term = json_decode($landtour->term, true);
        if ($landtour->favourites) {
            $landtour->is_favourite = true;
        } else {
            $landtour->is_favourite = false;
        }
        unset($landtour->favourites);
        $landtour->adult_description = "";
        $landtour->children_description = "";
        $landtour->kid_description = "";
        if($landtour->people_description){
            $json = json_decode($landtour->people_description, true);
            if(!$json){
                $json = [];
            }
            isset($json['description_type']) ? true : $json['description_type'] = "";
            isset($json['adult_description']) ?  true : $json['adult_description'] = "";
            isset($json['child_description']) ? true : $json['child_description'] = "";
            isset($json['kid_description']) ? true : $json['kid_description'] = "";

            $description = "";
            if($json['description_type'] == "age"){
                $description= "<i class='fas fa-child'></i> Theo tuổi: ";
            }
            if($json['description_type'] == "height"){
                $description = "<i class='fas fa-ruler-vertical'></i> Theo chiều cao: ";
            }
            $landtour->adult_description = $description . $json['adult_description'];
            $landtour->children_description = $description . $json['child_description'];
            $landtour->kid_description = $description . $json['kid_description'];
        }
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $landtour,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function calPrice()
    {
        $data = $this->getRequest()->getQuery();
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }
        $landTour = $this->LandTours->get($data['landtour_id'], ['contain' =>
            [
                'LandTourAccessories' => function ($q) use ($data) {
                    return $q->where(['id IN' => $data['accessory']]);
                },
                'LandTourUserPrices' => function ($q) use ($user_id) {
                    return $q->where(['user_id' => $user_id]);
                },
            ]
        ]);
        $defaultPrice = 0;
        $priceAdult = 0;
        $priceChilren = 0;
        $priceKid = 0;
        $revenue = 0;
        if (count($landTour->land_tour_accessories) > 0) {
            foreach ($landTour->land_tour_accessories as $k => $accessory) {
                $defaultPrice += $accessory->adult_price;
            }
        }
        if (count($landTour->land_tour_user_prices) > 0) {
            $defaultPrice += $landTour->land_tour_user_prices[0]->price;
        } else {
            $defaultPrice += $landTour->trippal_price;
        }
        $defaultPrice += $landTour->price + $landTour->customer_price;
        $revenue = $landTour->customer_price;
        $priceAdult = $defaultPrice;
        $priceChilren = $defaultPrice * $landTour->child_rate / 100;
        $priceKid = $defaultPrice * $landTour->kid_rate / 100;


        $price = $priceAdult * $data['numAdult'] + $priceChilren * $data['numChildren'] + $priceKid * $data['numKid'];
        $revenue = $revenue * $data['numAdult'] + $revenue * $landTour->child_rate / 100 * $data['numChildren'] + $revenue * $landTour->kid_rate / 100 * $data['numKid'];

        $detailBooking = $landTour->name . " dành cho " . $data['numAdult'] . " NL" . ($data['numChildren'] != 0 ? ", " . $data['numChildren'] . " TE" : "") .  ($data['numKid'] != 0 ? ", " . $data['numKid'] . " EB" : "") . ", ";
        foreach ($landTour->land_tour_accessories as $k => $acess) {
            if ($k != count($landTour->land_tour_accessories) - 1) {
                $detailBooking .= $acess->name . ", ";
            } else {
                $detailBooking .= $acess->name . ". ";
            }
        }
        $detailBooking .= "ngày đi: " . date('d-m-Y', strtotime($data['start_date']));


        $response['singleAdultPrice'] = $priceAdult;
        $response['singleChildrenPrice'] = $priceChilren;
        $response['singleKidPrice'] = $priceKid;
        $response['totalPrice'] = $price;
        $response['revenue'] = $revenue;
        $response['detail_booking'] = $detailBooking;
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $response,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function calPriceBooking()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
        } else {
            $user_id = 0;
        }
        $res = ['status' => STT_SUCCESS, 'message' => 'Thành công'];
        $this->loadModel('LandTourSurcharges');
        $data = $this->getRequest()->getData();
        $this->Util->writeLogFileApi($data);
        $landtour = $this->LandTours->get($data['item_id'], ['contain' =>
            [
                'LandTourAccessories' => function ($q) use ($data) {
                    return $q->where(['id IN' => $data['accessory_id']]);
                },
                'LandTourUserPrices' => function ($q) use ($user_id) {
                    return $q->where(['user_id' => $user_id]);
                },
                'LandTourDrivesurchages' => function ($q) use ($data) {
                    return $q->where(['id IN' => $data['drive_location_id']]);
                }
            ]]);
        if ($data['booking_landtour']['num_adult']) {
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
            $price = $defaultPrice * $data['booking_landtour']['num_adult'] + $defaultPrice * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $defaultPrice * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
            $profit = $defaultProfit * $data['booking_landtour']['num_adult'] + $defaultProfit * $data['booking_landtour']['num_children'] * $landtour->child_rate / 100 + $defaultProfit * $data['booking_landtour']['num_kid'] * $landtour->kid_rate / 100;
            $tempPrice = 0;
            if (count($landtour->land_tour_drivesurchages) == 2) {
                $tempPrice = $landtour->land_tour_drivesurchages[0]->price_adult * $data['booking_landtour']['num_adult'] * 0.5 + $landtour->land_tour_drivesurchages[1]->price_adult * $data['booking_landtour']['num_adult'] * 0.5;
                if ($tempPrice > ($landtour->land_tour_drivesurchages[0]->price_crowd * 0.5 + $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5)) {
                    $tempPrice = $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5 + $landtour->land_tour_drivesurchages[1]->price_crowd * 0.5;
                }
            } elseif (count($landtour->land_tour_drivesurchages) == 1) {
                if(count($data['drive_location_id']) == 1){
                    $tempPrice = $landtour->land_tour_drivesurchages[0]->price_adult * $data['booking_landtour']['num_adult'] * 0.5;
                    if ($tempPrice > $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5) {
                        $tempPrice = $landtour->land_tour_drivesurchages[0]->price_crowd * 0.5;
                    }
                } else {
                    $tempPrice = $landtour->land_tour_drivesurchages[0]->price_adult * $data['booking_landtour']['num_adult'];
                    if ($tempPrice > $landtour->land_tour_drivesurchages[0]->price_crowd) {
                        $tempPrice = $landtour->land_tour_drivesurchages[0]->price_crowd;
                    }
                }
            }
            $drive_surcharge = $tempPrice;
            $price += $tempPrice;
            if ($data['payment_method'] == AGENCY_PAY) {
                $price = $price - $profit;
                $profit = 0;
            }
            if ($data['payment_method'] == MUSTGO_DEPOSIT) {
                $price = $price - $profit;
                if(isset($data['mustgo_deposit'])){
                    $profit = $data['mustgo_deposit'] - $price;
                } else {
                    $profit = 0;
                }
            }
            $response = [
                'price' => $price,
                'profit' => $profit,
                'drive_surcharge' => $drive_surcharge
            ];
        } else {
            $res['status'] = STT_NOT_VALIDATION;
            $res['message'] = 'Bạn chưa nhập số người lớn.';
        }
        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => isset($response) ? $response : null,
            '_serialize' => ['status', 'message', 'data']
        ]);

//        $price =
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
        $landTour = $this->LandTours->get($id, [
            'contain' => ['Users', 'Departures', 'Destinations']
        ]);

        $this->set('landTour', $landTour);
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
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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
}
