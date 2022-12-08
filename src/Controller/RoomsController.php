<?php

namespace App\Controller;

/**
 * Rooms Controller
 *
 * @property \App\Model\Table\RoomsTable $Rooms
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\LandTourUserPricesTable $LandTourUserPrices
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
        $this->paginate = [
            'contain' => ['Hotels']
        ];
        $rooms = $this->paginate($this->Rooms);

        $this->set(compact('rooms'));
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
        $room = $this->Rooms->get($id, [
            'contain' => ['Hotels', 'Combos', 'Categories']
        ]);

        $this->set('room', $room);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $room = $this->Rooms->newEntity();
        if ($this->request->is('post')) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
            if ($this->Rooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $hotels = $this->Rooms->Hotels->find('list', ['limit' => 200]);
        $combos = $this->Rooms->Combos->find('list', ['limit' => 200]);
        $categories = $this->Rooms->Categories->find('list', ['limit' => 200]);
        $this->set(compact('room', 'hotels', 'combos', 'categories'));
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
        $room = $this->Rooms->get($id, [
            'contain' => ['Combos', 'Categories']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
            if ($this->Rooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $hotels = $this->Rooms->Hotels->find('list', ['limit' => 200]);
        $combos = $this->Rooms->Combos->find('list', ['limit' => 200]);
        $categories = $this->Rooms->Categories->find('list', ['limit' => 200]);
        $this->set(compact('room', 'hotels', 'combos', 'categories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $room = $this->Rooms->get($id);
        if ($this->Rooms->delete($room)) {
            $this->Flash->success(__('The room has been deleted.'));
        } else {
            $this->Flash->error(__('The room could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function addRoomForCombo()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Hotels');
        $hotels = $this->Hotels->find('list');
        $this->set(compact('hotels'));
        $this->render('add_room_for_combo')->body();
    }

    public function getRoomByHotel($hotel_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $rooms = $this->Rooms->find('list')->where(['hotel_id' => $hotel_id]);
        $room_id = $this->request->getQuery('room_id');

        $this->set(compact('rooms', 'room_id', 'icons'));
        $this->render('get_room_by_hotel')->body();
    }

    public function getHotelPriceRoom($hotel_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Rooms');
        $rooms = $this->Rooms->find()->where(['hotel_id' => $hotel_id])->toArray();
        $dataEdit = [];
        if (!empty($rooms)) {
            foreach ($rooms as $room) {
                $prices = json_decode($room['list_price']);
                if (is_array($prices)) {
                    foreach ($prices as $price) {
                        $dates = explode(' - ', $price->dates);
                        $dataEdit[$price->dates]['start_date'] = $dates[0];
                        $dataEdit[$price->dates]['end_date'] = $dates[1];
                        $newItem = [
                            'name' => $room->name,
                            'weekday' => isset($price->weekday) ? $price->weekday : 0,
                            'weekend' => isset($price->weekend) ? $price->weekend : 0,
                            'room_id' => $room->id,
                            'price_agency' => isset($price->price_agency) ? $price->price_agency : 0,
                            'price_customer' => isset($price->price_customer) ? $price->price_customer : 0,
                        ];
                        $dataEdit[$price->dates]['items'][] = $newItem;
                    }
                }
            }
        }
        foreach ($rooms as $room) {
            foreach ($dataEdit as $key => $element) {
                $isSet = false;
                foreach ($element['items'] as $item) {
                    if ($item['room_id'] == $room->id) {
                        $isSet = true;
                        break;
                    }
                }
                if (!$isSet) {
                    $newItem = [
                        'name' => $room->name,
                        'weekday' => 0,
                        'weekend' => 0,
                        'room_id' => $room->id
                    ];
                    $dataEdit[$key]['items'][] = $newItem;
                }
            }
         }
        $this->set(compact('dataEdit', 'rooms'));
        $this->render('get_hotel_price_room');
    }

    public function getListRevenueLandtour($landtour_id)
    {
        $this->loadModel('LandTourUserPrices');
        $this->loadModel('Users');
        $this->viewBuilder()->enableAutoLayout(false);

        $listPrice = [];
        $condition = [];
        if($this->Auth->user('role_id') == 2){
            $userId = $this->Auth->user('id');
            $condition['parent_id'] = $userId;
        }
        $listAgencyId = $this->Users->find('list', ['keyField' => 'id'])->select('id')->where($condition)->toArray();
        $listAgency = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'screen_name'
        ])->where($condition);
        $listRevenue = $this->LandTourUserPrices->find()->where(['user_id IN' => $listAgencyId, 'land_tour_id' => $landtour_id]);
        foreach ($listRevenue as $revenue){
            if(!isset($listPrice[$revenue->price])){
                $listPrice[$revenue->price]['user_id'][] = $revenue->user_id;
            } else {
                array_push($listPrice[$revenue->price]['user_id'], $revenue->user_id);
            }
        }
        $this->set(compact('listPrice', 'listAgency'));
    }

    public function addAgencyRevenueLandtour(){
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Users');

        $condition = [];
        if($this->Auth->user('role_id') == 5){
            $userId = $this->Auth->user('id');
            $condition['landtour_parent_id'] = $userId;
        }
        $listAgency = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'screen_name'
        ])->where($condition);
        $this->set(compact(['listAgency']));
    }

    public function addRoomPrice($hotel_id)
    {
        $rooms = $this->Rooms->find()->where(['hotel_id' => $hotel_id]);
        $this->viewBuilder()->enableAutoLayout(false);
        $this->set(compact('rooms'));
        $this->render('add_price_for_room');
    }
    public function addPriceHotel($hotel_id)
    {
        $rooms = $this->Rooms->find()->where(['hotel_id' => $hotel_id]);
        $this->viewBuilder()->enableAutoLayout(false);
        $this->set(compact('rooms'));
        $this->render('add_price_all_room_in_hotel');
    }

    public function addCaptionForCombo()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_caption_for_combo')->body();
    }

    public function addVinCaption()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_vin_caption')->body();
    }

    public function addVinExtends() {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_vin_extends')->body();
    }

    public function addEmail()
    {
        $this->viewBuilder()->enableAutoLayout(false);

        $this->render('add_email')->body();
    }

    public function addIcon()
    {
        $this->viewBuilder()->enableAutoLayout(false);

        $this->render('add_icon')->body();
    }

    public function addRoom()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_room')->body();
    }

    public function addPayment()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_payment')->body();
    }

    public function addTerm()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_term');
    }

    public function addRoomPriceByDate()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        if ($this->request->is('ajax')) {
            $data = $this->request->getQuery();
            $timestamp = $data['timestamp'];
            $this->set(compact('timestamp'));
        }
        $this->render('add_room_price_by_date')->body();
    }

    public function addItemForReview()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_item_for_review')->body();
    }

    public function addHomestayPrice()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_homestay_price')->body();
    }

    public function addHoliday() {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_holiday')->body();
    }

    public function addSurcharge() {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Surcharges');
        $surcharges = $this->Util->createListSurcharge();
        $this->set(compact('surcharges'));
    }

    public function addCustomSurcharge() {
        $this->viewBuilder()->enableAutoLayout(false);
        $type = $this->getRequest()->getQuery('type');
        $checkIn = $this->getRequest()->getQuery('checkIn');
        $checkOut = $this->getRequest()->getQuery('checkOut');
        $lastValue = $this->getRequest()->getQuery('lastValue');

        $newValue = '';
        if ($lastValue) {
            if ($type == SUR_CHILDREN) {
                $newValue = intval($lastValue) + 1;
            }
            if ($type == SUR_CHECKIN_SOON) {
                $time = strtotime("-1 minutes", strtotime($lastValue));
                $newValue = date('H:i', $time);
            }
            if ($type == SUR_CHECKOUT_LATE) {
                $time = strtotime("+1 minutes", strtotime($lastValue));
                $newValue = date('H:i', $time);
            }
        } else {
            if ($type == SUR_CHILDREN) {
                $newValue = 0;
            }
            if ($type == SUR_CHECKIN_SOON) {
                $newValue = $checkIn;
            }
            if ($type == SUR_CHECKOUT_LATE) {
                $newValue = $checkOut;
            }
        }


        $this->set(compact('type', 'checkIn', 'checkOut', 'newValue'));
    }

    public function addPriceByAge(){
        $this->viewBuilder()->enableAutoLayout(false);
        if($this->request->is('ajax')){
            $data = $this->request->getQuery();
            if(isset($data['lastValue']) && is_numeric($data['lastValue'])){
                $lastValue = $data['lastValue'] + 1;
            } else {
                $lastValue = 0;
            }
            $this->set(compact('lastValue'));
        }
    }
    public function addLandtourAccessory(){
        $this->viewBuilder()->enableAutoLayout(false);
    }
    public function addLandtourDriveSurchage(){
        $this->viewBuilder()->enableAutoLayout(false);
    }
}
