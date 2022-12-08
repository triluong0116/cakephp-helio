<?php

namespace App\Controller\Agency;

use App\Controller\AppController;

/**
 * Vouchers Controller
 *
 * @property \App\Model\Table\VouchersTable $Vouchers
 *
 * @method \App\Model\Entity\Voucher[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VouchersController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Departures', 'Destinations']
        ];
        $vouchers = $this->paginate($this->Vouchers);
          
         if($this->request->is('get') && $this->request->getQuery('search')){
            $data = trim($this->request->getQuery('search'));
            $list_object_vouchers = $this->Vouchers->find()->where([
                'OR' => [
                    'Vouchers.name LIKE' => '%'.$data.'%',
                ]
            ]);
         
            $number = $list_object_vouchers->count();
            $vouchers = $this->paginate($list_object_vouchers);
            $this->set(compact('vouchers', 'number', 'data'));
            $this->render('search');
        } else $this->set(compact('vouchers'));
         
    }

    /**
     * View method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $voucher = $this->Vouchers->get($id, [
            'contain' => ['Departures', 'Destinations', 'Rooms']
        ]);

        $this->set('voucher', $voucher);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $voucher = $this->Vouchers->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($data['rooms']) && isset($data['rooms']['_ids']) && !empty($data['rooms']['_ids'])) {
                if ($this->Util->checkDuplicateHotel($data['hotel']) == false) {
                    if (isset($data['list_caption']) && count($data['list_caption']) > 0) {
                        $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                        $data['price'] = str_replace(',', '', $data['price']);
                        $data['trippal_price'] = str_replace(',', '', $data['trippal_price']);
                        $data['customer_price'] = str_replace(',', '', $data['customer_price']);

                        $date_array = explode(' - ', $data['reservation']);
                        $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                        $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

                        if ($data['thumbnail']['error'] == 0) {
                            $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                            $data['thumbnail'] = $thumbnail;
                        } else {
                            $data['thumbnail'] = '';
                        }
                        $data['user_id'] = $this->Auth->user('id');
                        $voucher = $this->Vouchers->patchEntity($voucher, $data);
                        if ($this->Vouchers->save($voucher)) {
                            $this->Flash->success(__('The voucher has been saved.'));

                            return $this->redirect(['action' => 'index']);
                        }

                        $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
                    } else {
                        $this->Flash->error(__('Phải nhập ít nhất 1 mô tả'));
                    }
                } else {
                    $this->Flash->error(__('Không được chọn trùng khách sạn'));
                }
            } else {
                $this->Flash->error(__('Phải chọn ít nhất 1 phòng khách sạn'));
            }
        }
        $users = $this->Vouchers->Users->find('list', ['limit' => 200]);
        $departures = $this->Vouchers->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Vouchers->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Vouchers->Rooms->find('list', ['limit' => 200]);
        $this->set(compact('voucher', 'users', 'departures', 'destinations', 'rooms', 'hotels'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $this->loadModel('Hotels');
        $voucher = $this->Vouchers->get($id, [
            'contain' => ['Rooms', 'Rooms.Hotels']
        ]);
        $images = [];
        if ($voucher->media) {
            $medias = json_decode($voucher->media, true);
            foreach ($medias as $media) {
                $obj['name'] = basename($media);
                $obj['size'] = filesize($media);
                $images[] = $obj;
            }
        }
        $list_images = json_encode($images);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (isset($data['rooms']) && isset($data['rooms']['_ids']) && !empty($data['rooms']['_ids'])) {
                if ($this->Util->checkDuplicateHotel($data['hotel']) == false) {
                    if (isset($data['list_caption']) && count($data['list_caption']) > 0) {
                        $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                        $data['price'] = str_replace(',', '', $data['price']);
                        $data['trippal_price'] = str_replace(',', '', $data['trippal_price']);
                        $data['customer_price'] = str_replace(',', '', $data['customer_price']);

                        $date_array = explode(' - ', $data['reservation']);
                        $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                        $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

                        if ($data['thumbnail']['error'] == 0) {
                            $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                            $data['thumbnail'] = $thumbnail;
                        } else {
                            $data['thumbnail'] = $data['thumbnail_edit'];
                        }
                        $data['user_id'] = $this->Auth->user('id');
//                        dd($data);
                        $voucher = $this->Vouchers->patchEntity($voucher, $data);
                        if ($this->Vouchers->save($voucher)) {
                            $this->Flash->success(__('The voucher has been saved.'));

                            return $this->redirect(['action' => 'index']);
                        }
                        $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
                    } else {
                        $this->Flash->error(__('Phải nhập ít nhất 1 mô tả'));
                    }
                } else {
                    $this->Flash->error(__('Không được chọn trùng khách sạn'));
                }
            } else {
                $this->Flash->error(__('Phải chọn ít nhất 1 phòng khách sạn'));
            }
        }
        $users = $this->Vouchers->Users->find('list', ['limit' => 200]);
        $departures = $this->Vouchers->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Vouchers->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Vouchers->Rooms->find('list', ['limit' => 200]);
        $hotels = $this->Hotels->find('list');
        $this->set(compact('voucher', 'users', 'departures', 'destinations', 'rooms', 'list_images', 'hotels'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $voucher = $this->Vouchers->get($id);
        if ($this->Vouchers->delete($voucher)) {
            $this->Flash->success(__('The voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function isAuthorized($user) {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 3)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'agency'));
        return parent::isAuthorized($user);
    }

}
