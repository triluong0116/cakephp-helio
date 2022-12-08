<?php

namespace App\Controller\Sale;

use App\Controller\AppController;

/**
 * Hotels Controller
 *
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\RoomsTable $Rooms
 * @property \App\Model\Table\PriceRoomsTable $PriceRooms
 *
 * @method \App\Model\Entity\Hotel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HotelsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 20,
            'contain' => ['Locations']
        ];
        $hotels = $this->paginate($this->Hotels);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_hotels = $this->Hotels->find()->where([
                'OR' => [
                    'Hotels.name LIKE' => '%' . $data . '%',
                    'Hotels.description LIKE' => '%' . $data . '%',
                    'Hotels.map LIKE' => '%' . $data . '%',
                    'Hotels.hotline LIKE' => '%' . $data . '%',
                    'Hotels.term LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_hotels->count();
            $hotels = $this->paginate($list_object_hotels);
            $this->set(compact('hotels', 'number', 'data'));
        } else
            $this->set(compact('hotels'));
    }

    /**
     * View method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Locations', 'Categories', 'Rooms']
        ]);

        $this->set('hotel', $hotel);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Categories');
        $this->loadModel('Rooms');
        $this->loadModel('Surcharges');
        $hotel = $this->Hotels->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($data['list_caption'])) {
                if (isset($data['list_email'])) {
                    if (!isset($data['is_special'])) {
                        $data['is_special'] = 0;
                    }

                    if (isset($data['list_caption'])) {
                        $data['list_caption'] = array_values($data['list_caption']);
                        $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['caption'] = '';
                    }

                    if (isset($data['holidays'])) {
                        $data['holidays'] = array_values($data['holidays']);
                        $data['holidays'] = json_encode($data['holidays'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['holidays'] = '';
                    }

                    if (isset($data['weekday'])) {
                        $data['weekday'] = array_values($data['weekday']);
                        $data['weekday'] = json_encode($data['weekday'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['weekday'] = '';
                    }

                    if (isset($data['weekend'])) {
                        $data['weekend'] = array_values($data['weekend']);
                        $data['weekend'] = json_encode($data['weekend'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['weekend'] = '';
                    }

                    if (isset($data['list_email'])) {
                        $data['list_email'] = array_values($data['list_email']);
                        $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['email'] = '';
                    }

                    if (isset($data['list_term'])) {
                        $data['list_term'] = array_values($data['list_term']);
                        $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['term'] = '';
                    }

                    if (isset($data['list_payment'])) {
                        $data['list_payment'] = array_values($data['list_payment']);
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['payment_information'] = '';
                    }

                    if ($data['contract_file']['error'] == 0) {
                        $contract = $this->Upload->uploadSingle($data['contract_file']);
                        $data['contract_file'] = $contract;
                    } else {
                        unset($data['contract_file']);
                    }

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        unset($data['thumbnail']);
                    }
                    if (isset($data['hotel_surcharges'])) {
                        foreach ($data['hotel_surcharges'] as $key => $surcharge) {
                            if (isset($surcharge['price'])) {
                                $data['hotel_surcharges'][$key]['price'] = str_replace(',', '', $surcharge['price']);
                            }
                            if (isset($surcharge['options'])) {
                                foreach ($surcharge['options'] as $k => $option) {
                                    if ($surcharge['surcharge_type'] != SUR_CHECKIN_SOON && $surcharge['surcharge_type'] != SUR_CHECKOUT_LATE) {
                                        $surcharge['options'][$k]['price'] = str_replace(',', '', $option['price']);
                                    }
                                }
                                $data['hotel_surcharges'][$key]['options'] = json_encode($surcharge['options'], JSON_UNESCAPED_UNICODE);
                            }
                        }
                    }

                    $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                    $data['price_customer'] = str_replace(',', '', $data['price_customer']);
                    $hotel = $this->Hotels->patchEntity($hotel, $data);

                    if (isset($data['address'])) {
                        $geocoding = $this->Util->newGeoCoding($data['address']);
                        if (isset($geocoding[0]) && $geocoding[0]) {
                            $hotel->lat = $geocoding[0]['lat'];
                            $hotel->lon = $geocoding[0]['lon'];
                        }
                    }

                    if ($this->Hotels->save($hotel)) {
                        $this->Flash->success(__('The hotel has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }


                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));

                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 Mô tả ngắn'));
            }
        }
        $weekly = [
            WEEK_MON => 'Thứ 2',
            WEEK_TUE => 'Thứ 3',
            WEEK_WED => 'Thứ 4',
            WEEK_THU => 'Thứ 5',
            WEEK_FRI => 'Thứ 6',
            WEEK_SAT => 'Thứ 7',
            WEEK_SUN => 'Chủ nhật'
        ];
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
        $surcharges = $this->Util->createListSurcharge();
        $this->set(compact('hotel', 'locations', 'ultilities', 'weekly', 'surcharges'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Categories');
        $this->loadModel('HotelsCategories');
        $this->loadModel('Rooms');
        $this->loadModel('Surcharges');
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Categories', 'Rooms', 'Rooms.PriceRooms', 'HotelSurcharges']
        ]);
//        dd($hotel);
        $images = [];
        if ($hotel->media) {
            $medias = json_decode($hotel->media, true);
            foreach ($medias as $media) {
                if (file_exists($media)) {
                    $obj['name'] = basename($media);
                    $obj['size'] = filesize($media);
                    $images[] = $obj;
                }
            }
        }
        $list_images = json_encode($images);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (isset($data['list_caption']) && is_array($data['list_caption'])) {
                if (isset($data['list_email']) && is_array($data['list_email'])) {
                    if (!isset($data['is_special'])) {
                        $data['is_special'] = 0;
                    }
                    if (isset($data['list_caption'])) {
                        $data['list_caption'] = array_values($data['list_caption']);
                        $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['caption'] = '';
                    }

                    if (isset($data['holidays'])) {
                        $data['holidays'] = array_values($data['holidays']);
                        $data['holidays'] = json_encode($data['holidays'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['holidays'] = '';
                    }

                    if (isset($data['weekday'])) {
                        $data['weekday'] = array_values($data['weekday']);
                        $data['weekday'] = json_encode($data['weekday'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['weekday'] = '';
                    }

                    if (isset($data['weekend'])) {
                        $data['weekend'] = array_values($data['weekend']);
                        $data['weekend'] = json_encode($data['weekend'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['weekend'] = '';
                    }

                    if (isset($data['list_email'])) {
                        $data['list_email'] = array_values($data['list_email']);
                        $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['email'] = '';
                    }

                    if (isset($data['list_term'])) {
                        $data['list_term'] = array_values($data['list_term']);
                        $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['term'] = '';
                    }

                    if (isset($data['list_payment'])) {
                        $data['list_payment'] = array_values($data['list_payment']);
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['payment_information'] = '';
                    }

                    unset($data['reservation']);

                    if ($data['contract_file']['error'] == 0) {
                        $contract = $this->Upload->uploadSingle($data['contract_file']);
                        $data['contract_file'] = $contract;
                    } else {
                        $data['contract_file'] = $data['contract_file_edit'];
                    }

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = $data['thumbnail_edit'];
                    }

                    if (isset($data['hotel_surcharges'])) {
                        foreach ($data['hotel_surcharges'] as $key => $surcharge) {
                            if (isset($surcharge['price'])) {
                                $data['hotel_surcharges'][$key]['price'] = str_replace(',', '', $surcharge['price']);
                            }
                            if (isset($surcharge['options'])) {
                                foreach ($surcharge['options'] as $k => $option) {
                                    if ($surcharge['surcharge_type'] != SUR_CHECKIN_SOON && $surcharge['surcharge_type'] != SUR_CHECKOUT_LATE) {
                                        $surcharge['options'][$k]['price'] = str_replace(',', '', $option['price']);
                                    }
                                }
                                $data['hotel_surcharges'][$key]['options'] = json_encode($surcharge['options'], JSON_UNESCAPED_UNICODE);
                            }
                            if (isset($surcharge['description'])) {
                                $data['hotel_surcharges'][$key]['description'] = str_replace(',', '', $surcharge['description']);
                            }
                        }
                    } else {
                        $data['hotel_surcharges'] = [];
                    }

                    $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                    $data['price_customer'] = str_replace(',', '', $data['price_customer']);
                    if (isset($data['address'])) {
                        $geocoding = $this->Util->newGeoCoding($data['address']);
                        if (isset($geocoding[0]) && $geocoding[0]) {
                            $hotel->lat = $geocoding[0]['lat'];
                            $hotel->lon = $geocoding[0]['lon'];
                        }
                    }
                    $hotel = $this->Hotels->patchEntity($hotel, $data);
                    if ($this->Hotels->save($hotel)) {
                        $this->Flash->success(__('The hotel has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
                }  else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 Mô tả'));
            }
        }

        $weekly = [
            WEEK_MON => 'Thứ 2',
            WEEK_TUE => 'Thứ 3',
            WEEK_WED => 'Thứ 4',
            WEEK_THU => 'Thứ 5',
            WEEK_FRI => 'Thứ 6',
            WEEK_SAT => 'Thứ 7',
            WEEK_SUN => 'Chủ nhật'
        ];

        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
        $surcharges = $this->Util->createListSurcharge();
        $this->set(compact('hotel', 'locations', 'ultilities', 'list_images', 'weekly', 'surcharges'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $hotel = $this->Hotels->get($id);
        if ($this->Hotels->trash($hotel)) {
            $this->Flash->success(__('The hotel has been deleted.'));
        } else {
            $this->Flash->error(__('The hotel could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function addPrice()
    {
        $this->loadModel('Rooms');
        $this->loadModel('RoomPrices');
        $hotels = $this->Hotels->find('list');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->getRequest()->getData();
            $hotel = $this->Hotels->get($data['hotel']);
            $dataRoom = [];
            foreach ($data['holiday_prices'] as $holiday_price) {
                $dataRoom[$holiday_price['room_id']]['holiday_price'] = str_replace(',', '',$holiday_price['price']);
                $dataRoom[$holiday_price['room_id']]['holiday_price_agency'] = str_replace(',', '',$holiday_price['holiday_price_agency']);
                $dataRoom[$holiday_price['room_id']]['holiday_price_customer'] = str_replace(',', '',$holiday_price['holiday_price_customer']);
            }
            foreach ($data['price_rooms'] as $priceItem) {
                foreach ($priceItem['items'] as $item) {
                    // new
                    $priceRoomData = [
                        'weekday' => str_replace(',', '', $item['weekday']),
                        'weekend' => str_replace(',', '', $item['weekend']),
                        'dates' => $priceItem['date'],
                        'price_agency' => str_replace(',', '', $item['price_agency']),
                        'price_customer' => str_replace(',', '', $item['price_customer']),
                    ];
                    if (isset($item['id'])) {
                        $priceRoomData['id'] = $item['id'];
                    }
                    $dataRoom[$item['room_id']]['price_rooms'][] = $priceRoomData;
                }
            }
            foreach ($dataRoom as $key => $dRoom) {
                $room = $this->Rooms->get($key);
                $dRoom['list_price'] = json_encode($dRoom['price_rooms'], JSON_UNESCAPED_UNICODE);
                unset($dRoom['price_rooms']);
                $room = $this->Rooms->patchEntity($room, $dRoom);
                if ($this->Rooms->save($room)) {
                    $this->Util->saveRoomPrice($hotel, $room);
                }
            }
            return $this->redirect(['action' => 'index']);

        }
        $this->set(compact('hotels'));
    }
    public function expiredHotel()
    {
        $this->loadModel('RoomPrices');
        $this->loadModel('Rooms');
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "ngay-het-han-khach-san"])->first();
        $this->paginate = [
            'limit' => 10
        ];
        $endDay = date("Y-m-d", strtotime('+'.$config->value.' day'));
        $condition = [];
        $keyword = $this->request->getQuery('search');
        if ($keyword) {
            $condition = [
                'OR' => [
                    'Hotels.name LIKE' => '%' . $keyword . '%',
                    'Hotels.description LIKE' => '%' . $keyword . '%',
                    'Hotels.map LIKE' => '%' . $keyword . '%',
                    'Hotels.hotline LIKE' => '%' . $keyword . '%',
                    'Hotels.term LIKE' => '%' . $keyword . '%'
                ]
            ];
        }
        $rooms = $this->RoomPrices->find()
            ->contain(['Rooms', 'Rooms.Hotels'])
            ->select($this->RoomPrices)
            ->select($this->RoomPrices->Rooms)
            ->select($this->RoomPrices->Rooms->Hotels)
            ->select(['max_room_day' => 'max(room_day)'])
            ->where($condition)
            ->group('Rooms.hotel_id')
            ->having(['max(room_day) <=' => $endDay]);
        $rooms = $this->paginate($rooms);
        $this->set(compact('rooms','config'));

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

    public function setFeatured()
    {
        $this->viewBuilder()->autoLayout(false);
        $response = ['success' => false, 'message' => ''];
        $data = $this->request->getData();
        if ($data['ids']) {
            $this->Hotels->updateAll(['is_feature' => 1], ['id IN' => $data['ids']]);
        }
        $response['success'] = true;

        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function unsetFeatured()
    {
        $this->viewBuilder()->autoLayout(false);
        $response = ['success' => false, 'message' => ''];
        $data = $this->request->getData();
        if ($data['ids']) {
            $this->Hotels->updateAll(['is_feature' => 0], ['id IN' => $data['ids']]);
        }
        $response['success'] = true;

        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }
}
