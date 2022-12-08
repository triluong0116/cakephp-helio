<?php

namespace App\Controller\Editor;

use App\Controller\AppController;

/**
 * HomeStays Controller
 *
 * @property \App\Model\Table\HomeStaysTable $HomeStays
 * @property \App\Model\Table\PriceHomeStaysTable $PriceHomeStays
 * @property \App\Model\Table\LocationsTable $Locations
 *
 * @method \App\Model\Entity\HomeStay[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HomeStaysController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('HomeStays');
        $this->paginate = [
            'limit' => 20,
            'contain' => ['Locations']
        ];
        $homestays = $this->paginate($this->HomeStays);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_homestay = $this->HomeStays->find()->contain(['Locations'])->where([
                'OR' => [
                    'HomeStays.name LIKE' => '%' . $data . '%',
                    'HomeStays.description LIKE' => '%' . $data . '%',
                    'Locations.name LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_homestay->count();
            $homestays = $this->paginate($list_object_homestay);
            $this->set(compact('homestays', 'number', 'data'));
        } else
            $this->set(compact('homestays'));
    }

    /**
     * View method
     *
     * @param string|null $id Home Stay id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('HomeStays');
        $homeStay = $this->HomeStays->get($id, [
            'contain' => ['Locations', 'Categories', 'PriceHomeStays']
        ]);

        $this->set('homeStay', $homeStay);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('HomeStays');
        $this->loadModel('Categories');
        $this->loadModel('Rooms');
        $this->loadModel('PriceHomeStays');

        $listHouse = [APARTMENT => 'Chung cư', VILLA => 'Biệt thự', HOME => 'Nhà riêng', BUNGALOW => 'Bungalow'];
        $typeHouse = [SINGLE_ROOM => 'Phòng riêng', WHOLE_HOUSE => 'Nguyên căn'];

        $homestay = $this->HomeStays->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($data['list_email'])) {
                if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                    $data['list_icon'] = array_values($data['list_icon']);
                    $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
                } else {
                    $data['icon_list'] = json_encode([]);
                }
                if (!isset($data['is_special'])) {
                    $data['is_special'] = 0;
                }
                $data['list_caption'] = array_values($data['list_caption']);
                $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);

                $data['list_email'] = array_values($data['list_email']);
                $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);

                $data['list_term'] = array_values($data['list_term']);
                $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);

                if(isset($data['list_payment'])){
                    $data['list_payment'] = array_values($data['list_payment']);
                    $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                }

                if(isset($data['contract_file'])){
                    if ($data['contract_file']['error'] == 0) {
                        $contract = $this->Upload->uploadSingle($data['contract_file']);
                        $data['contract_file'] = $contract;
                    } else {
                        unset($data['contract_file']);
                    }
                }

                if ($data['thumbnail']['error'] == 0) {
                    $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                    $data['thumbnail'] = $thumbnail;
                } else {
                    unset($data['thumbnail']);
                }


                $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                $data['price_customer'] = str_replace(',', '', $data['price_customer']);
                $homestay = $this->HomeStays->patchEntity($homestay, $data);
                if (isset($data['address'])) {
                    $geocoding = $this->Util->newGeoCoding($data['address']);
                    if (isset($geocoding[0]) && $geocoding[0]) {
                        $homestay->lat = $geocoding[0]['lat'];
                        $homestay->lon = $geocoding[0]['lon'];
                    }
                }

                if ($this->HomeStays->save($homestay)) {
                    $priceWeekDay = $this->PriceHomeStays->newEntity();
                    $priceWeekDaySaveData = [];
                    $priceWeekDaySaveData['home_stay_id'] = $homestay->id;
                    $priceWeekDaySaveData['type'] = WEEK_DAY;
                    $priceWeekDaySaveData['description'] = $data['weekday_price_description'];
                    $priceWeekDaySaveData['price'] = str_replace(',', '', $data['weekday_price']);
                    $priceWeekDay = $this->PriceHomeStays->patchEntity($priceWeekDay, $priceWeekDaySaveData);
                    $this->PriceHomeStays->save($priceWeekDay);

                    $priceWeekEnd = $this->PriceHomeStays->newEntity();
                    $priceWeekEndSaveData = [];
                    $priceWeekEndSaveData['home_stay_id'] = $homestay->id;
                    $priceWeekEndSaveData['type'] = WEEK_END;
                    $priceWeekEndSaveData['description'] = $data['weekend_price_description'];
                    $priceWeekEndSaveData['price'] = str_replace(',', '', $data['weekend_price']);
                    $priceWeekEnd = $this->PriceHomeStays->patchEntity($priceWeekEnd, $priceWeekEndSaveData);
                    $this->PriceHomeStays->save($priceWeekEnd);

                    $this->Flash->success(__('The hotel has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }


                $this->Flash->error(__('The hotel could not be saved. Please, try again.'));

            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
            }
        }

        $locations = $this->HomeStays->Locations->find('list', ['limit' => 200]);
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
//        dd($ultilities->toArray());
        $this->set(compact('homestay', 'locations', 'ultilities', 'listHouse', 'typeHouse'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Home Stay id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('HomeStays');
        $this->loadModel('Categories');
        $this->loadModel('PriceHomeStays');
        $homestay = $this->HomeStays->get($id, [
            'contain' => ['Locations', 'Categories', 'PriceHomeStays']
        ]);
        $listHouse = [APARTMENT => 'Chung cư', VILLA => 'Biệt thự', HOME => 'Nhà riêng', BUNGALOW => 'Bungalow'];
        $typeHouse = [SINGLE_ROOM => 'Phòng riêng', WHOLE_HOUSE => 'Nguyên căn'];

//        dd($homestay);
        $images = [];
        if ($homestay->media) {
            $medias = json_decode($homestay->media, true);
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
            if (isset($data['list_email']) && is_array($data['list_email'])) {
                if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                    $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
                } else {
                    $data['icon_list'] = json_encode([]);
                }
                if (!isset($data['is_special'])) {
                    $data['is_special'] = 0;
                }
                $data['list_caption'] = array_values($data['list_caption']);
                $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);

                $data['list_email'] = array_values($data['list_email']);
                $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);

                $data['list_term'] = array_values($data['list_term']);
                $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);

                if(isset($data['list_payment'])){
                    $data['list_payment'] = array_values($data['list_payment']);
                    $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                }
                unset($data['reservation']);

                if(isset($data['contract_file'])){
                    if ($data['contract_file']['error'] == 0) {
                        $contract = $this->Upload->uploadSingle($data['contract_file']);
                        $data['contract_file'] = $contract;
                    } else {
                        $data['contract_file'] = $data['contract_file_edit'];
                    }
                }

                if ($data['thumbnail']['error'] == 0) {
                    $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                    $data['thumbnail'] = $thumbnail;
                } else {
                    $data['thumbnail'] = $data['thumbnail_edit'];
                }
                $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                $data['price_customer'] = str_replace(',', '', $data['price_customer']);
                $homestay = $this->HomeStays->patchEntity($homestay, $data);
                if (isset($data['address'])) {
                    $geocoding = $this->Util->newGeoCoding($data['address']);
                    if (isset($geocoding[0]) && $geocoding[0]) {
                        $homestay->lat = $geocoding[0]['lat'];
                        $homestay->lon = $geocoding[0]['lon'];
                    }
                }

                if ($this->HomeStays->save($homestay)) {

                    $priceHomeStays = $this->PriceHomeStays->find()->where(['home_stay_id' => $homestay->id]);
                    foreach ($priceHomeStays as $price) {
                        if ($price->type == WEEK_DAY) {
                            $price = $this->PriceHomeStays->patchEntity($price, ['price' => str_replace(',', '', $data['weekday_price'])
                                , 'description' => $data['weekday_price_description']]);
                            $this->PriceHomeStays->save($price);
                        } else if ($price->type == WEEK_END) {
                            $price = $this->PriceHomeStays->patchEntity($price, ['price' => str_replace(',', '', $data['weekend_price'])
                                , 'description' => $data['weekend_price_description']]);
                            $this->PriceHomeStays->save($price);
                        }
                    }

                    $this->Flash->success(__('The hotel has been saved.'));
                    return $this->redirect(['controller' => 'home_stays', 'action' => 'index']);
                }
                $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
            }
        }


        $locations = $this->HomeStays->Locations->find('list', ['limit' => 200]);
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
        $this->set(compact('homestay', 'locations', 'ultilities', 'list_images', 'listHouse', 'typeHouse'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Home Stay id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


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
}
