<?php

namespace App\Controller\Editor;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Rooms Controller
 *
 * @property \App\Model\Table\RoomsTable $Rooms
 * @property \App\Model\Table\ChannelroomsTable $Channelrooms
 * @property \App\Model\Table\ChannelrateplanesTable $Channelrateplanes
 * @property \App\Model\Table\PriceRoomsTable $PriceRooms
 * @property \App\Model\Table\RoomPricesTable $RoomPrices
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\VinroomsTable $Vinrooms
 * @property \App\Controller\Component\UtilComponent $Util
 *
 * @method \App\Model\Entity\Room[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RoomsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Hotels');
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Rooms']
        ];
        $condition = [];
        if ($this->request->getQuery()) {
            $hotel_id = $this->request->getQuery('hotel_id');
            $condition['id'] = $hotel_id;
        }
        $hotelSearches = $this->Hotels->find('list')->all()->toArray();
        $hotels = $this->Hotels->find()->where($condition);
        $hotels = $this->paginate($hotels);
        $this->set(compact('hotels', 'hotelSearches'));
    }

    public function listRoom($id = null)
    {
        $this->loadModel('Hotels');
        $hotel = $this->Hotels->find()->where(['id' => $id])->first();
        $this->paginate = [
            'limit' => 10
        ];
        $rooms = $this->Rooms->find()->where(['hotel_id' => $id]);
        $rooms = $this->paginate($rooms);
        $this->set(compact('rooms', 'hotel'));
    }

    public function listRoomVin($id = null)
    {
        $this->loadModel('Vinrooms');
        $this->loadModel('Hotels');
        $startDate = date('Y-m-d', strtotime('+5 days'));
        $endDate = date('Y-m-d', strtotime('+6 days'));
        $testUrl = $this->viewVars['testUrl'];
        $hotel = $this->Hotels->find()->where(['id' => $id])->first();
        $data = [
            "arrivalDate" => $startDate,
            "departureDate" => $endDate,
            "numberOfRoom" => 1,
            "propertyIds" => [$hotel->vinhms_code],
            "roomOccupancy" => []
        ];
        $roomOccupancy = [
            'numberOfAdult' => 1,
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
        $listRoom = [];
        $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);
        if (isset($dataApi['data'])) {
            foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $k => $singleRoom) {
                $listRoom[$singleRoom['id']] = [
                    'name' => $singleRoom['name'],
                ];
                $vinRoom = $this->Vinrooms->find()->where(['vin_code' => $singleRoom['id'], 'hotel_id' => $hotel->id])->first();
                if (!$vinRoom) {
                    $listRoom[$singleRoom['id']]['trippal_price'] = 0;
                    $listRoom[$singleRoom['id']]['customer_price'] = 0;
                    $listRoom[$singleRoom['id']]['trippal_price_type'] = 0;
                    $listRoom[$singleRoom['id']]['customer_price_type'] = 0;
                } else {
                    $listRoom[$singleRoom['id']]['trippal_price'] = $vinRoom->trippal_price;
                    $listRoom[$singleRoom['id']]['customer_price'] = $vinRoom->customer_price;
                    $listRoom[$singleRoom['id']]['trippal_price_type'] = $vinRoom->trippal_price_type;
                    $listRoom[$singleRoom['id']]['customer_price_type'] = $vinRoom->customer_price_type;
                }

            }
        }
        $this->set(compact('listRoom', 'hotel'));
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if ($data) {
                foreach ($data['vin_room'] as $k => $dataSave) {
                    $dataSave['trippal_price'] = str_replace(',', '', $dataSave['trippal_price']);
                    $dataSave['customer_price'] = str_replace(',', '', $dataSave['customer_price']);
                    $vinRoomSave = $this->Vinrooms->find()->where(['vin_code' => $dataSave['vin_code'], 'hotel_id' => $dataSave['hotel_id']])->first();
                    if (!$vinRoomSave) {
                        $vinRoomSave = $this->Vinrooms->newEntity();
                    }
                    $vinRoomSave = $this->Vinrooms->patchEntity($vinRoomSave, $dataSave);
                    $this->Vinrooms->save($vinRoomSave);
                }
            }
            return $this->redirect(['controller' => 'Rooms', 'action' => 'listRoomVin', ['id' => $id]]);
        }
    }

    public function detailRoomVin($code = null, $hotelId = null) {
        $this->loadModel('Hotels');
        $this->loadModel('Vinrooms');
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+1 day', strtotime($startDate)));
        $testUrl = $this->viewVars['testUrl'];
        $hotel = $this->Hotels->find()->where(['id' => $hotelId])->first();
        $dataVinRoom = $this->Vinrooms->find()->where(['vin_code' => $code])->first();
        $images = [];
        if ($dataVinRoom && $dataVinRoom->thumbnail) {
            $medias = json_decode($dataVinRoom->thumbnail, true);
            foreach ($medias as $media) {
                if (file_exists($media)) {
                    $obj['name'] = basename($media);
                    $obj['size'] = filesize($media);
                    $images[] = $obj;
                }
            }
        }

        $list_images = json_encode($images);
        $data = [
            "arrivalDate" => $startDate,
            "departureDate" => $endDate,
            "numberOfRoom" => 1,
            "propertyIds" => [$hotel->vinhms_code],
            "roomOccupancy" => []
        ];
        $roomOccupancy = [
            'numberOfAdult' => 1,
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
        $listRoom = [];
        $dataApi = $this->Util->SearchHotelHmsAvailability($testUrl, $data);

        if ($dataApi['isSuccess']) {
            foreach ($dataApi['data']['rates'][0]['property']['roomTypes'] as $singleRoom) {
                if ($singleRoom['id'] == $code) {
                    $dataRoom = $singleRoom;
                    break;
                }
            }
        }
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if ($data) {
                foreach ($data['extends'] as $k => $extend) {
                    if ($extend['image']['error'] == 0) {
                        $image = $this->Upload->uploadFile($extend['image']);
                        $data['extends'][$k]['image'] = $image;
                    } else {
                        $data['extends'][$k]['image'] = $data['extends'][$k]['image_edit'];
                        unset($data['extends'][$k]['image_edit']);
                    }
                }
                $data['thumbnail'] = $data['media'];
                unset($data['media']);
                $vinroom = $this->Vinrooms->find()->where(['vin_code' => $code])->first();
                if (!$vinroom) {
                    $vinroom = $this->Vinrooms->newEntity();
                    $data['trippal_price'] = 0;
                    $data['customer_price'] = 0;
                    $data['hotel_id'] = $hotel->id;
                    $data['vin_code'] = $code;
                }
                $data['extends'] = json_encode($data['extends'], true);
                $vinroom = $this->Vinrooms->patchEntity($vinroom, $data);
                $this->Vinrooms->save($vinroom);
                return $this->redirect(['action' => 'listRoomVin', $hotel->id]);
            }
        }
        $this->set(compact('dataRoom', 'list_images', 'dataVinRoom'));
    }

    /**
     * View method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('Rooms');
        $room = $this->Rooms->get($id, [
            'contain' => ['Categories', 'PriceRooms']
        ]);

        $this->set('room', $room);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($hotel_id)
    {
        $this->loadModel('RoomPrices');
        $hotel = $this->Rooms->Hotels->query()->where(['id =' => $hotel_id])->first();
        if ($hotel) {
            $room = $this->Rooms->newEntity();
            if ($this->request->is('post')) {

                $data = $this->request->getData();
                $data['hotel_id'] = $hotel_id;
                $room = $this->Rooms->patchEntity($room, $data);
                if ($this->Rooms->save($room)) {
                    $this->Util->saveRoomPrice($hotel, $room);
                    $this->Flash->success(__('The room has been saved.'));

                    return $this->redirect(['action' => 'listRoom', $data['hotel_id']]);
                }
                $this->Flash->error(__('The room could not be saved. Please, try again.'));
            }
            $this->set(compact('room', 'hotel'));

        } else {
            return $this->redirect(['controller' => 'hotels', 'action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('RoomPrices');
        $room = $this->Rooms->get($id);
        $images = [];
        if ($room->media) {
            $medias = json_decode($room->media, true);
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
            if (!isset($data['have_breakfast'])) {
                $data['have_breakfast'] = 0;
            }
            $hotel = $this->Rooms->Hotels->get($room->hotel_id);
            $room = $this->Rooms->patchEntity($room, $data);
            if ($this->Rooms->save($room)) {
                $this->Util->saveRoomPrice($hotel, $room);
                $this->Flash->success(__('The room has been saved.'));

                return $this->redirect(['action' => 'listRoom', $room->hotel_id]);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));

        }
        $this->set(compact('room', 'list_images'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $room = $this->Rooms->get($id);
        $hotel = $this->Rooms->Hotels->get($room->hotel_id);
        if ($this->Rooms->delete($room)) {
            $this->Flash->success(__('The room has been deleted.'));
        } else {
            $this->Flash->error(__('The room could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'Rooms', 'action' => 'listRoom', $hotel->id]);
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
    public function listRoomChannel($id = null){
        $this->loadModel('Hotels');
        $this->loadModel('Channelrooms');
        $this->loadModel('Channelrateplanes');
        $hotel = $this->Hotels->find()->where(['id' => $id])->first();
        $this->paginate = [
            'limit' => 10
        ];
        $rooms = $this->Channelrooms->find()->where(['hotel_id' => $id]);

        if (!$rooms->toArray()){
            $data = $this->Util->getRatePlans($hotel->hotel_link_code);
            if ($data['result']){
                $dataroom = $datarateplan = [];
//                dd($data);
                foreach ($data['data']['Rooms'] as $val){
                    $dataroom = [
                        'hotel_id' => $hotel->id,
                        'hotel_link_code' => $hotel->hotel_link_code,
                        'room_code' => $val['RoomId'],
                        'name' => $val['Name'],
                    ];
                    $dataRoomSave = $this->Channelrooms->newEntity($dataroom);
                    if ($this->Channelrooms->save($dataRoomSave)){
                        foreach ($val['RatePlans'] as $item){
                            $datarateplan = [
                                'channel_room_id' => $dataRoomSave->id,
                                'rateplan_code' => $item['RatePlanId'],
                                'name' => $item['Name'],
                                'guest' => $item['GuestsIncluded'],
                                'adult' => $item['AdultGuestsIncluded'],
                                'child' => $item['ChildGuestsIncluded'],
                                'maxguest' => $item['MaxGuests'],
                                'extraguest' => $item['ExtraGuestsConfig'],
                                'min_price' => $item['MinRoomRate'],
                                'meals' => $this->Util->getMeals($item['MealsIncluded']),
                            ];
                            $datarateplansave = $this->Channelrateplanes->newEntity($datarateplan);
                            $this->Channelrateplanes->save($datarateplansave);
                        }
                    }
                }
            }
            $rooms = $this->Channelrooms->find()->where(['hotel_id' => $id]);
        }
        $rooms = $this->paginate($rooms);
        $this->set(compact('rooms', 'hotel'));
    }
    public function editChannel($id = null){
        $this->loadModel('Channelrooms');
        $room = $this->Channelrooms->get($id);
        $images = [];
        if ($room->media) {
            $medias = json_decode($room->media, true);
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
            $hotel = $this->Channelrooms->get($room->id);
            $data['thumbnail'] = json_decode($data['media'], true)[0];
            $room = $this->Channelrooms->patchEntity($room, $data);
            if ($this->Channelrooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));
                return $this->redirect(['action' => 'listRoomChannel', $room->hotel_id]);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $this->set(compact('room', 'list_images'));
    }
    public function rateplaneChannel($id = null){
        $this->loadModel('Channelrateplanes');
        $this->loadModel('Channelrooms');
        $room = $this->Channelrooms->get($id);
        $rateplanes = $this->Channelrateplanes->find()->where(['channel_room_id' => $id])->all();
//        dd($rateplanes);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            foreach ($data['rateplane'] as $key => $item){
                $rateplan = $this->Channelrateplanes->find()->where(['id' => $key])->first();
                $rateplan = $this->Channelrooms->patchEntity($rateplan, $item);
                $this->Channelrateplanes->save($rateplan);
            }
            return $this->redirect(['action' => 'rateplaneChannel', $room->id]);
        }
        $this->set(compact('room', 'rateplanes'));
    }

}
