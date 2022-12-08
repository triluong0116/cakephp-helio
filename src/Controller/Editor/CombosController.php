<?php

namespace App\Controller\Editor;

use App\Controller\AppController;
use Cake\Utility\Hash;

/**
 * Combos Controller
 *
 * @property \App\Model\Table\CombosTable $Combos
 *
 * @method \App\Model\Entity\Combo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CombosController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Departures', 'Destinations']
        ];
        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_combos = $this->Combos->find()->where([
                'OR' => [
                    'Combos.name LIKE' => '%' . $data . '%',
                    'Combos.caption LIKE' => '%' . $data . '%',
                    'Combos.description LIKE' => '%' . $data . '%',
                ]
            ]);
            $number = $list_object_combos->count();
            $combos = $this->paginate($list_object_combos);
            $this->set(compact('combos', 'number', 'data'));
        } else
            $combos = $this->paginate($this->Combos);
        $this->set(compact('combos'));
    }

    /**
     * View method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $combo = $this->Combos->get($id, [
            'contain' => ['Departures', 'Destinations']
        ]);

        $this->set('combo', $combo);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->loadModel('Hotels');
        $combo = $this->Combos->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();            
            $data['addition_fee'] = str_replace(',', '', $data['addition_fee']);
            if (isset($data['hotels']) && count($data['hotels']) > 0) {
                if (count($data['hotels']) <= 2) {
                    $hotelIds = Hash::extract($data['hotels'], '{n}.id');
                    if ($this->checkDuplicateHotel($hotelIds) == false) {
                        if (isset($data['list_caption']) && count($data['list_caption']) > 0) {
                            $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                            if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                                $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
                            } else {
                                $data['icon_list'] = json_encode([]);
                            }
                            if ($data['thumbnail']['error'] == 0) {
                                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                                $data['thumbnail'] = $thumbnail;
                            } else {
                                $data['thumbnail'] = '';
                            }
                            $combo = $this->Combos->patchEntity($combo, $data);
//                            dd($combo);
                            if ($this->Combos->save($combo)) {
                                $this->Flash->success(__('The combo has been saved.'));

                                return $this->redirect(['action' => 'index']);
                            }
                            $this->Flash->error(__('The combo could not be saved. Please, try again.'));
                        } else {
                            $this->Flash->error(__('Phải nhập ít nhất 1 mô tả'));
                        }
                    } else {
                        $this->Flash->error(__('Không được chọn trùng khách sạn'));
                    }
                } else {
                    $this->Flash->error(__('Không được chọn quá 2 Khách sạn'));
                }
            } else {
                $this->Flash->error(__('Phải chọn ít nhất 1 phòng khách sạn'));
            }
        }
        $departures = $this->Combos->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Combos->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Combos->Rooms->find('list', ['limit' => 200]);
        $this->set(compact('combo', 'departures', 'destinations', 'rooms', 'hotels'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $this->loadModel('Hotels');
        $combo = $this->Combos->get($id, [
            'contain' => ['Hotels']
        ]);
//        dd($combo);
        $images = [];
        if ($combo->media) {
            $medias = json_decode($combo->media, true);
            foreach ($medias as $media) {
                $obj['name'] = basename($media);
                $obj['size'] = filesize($media);
                $images[] = $obj;
            }
        }
        $list_images = json_encode($images);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['addition_fee'] = str_replace(',', '', $data['addition_fee']);
            $data['description'] = html_entity_decode($data['description']);
//            dd($data);            
            if (isset($data['hotels']) && count($data['hotels']) > 0) {
                if (count($data['hotels']) <= 2) {
                    $hotelIds = Hash::extract($data['hotels'], '{n}.id');
                    if ($this->checkDuplicateHotel($hotelIds) == false) {
                        if (isset($data['list_caption']) && count($data['list_caption']) > 0) {
                            $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                            if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                                $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
                            } else {
                                $data['icon_list'] = json_encode([]);
                            }
                            if ($data['thumbnail']['error'] == 0) {
                                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                                $data['thumbnail'] = $thumbnail;
                            } else {
                                $data['thumbnail'] = $data['thumbnail_edit'];
                            }
                            $combo = $this->Combos->patchEntity($combo, $data);
                            if ($this->Combos->save($combo)) {
                                $this->Flash->success(__('The combo has been saved.'));

                                return $this->redirect(['action' => 'index']);
                            }
                            $this->Flash->error(__('The combo could not be saved. Please, try again.'));
                        } else {
                            $this->Flash->error(__('Phải nhập ít nhất 1 mô tả'));
                        }
                    } else {
                        $this->Flash->error(__('Không được chọn trùng khách sạn'));
                    }
                } else {
                    $this->Flash->error(__('Không được chọn quá 2 Khách sạn'));
                }
            } else {
                $this->Flash->error(__('Phải chọn ít nhất 1 phòng khách sạn'));
            }
        }
        $departures = $this->Combos->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Combos->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Combos->Rooms->find('list', ['limit' => 200]);
        $hotels = $this->Hotels->find('list');
        $this->set(compact('combo', 'departures', 'destinations', 'rooms', 'list_images', 'hotels'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    private function checkDuplicateHotel($array) {
        return count($array) !== count(array_unique($array));
    }

    public function isAuthorized($user) {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 4)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }

}
