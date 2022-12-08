<?php

namespace App\Controller\Editor;

use App\Controller\AppController;
use App\Model\Entity\Hotel;

/**
 * Hotels Controller
 *
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\RoomsTable $Rooms
 * @property \App\Model\Table\SurchargesTable $Surcharges
 * @property \App\Model\Table\PriceRoomsTable $PriceRooms
 * @property \App\Model\Table\RoomPricesTable $RoomPrices
 * @property \App\Model\Table\LocationsTable $Locations
 * @property \App\Model\Table\VinhmsallotmentsTable $Vinhmsallotments
 * @property \App\Controller\Component\UtilComponent $Util
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
        $list_object_hotels = $this->Hotels->find()->where([
            'is_vinhms' => 0,
        ]);
        $hotels = $this->paginate($list_object_hotels);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_hotels = $this->Hotels->find()->where([
                'is_vinhms' => 0,
                'is_hotel_link' => 0,
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
            $keyword = $data;
            $this->set(compact('hotels', 'number', 'data' , 'keyword'));
        } else
            $this->set(compact('hotels'));
    }

    public function indexVinpearl()
    {
        $this->paginate = [
            'limit' => 20,
            'contain' => ['Locations']
        ];
        $list_object_hotels = $this->Hotels->find()->where([
            'is_vinhms' => 1,
        ]);
        $hotels = $this->paginate($list_object_hotels);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_hotels = $this->Hotels->find()->where([
                'is_vinhms' => 1,
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
            $keyword = $data;
            $this->set(compact('hotels', 'number', 'data','keyword'));
        } else
            $this->set(compact('hotels'));
    }
    public function indexChannel()
    {
        $this->paginate = [
            'limit' => 20,
            'contain' => ['Locations']
        ];
        $list_object_hotels = $this->Hotels->find()->where([
            'is_hotel_link' => 1,
        ]);
        $hotels = $this->paginate($list_object_hotels);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_hotels = $this->Hotels->find()->where([
                'is_hotel_link' => 1,
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
            $keyword = $data;
            $this->set(compact('hotels', 'number', 'data','keyword'));
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
    public function viewChannel($id = null)
    {
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Locations', 'Channelrooms']
        ]);
//        dd($hotel);
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

                    if ($data['promotion']['error'] == 0) {
                        $promotion = $this->Upload->uploadSingle($data['promotion']);
                        $data['promotion'] = $promotion;
                    } else {
                        unset($data['promotion']);
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

    public function addVinpearl()
    {
        $hotel = $this->Hotels->newEntity();
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);

        $testUrl = $this->viewVars['testUrl'];
        $listHotelVinpearl = [];
        for ($i = 0; $i <= 5; $i++) {
            $tempListHotelVinpearl = $this->Util->getListHotel($testUrl, $i, 10);
            if ($tempListHotelVinpearl['isSuccess']) {
                foreach ($tempListHotelVinpearl['data']['items'] as $item) {
                    $listHotelVinpearl[] = $item;
                }
            }
        }

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

                    if (isset($data['list_email'])) {
                        $data['list_email'] = array_values($data['list_email']);
                        $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['email'] = '';
                    }

                    if (isset($data['list_payment'])) {
                        $data['list_payment'] = array_values($data['list_payment']);
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['payment_information'] = '';
                    }

                    $data['vinhms_meeting']['media'] = $data['vinhms_meeting_media'];
                    $data['vinhms_meeting'] = json_encode($data['vinhms_meeting']);


                    unset($data['reservation']);

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = $data['thumbnail_edit'];
                    }

                    if ($data['banner']['error'] == 0) {
                        $banner = $this->Upload->uploadSingle($data['banner']);
                        $data['banner'] = $banner;
                    } else {
                        $data['banner'] = $data['banner_edit'];
                    }
                    $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                    $data['price_customer'] = str_replace(',', '', $data['price_customer']);

                    $saveCaption = [];
                    $saveCaption['title'] = $data['list_vin_caption']['tittle'];
                    if (isset($data['list_vin_caption']) && isset($data['list_vin_caption']['caption'])) {
                        foreach ($data['list_vin_caption']['caption'] as $key => $vinCaption) {
                            if ($vinCaption['image']['error'] == 0) {
                                $image = $this->Upload->uploadSingle($vinCaption['image']);
                                $saveCaption['caption'][$key]['image'] = $image;
                            } else {
                                $saveCaption['caption'][$key]['image'] = $vinCaption['image_edit'];
                            }
                            $saveCaption['caption'][$key]['content'] = $vinCaption['content'];
                        }
                    } else {
                        $saveCaption = [];
                    }
                    unset($data['list_vin_caption']);
                    $data['vinhms_caption'] = json_encode($saveCaption, JSON_UNESCAPED_UNICODE);

                    if (isset($data['address'])) {
                        $geocoding = $this->Util->newGeoCoding($data['address']);
                        if (isset($geocoding[0]) && $geocoding[0]) {
                            $hotel->lat = $geocoding[0]['lat'];
                            $hotel->lon = $geocoding[0]['lon'];
                        }
                    }
                    $data['is_vinhms'] = 1;
                    $hotel = $this->Hotels->patchEntity($hotel, $data);
                    if ($this->Hotels->save($hotel)) {
                        $this->Flash->success(__('The hotel has been saved.'));
                        return $this->redirect(['action' => 'indexVinpearl']);
                    }
                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 mô tả'));
            }
        }


        $this->set(compact('hotel', 'locations', 'listHotelVinpearl'));
    }
    public function addChannel()
    {
        $this->loadModel('Categories');
        $hotel = $this->Hotels->newEntity();
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);
        $testUrl = $this->viewVars['testUrl'];
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

                    $data['vinhms_meeting']['media'] = $data['vinhms_meeting_media'];
                    $data['vinhms_meeting'] = json_encode($data['vinhms_meeting']);


                    unset($data['reservation']);

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = $data['thumbnail_edit'];
                    }

                    if ($data['banner']['error'] == 0) {
                        $banner = $this->Upload->uploadSingle($data['banner']);
                        $data['banner'] = $banner;
                    } else {
                        $data['banner'] = $data['banner_edit'];
                    }
                    if ($data['contract_file']['error'] == 0) {
                        $contract = $this->Upload->uploadSingle($data['contract_file']);
                        $data['contract_file'] = $contract;
                    } else {
                        unset($data['contract_file']);
                    }
                    $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                    if (isset($data['address'])) {
                        $geocoding = $this->Util->newGeoCoding($data['address']);
                        if (isset($geocoding[0]) && $geocoding[0]) {
                            $hotel->lat = $geocoding[0]['lat'];
                            $hotel->lon = $geocoding[0]['lon'];
                        }
                    }
                    $data['is_hotel_link'] = 1;
                    $data['hotel_link_code'] = $data['channel_code'];
                    $hotel = $this->Hotels->patchEntity($hotel, $data);
                    if ($this->Hotels->save($hotel)) {
                        $this->Flash->success(__('The hotel has been saved.'));
                        return $this->redirect(['action' => 'indexChannel']);
                    }
                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 mô tả'));
            }
        }
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
        $this->set(compact('hotel', 'locations','ultilities'));
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
                $dataRoom[$holiday_price['room_id']]['holiday_price'] = str_replace(',', '', $holiday_price['price']);
                $dataRoom[$holiday_price['room_id']]['holiday_price_agency'] = str_replace(',', '', $holiday_price['holiday_price_agency']);
                $dataRoom[$holiday_price['room_id']]['holiday_price_customer'] = str_replace(',', '', $holiday_price['holiday_price_customer']);
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

    /**
     * Edit method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function commit()
    {
        $this->paginate = [
            'limit' => 20,
            'contain' => ['Locations']
        ];
        $hotels = $this->paginate($this->Hotels->find()->where([
            'is_commit' => 1,
        ]));

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_hotels = $this->Hotels->find()->contain(['Locations'])->where([
                'is_commit' => 1,
                'OR' => [
                    'Hotels.name LIKE' => '%' . $data . '%',
                    'Locations.name LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_hotels->count();
            $hotels = $this->paginate($list_object_hotels);
            $keyword = $data;
            $this->set(compact('hotels','number', 'data', 'keyword'));
        } else
            $this->set(compact('hotels'));
    }

    public function edit($id = null)
    {
        $this->loadModel('Categories');
        $this->loadModel('HotelsCategories');
        $this->loadModel('Rooms');
        $this->loadModel('Surcharges');
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Categories', 'Rooms', 'Rooms.PriceRooms', 'HotelSurcharges']
        ]);
        if ($hotel->is_vinhms == 1) {
            return $this->redirect(['action' => 'editVinpearl', $hotel->id]);
        }
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

                    if ($data['promotion']['error'] == 0) {
                        $promotion = $this->Upload->uploadSingle($data['promotion']);
                        $data['promotion'] = $promotion;
                    } else {
                        $data['promotion'] = $data['promotion_edit'];
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
                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 mô tả'));
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

    public function editVinpearl($id = null)
    {
        $hotel = $this->Hotels->get($id);
        if ($hotel && $hotel->is_vinhms == 0) {
            return $this->redirect(['action' => 'edit', $hotel->id]);
        }
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

        $testUrl = $this->viewVars['testUrl'];
        $res = $this->Util->getDetailHotel($testUrl, $hotel->vinhms_code);

        $listAccessory = [];
        if (isset($res['data']['extends'])) {
            foreach ($res['data']['extends'] as $k => $extend) {
                $listAccessory[] = $extend;
            }
        }

        $meetingImages = [];
        if ($hotel->vinhms_meeting) {
            $meeting = json_decode($hotel->vinhms_meeting, true);
            $medias = json_decode($meeting['media'], true);
            if ($medias) {
                foreach ($medias as $media) {
                    if (file_exists($media)) {
                        $obj['name'] = basename($media);
                        $obj['size'] = filesize($media);
                        $meetingImages[] = $obj;
                    }
                }
            }
        }
        $list_meeting_images = json_encode($meetingImages);
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);

        $testUrl = $this->viewVars['testUrl'];
        $listHotelVinpearl = [];
        for ($i = 0; $i <= 5; $i++) {
            $tempListHotelVinpearl = $this->Util->getListHotel($testUrl, $i, 10);
            if ($tempListHotelVinpearl['isSuccess']) {
                foreach ($tempListHotelVinpearl['data']['items'] as $item) {
                    $listHotelVinpearl[] = $item;
                }
            }
        }



        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (isset($data['extends'])) {
                foreach ($data['extends'] as $k => $extend) {
                    if ($extend['image']['error'] == 0) {
                        $image = $this->Upload->uploadFile($extend['image']);
                        $data['extends'][$k]['image'] = $image;
                    } else {
                        if (isset($data['extends'][$k]['image_edit'])) {
                            $data['extends'][$k]['image'] = $data['extends'][$k]['image_edit'];
                            unset($data['extends'][$k]['image_edit']);
                        }
                    }
                }
                $data['extends'] = json_encode($data['extends'], true);
            }
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

                    if (isset($data['list_email'])) {
                        $data['list_email'] = array_values($data['list_email']);
                        $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['email'] = '';
                    }

                    if (isset($data['list_payment'])) {
                        $data['list_payment'] = array_values($data['list_payment']);
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['payment_information'] = '';
                    }

                    $data['vinhms_meeting']['media'] = $data['vinhms_meeting_media'];
                    $data['vinhms_meeting'] = json_encode($data['vinhms_meeting']);


                    unset($data['reservation']);

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = $data['thumbnail_edit'];
                    }

                    if ($data['banner']['error'] == 0) {
                        $banner = $this->Upload->uploadSingle($data['banner']);
                        $data['banner'] = $banner;
                    } else {
                        $data['banner'] = $data['banner_edit'];
                    }
                    $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                    $data['price_customer'] = str_replace(',', '', $data['price_customer']);

                    $saveCaption = [];
                    $saveCaption['title'] = $data['list_vin_caption']['tittle'];
                    if (isset($data['list_vin_caption']) && isset($data['list_vin_caption']['caption'])) {
                        foreach ($data['list_vin_caption']['caption'] as $key => $vinCaption) {
                            if ($vinCaption['image']['error'] == 0) {
                                $image = $this->Upload->uploadSingle($vinCaption['image']);
                                $saveCaption['caption'][$key]['image'] = $image;
                            } else {
                                $saveCaption['caption'][$key]['image'] = $vinCaption['image_edit'];
                            }
                            $saveCaption['caption'][$key]['content'] = $vinCaption['content'];
                        }
                    } else {
                        $saveCaption = [];
                    }
                    unset($data['list_vin_caption']);
                    $data['vinhms_caption'] = json_encode($saveCaption, JSON_UNESCAPED_UNICODE);

                    if (isset($data['address'])) {
                        $geocoding = $this->Util->newGeoCoding($data['address']);
                        if (isset($geocoding[0]) && $geocoding[0]) {
                            $hotel->lat = $geocoding[0]['lat'];
                            $hotel->lon = $geocoding[0]['lon'];
                        }
                    }
                    $data['is_vinhms'] = 1;
                    $hotel = $this->Hotels->patchEntity($hotel, $data);
                    if ($this->Hotels->save($hotel)) {
                        $this->Flash->success(__('The hotel has been saved.'));
                        return $this->redirect(['action' => 'indexVinpearl']);
                    }
                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 mô tả'));
            }
        }

        $this->set(compact('hotel', 'locations', 'list_images', 'listHotelVinpearl', 'list_meeting_images', 'listAccessory'));
    }
    public function editChannel($id = null)
    {
        $this->loadModel('Categories');
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Categories']
        ]);
        if ($hotel && $hotel->is_hotel_link == 0) {
            return $this->redirect(['action' => 'edit', $hotel->id]);
        }
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
        $meetingImages = [];
        if ($hotel->vinhms_meeting) {
            $meeting = json_decode($hotel->vinhms_meeting, true);
            $medias = json_decode($meeting['media'], true);
            if ($medias) {
                foreach ($medias as $media) {
                    if (file_exists($media)) {
                        $obj['name'] = basename($media);
                        $obj['size'] = filesize($media);
                        $meetingImages[] = $obj;
                    }
                }
            }
        }
        $list_meeting_images = json_encode($meetingImages);
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);

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

                    $data['vinhms_meeting']['media'] = $data['vinhms_meeting_media'];
                    $data['vinhms_meeting'] = json_encode($data['vinhms_meeting']);


                    unset($data['reservation']);

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = $data['thumbnail_edit'];
                    }

                    if ($data['banner']['error'] == 0) {
                        $banner = $this->Upload->uploadSingle($data['banner']);
                        $data['banner'] = $banner;
                    } else {
                        $data['banner'] = $data['banner_edit'];
                    }
                    if ($data['contract_file']['error'] == 0) {
                        $contract = $this->Upload->uploadSingle($data['contract_file']);
                        $data['contract_file'] = $contract;
                    } else {
                        unset($data['contract_file']);
                    }
                    $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                    if (isset($data['address'])) {
                        $geocoding = $this->Util->newGeoCoding($data['address']);
                        if (isset($geocoding[0]) && $geocoding[0]) {
                            $hotel->lat = $geocoding[0]['lat'];
                            $hotel->lon = $geocoding[0]['lon'];
                        }
                    }
                    $data['is_hotel_link'] = 1;
                    $data['hotel_link_code'] = $data['channel_code'];
                    $hotel = $this->Hotels->patchEntity($hotel, $data);
                    if ($this->Hotels->save($hotel)) {
                        $this->Flash->success(__('The hotel has been saved.'));
                        return $this->redirect(['action' => 'indexChannel']);
                    }
                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 mô tả'));
            }
        }
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
        $this->set(compact('hotel', 'locations', 'list_images', 'ultilities' , 'list_meeting_images'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


    public function expiredHotel()
    {
        $this->loadModel('RoomPrices');
        $this->loadModel('Rooms');
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "ngay-het-han-khach-san"])->first();
        $this->paginate = [
            'limit' => 10
        ];
        $endDay = date("Y-m-d", strtotime('+' . $config->value . ' day'));
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
        $this->set(compact('rooms', 'config'));

    }

    public function changeToVinpearl($hotelId)
    {
        $response = ['success' => false];
        $hotel = $this->Hotels->get($hotelId);
        if ($hotel) {
            $hotel = $this->Hotels->patchEntity($hotel, ['is_vinhms' => 1]);
            $this->Hotels->save($hotel);
            $response['success'] = true;
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 4)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }

    public function changeIsCommit()
    {
        $hotelId = $this->request->getData('hotel_id');
        $checked = $this->request->getData('checked');
        $this->viewBuilder()->enableAutoLayout(false);
        $hotel = $this->Hotels->get($hotelId);
        if ($checked == "false") {
            $hotel = $this->Hotels->patchEntity($hotel, ['is_commit' => 0]);
        } else {
            $hotel = $this->Hotels->patchEntity($hotel, ['is_commit' => 1]);
        }
        $this->Hotels->save($hotel);
        $response = ['success' => true];
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function addAllotment($id) {
        $this->loadModel('Vinrooms');
        $this->loadModel('Vinhmsallotments');
        $hotel = $this->Hotels->get($id);
        $testUrl = $this->viewVars['testUrl'];
        $data = [
            "arrivalDate" => date("Y/m/d"),
            "departureDate" => date("Y/m/d"),
            "numberOfRoom" => 1,
            "propertyIds" => [$hotel->vinhms_code],
            "roomOccupancy" => []
        ];
        empty($data['roomOccupancy']);
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
        $listName = [];
        $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
        if (isset($dataApi['data'])) {
            if (count($dataApi['data']['rates']) > 0) {
                foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $k => $singleRoom) {
                    $listRoom[$singleRoom['id']] = [
                        'name' => $singleRoom['name'],
                    ];
                }
            }
        }

        $allotments = $this->Vinhmsallotments->find()
            ->where(['hotel_id' => $id]);
        $listAllotment = [];
        foreach ($allotments as $allotment) {
            if (isset($listAllotment[$allotment->code])) {
                $listAllotment[$allotment->code][] = $allotment;
            } else {
                $listAllotment[$allotment->code][] = $allotment;
            }
        }
        $this->set(compact('hotel', 'listAllotment'));
    }
    public function renderNewAllotment($hotelId) {
        $this->viewBuilder()->enableAutoLayout(false);
        $hotel = $this->Hotels->get($hotelId);
        $testUrl = $this->viewVars['testUrl'];

        $date = 0;
        $listRoom = [];
        while (empty($listRoom)) {
            $data = [
                "arrivalDate" => date('Y/m/d', strtotime(' +' . $date . ' days')),
                "departureDate" => date('Y/m/d', strtotime(' +' . ($date + 1) . ' days')),
                "numberOfRoom" => 1,
                "propertyIds" => [$hotel->vinhms_code],
                "roomOccupancy" => []
            ];
            empty($data['roomOccupancy']);
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
            $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
            if (isset($dataApi['data']) && count($dataApi['data']['rates']) > 0) {
                foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $k => $singleRoom) {
                    $listRoom[$singleRoom['id']] = [
                        'name' => $singleRoom['name'],
                    ];
                }
            }
            $date++;
        }
        $this->set(compact('hotel', 'listRoom'));
        $this->render('render_new_allotment')->body();
    }

    public function saveAllotment() {
        $this->loadModel('Vinhmsallotments');
        $data = $this->request->getData();
        $allotmentCode = $data['code'];
        foreach ($data['vin_room'] as $k => $singleRoom) {
            $allotmentHotelRoom = $this->Vinhmsallotments->find()
                ->where([
                    'code' => $allotmentCode,
                    'hotel_id' => $singleRoom['hotel_id'],
                    'vinroom_code' => $singleRoom['vin_code'],
                ])->first();
            if (!$allotmentHotelRoom) {
                $allotmentHotelRoom = $this->Vinhmsallotments->newEntity();
            }
            $dataAllotment = [
                'code' => $allotmentCode,
                'name' => $allotmentCode,
                'hotel_id' => $singleRoom['hotel_id'],
                'vinroom_name' => $singleRoom['room_name'],
                'vinroom_code' => $singleRoom['vin_code'],
                'sale_revenue' => $singleRoom['sale_revenue'],
                'revenue' => $singleRoom['revenue'],
                'sale_revenue_type' => $singleRoom['sale_revenue_type'],
                'revenue_type' => $singleRoom['revenue_type'],
            ];
            $allotmentHotelRoom = $this->Vinhmsallotments->patchEntity($allotmentHotelRoom, $dataAllotment);
            $this->Vinhmsallotments->save($allotmentHotelRoom);
        }

        $response = ['success' => true];
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function deleteAllotment() {
        $this->loadModel('Vinhmsallotments');
        $data = $this->request->getData();
        $allotmentCode = $data['code'];
        foreach ($data['vin_room'] as $k => $singleRoom) {
            $allotmentHotelRoom = $this->Vinhmsallotments->find()
                ->where([
                    'code' => $allotmentCode,
                    'hotel_id' => $singleRoom['hotel_id'],
                    'vinroom_code' => $singleRoom['vin_code'],
                ])->first();
            if ($allotmentHotelRoom) {
                $this->Vinhmsallotments->delete($allotmentHotelRoom);
            }
        }

        $response = ['success' => true];
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }
}
