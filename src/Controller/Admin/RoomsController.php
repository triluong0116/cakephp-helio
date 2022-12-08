<?php

namespace App\Controller\Admin;

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

    /**
     * View method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $room = $this->Rooms->get($id, [
            'contain' => ['Hotels', 'Combos', 'Categories', 'PriceRooms']
        ]);

        $this->set('room', $room);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $room = $this->Rooms->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($data['reservation']) && !empty($data['reservation'])) {
                $reservations = $data['reservation'];
                unset($data['reservation']);
                if ($data['thumbnail']['error'] == 0) {
                    $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                    $data['thumbnail'] = $thumbnail;
                } else {
                    unset($data['thumbnail']);
                }
                $room = $this->Rooms->patchEntity($room, $data);
                if ($this->Rooms->save($room)) {
                    foreach ($reservations as $reservation) {
                        $data_price_room = [
                            'room_id' => $room->id
                        ];
                        $data_price_room['price'] = str_replace(',', '', $reservation['price']);
                        $date_array = explode(' - ', $reservation['date']);
                        $data_price_room['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                        $data_price_room['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

                        $price_room = $this->Rooms->PriceRooms->patchEntity($this->Rooms->PriceRooms->newEntity(), $data_price_room);
                        $this->Rooms->PriceRooms->save($price_room);
                    }
                    $this->Flash->success(__('The room has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The room could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('Phải nhập ít nhất một Giá cho phòng khách sạn'));
            }
        }
        $hotels = $this->Rooms->Hotels->find('list', ['limit' => 200]);
        $combos = $this->Rooms->Combos->find('list', ['limit' => 200]);
        $categories = $this->Rooms->Categories->find('list', ['limit' => 200]);
        $ultilities = $this->Rooms->Categories->find('list')->where(['parent_id' => 1]);
        $this->set(compact('room', 'hotels', 'combos', 'categories', 'ultilities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $this->loadModel('RoomsCategories');
        $room = $this->Rooms->get($id, [
            'contain' => ['Categories', 'PriceRooms']
        ]);
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
            $reservations = array_key_exists("reservation", $data) ? $data['reservation'] : [];
            if ($data['reservation']) {
                if ($data['thumbnail']['error'] == 0) {
                    $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                    $data['thumbnail'] = $thumbnail;
                } else {
                    $data['thumbnail'] = $data['thumbnail_edit'];
                }
                $room = $this->Rooms->patchEntity($room, $data);
                if ($this->Rooms->save($room)) {
                    foreach ($reservations as $reservation) {
                        if ($this->Rooms->PriceRooms->exists(['id' => $reservation['id']])) {
                            $price_room = $this->Rooms->PriceRooms->find()->where(['id' => $reservation['id']])->first();
                        } else {
                            $price_room = $this->Rooms->PriceRooms->newEntity();
                        }
                        $data_price_room = [
                            'room_id' => $room->id
                        ];
                        $data_price_room['price'] = str_replace(',', '', $reservation['price']);
                        $date_array = explode(' - ', $reservation['date']);
                        $data_price_room['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                        $data_price_room['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

                        $price_room = $this->Rooms->PriceRooms->patchEntity($price_room, $data_price_room);
                        $this->Rooms->PriceRooms->save($price_room);
                    }
                    $this->Flash->success(__('The room has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The room could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('Phải nhập ít nhất một Giá cho phòng khách sạn'));
            }
        }
        $hotels = $this->Rooms->Hotels->find('list', ['limit' => 200]);
        $combos = $this->Rooms->Combos->find('list', ['limit' => 200]);
        $categories = $this->Rooms->Categories->find('list', ['limit' => 200]);
        $ultilities = $this->Rooms->Categories->find('list')->where(['parent_id' => 1]);
        $selecteds = $this->RoomsCategories->find()->where(['room_id' => $id])->extract('category_id')->toArray();
        $this->set(compact('room', 'hotels', 'combos', 'categories', 'ultilities', 'list_images', 'selecteds'));
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
        if ($this->Rooms->delete($room)) {
            $this->Flash->success(__('The room has been deleted.'));
        } else {
            $this->Flash->error(__('The room could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
