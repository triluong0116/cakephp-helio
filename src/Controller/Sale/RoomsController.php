<?php

namespace App\Controller\Sale;

use App\Controller\AppController;

/**
 * Rooms Controller
 *
 * @property \App\Model\Table\RoomsTable $Rooms
 *
 * @method \App\Model\Entity\Room[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RoomsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Hotels']
        ];
        $rooms = $this->paginate($this->Rooms);
        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_rooms = $this->Rooms->find()->contain(['Hotels'])->where([
                'OR' => [
                    'Hotels.name LIKE' => '%' . $data . '%',
                    'Rooms.name LIKE' => '%' . $data . '%',
                    'Rooms.area' => $data,
                    'Rooms.num_bed' => $data
                ]
            ]);
            $number = $list_object_rooms->count();
            $rooms = $this->paginate($list_object_rooms);
            $this->set(compact('rooms', 'number', 'data'));
        } else
            $this->set(compact('rooms'));
    }

    public function listRoom($id = null){
        $this->loadModel('Hotels');
        $hotel = $this->Hotels->find()->where(['id'=>$id])->first();
        $this->paginate = [
            'limit' => 10
        ];
        $rooms = $this->Rooms->find()->where(['hotel_id'=>$id]);
        $rooms = $this->paginate($rooms);
        $this->set(compact('rooms','hotel'));
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
                $data['hotel_id']= $hotel_id;
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
            return $this->redirect(['controller' => 'hotels','action' => 'index']);
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
            if(!isset($data['have_breakfast'])){
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

    /**
     * Delete method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
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

    public function isAuthorized($user) {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 2 || $user['role_id'] === 5)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }

}
