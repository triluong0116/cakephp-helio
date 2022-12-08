<?php

namespace App\Controller\Sale;

use App\Controller\AppController;

/**
 * LandTours Controller
 *
 * @property \App\Model\Table\LandToursTable $LandTours
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\LandTourUserPricesTable $LandTourUserPrices
 *
 * @method \App\Model\Entity\LandTour[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LandToursController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Users', 'Departures', 'Destinations']
        ];
        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_landTours = $this->LandTours->find()->where([
                'OR' => [
                    'LandTours.name LIKE' => '%' . $data . '%',
                    'LandTours.caption LIKE' => '%' . $data . '%',
                    'LandTours.description LIKE' => '%' . $data . '%',
                ]
            ]);
            $number = $list_object_landTours->count();
            $landTours = $this->paginate($list_object_landTours);
            $this->set(compact('landTours', 'number', 'data'));
        } else {
            $landTours = $this->paginate($this->LandTours);
            $this->set(compact('landTours'));
        }
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
            $data = $this->request->getData();
            if (isset($data['list_caption']) && count($data['list_caption']) > 0) {
                if (isset($data['list_email'])) {
                    if(isset($data['list_caption']) && !empty($data['list_caption'])){
                        $data['list_caption'] = array_values($data['list_caption']);
                        $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                    }

//                    if(isset($data['list_term']) && !empty($data['list_term'])){
//                        $data['list_term'] = array_values($data['list_term']);
//                        $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
//                    }

                    if(isset($data['description_type'])){
                        $peopleDescription = [];
                        $peopleDescription['description_type'] = $data['description_type'];
                        $peopleDescription['adult_description'] = isset($data['adult_description']) ? $data['adult_description'] : "";
                        $peopleDescription['child_description'] = isset($data['child_description']) ? $data['child_description'] : "";
                        $peopleDescription['kid_description'] = isset($data['kid_description']) ? $data['kid_description'] : "";
                    }
                    unset($data['description_type']);
                    unset($data['adult_description']);
                    unset($data['child_description']);
                    unset($data['kid_description']);
                    $data['people_description'] = json_encode($peopleDescription);
                    if(isset($data['list_email']) && !empty($data['list_email'])){
                        $data['list_email'] = array_values($data['list_email']);
                        $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    }
                    if (isset($data['list_payment'])) {
                        $data['list_payment'] = array_values($data['list_payment']);
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                    }
                    if (isset($data['list_term'])) {
                        $data['list_term'] = array_values($data['list_term']);
                        $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['term'] = '';
                    }
                    if(isset($data['land_tour_drivesurchages']) && !empty($data['land_tour_drivesurchages'])){
                        foreach ($data['land_tour_drivesurchages'] as $k => $val){
                            $data['land_tour_drivesurchages'][$k]['price_adult'] = str_replace(',', '', $val['price_adult']);
                            $data['land_tour_drivesurchages'][$k]['price_crowd'] = str_replace(',', '', $val['price_crowd']);
                        }
                    }

                    $data['price'] = str_replace(',', '', $data['price']);
                    $data['trippal_price'] = str_replace(',', '', $data['trippal_price']);
                    $data['customer_price'] = str_replace(',', '', $data['customer_price']);

                    $date_array = explode(' - ', $data['reservation']);
                    $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                    $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

//                    if ($data['contract_file']['error'] == 0) {
//                        $contract = $this->Upload->uploadSingle($data['contract_file']);
//                        $data['contract_file'] = $contract;
//                    } else {
//                        $data['contract_file'] = '';
//                    }

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = '';
                    }
                    if(isset($data['promotion'])){
                        if ($data['promotion']['error'] == 0) {
                            $thumbnail = $this->Upload->uploadSingle($data['promotion']);
                            $data['promotion'] = $thumbnail;
                        } else {
                            $data['promotion'] = '';
                        }
                    }
//                    foreach ($data['land_tour_surcharges'] as $surchaseKey => $surcharge) {
//                        foreach ($surcharge['options'] as $key => $singleFee) {
//                            $data['land_tour_surcharges'][$surchaseKey]['options'][$key]['price'] = str_replace(',', '', $singleFee['price']);
//                        }
//                        $data['land_tour_surcharges'][$surchaseKey]['options'] = json_encode($data['land_tour_surcharges'][$surchaseKey]['options'], JSON_UNESCAPED_UNICODE);
//                    }
                    $data['user_id'] = $this->Auth->user('id');
                    $landTour = $this->LandTours->patchEntity($landTour, $data);
                    if ($this->LandTours->save($landTour)) {
                        $this->Flash->success(__('The land tour has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The land tour could not be saved. Please, try again.'));
                } else {

                }
            } else {
                $this->Flash->error(__('Phải nhập ít nhất 1 mô tả'));
            }
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
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $landTour = $this->LandTours->get($id, [
            'contain' => ['LandTourAccessories', 'LandTourDrivesurchages']
        ]);
//        dd($landTour);
        $start_date = $landTour->start_date->format('d/m/Y');
        $end_date = $landTour->end_date->format('d/m/Y');
        $date = $start_date. " - " . $end_date;
        $images = [];
        if ($landTour->media) {
            $medias = json_decode($landTour->media, true);
            foreach ($medias as $media) {
                $obj['name'] = basename($media);
                $obj['size'] = filesize($media);
                $images[] = $obj;
            }
        }
        $list_images = json_encode($images);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (isset($data['list_caption']) && count($data['list_caption']) > 0) {
                if (isset($data['list_email']) && is_array($data['list_email'])) {
                    if(isset($data['list_caption']) && !empty($data['list_caption'])){
                        $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                    }
//                    if(isset($data['list_term']) && !empty($data['list_term'])){
//                        $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
//                    }
                    if(isset($data['list_email']) && !empty($data['list_email'])){
                        $data['email'] = array_values($data['list_email']);
                        $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    }
                    if (isset($data['list_payment'])) {
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
                    }
                    if (isset($data['list_term'])) {
                        $data['list_term'] = array_values($data['list_term']);
                        $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['term'] = '';
                    }

                    if(isset($data['description_type'])){
                        $peopleDescription = [];
                        $peopleDescription['description_type'] = $data['description_type'];
                        $peopleDescription['adult_description'] = isset($data['adult_description']) ? $data['adult_description'] : "";
                        $peopleDescription['child_description'] = isset($data['child_description']) ? $data['child_description'] : "";
                        $peopleDescription['kid_description'] = isset($data['kid_description']) ? $data['kid_description'] : "";
                    }
                    unset($data['description_type']);
                    unset($data['adult_description']);
                    unset($data['child_description']);
                    unset($data['kid_description']);
                    $data['people_description'] = json_encode($peopleDescription);

                    $data['price'] = str_replace(',', '', $data['price']);
                    $data['trippal_price'] = str_replace(',', '', $data['trippal_price']);
                    $data['customer_price'] = str_replace(',', '', $data['customer_price']);

                    $date_array = explode(' - ', $data['reservation']);
                    $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                    $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

//                    if ($data['contract_file']['error'] == 0) {
//                        $contract = $this->Upload->uploadSingle($data['contract_file']);
//                        $data['contract_file'] = $contract;
//                    } else {
//                        $data['contract_file'] = $data['contract_file_edit'];
//                    }

                    if ($data['thumbnail']['error'] == 0) {
                        $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                        $data['thumbnail'] = $thumbnail;
                    } else {
                        $data['thumbnail'] = $data['thumbnail_edit'];
                    }
                    if(isset($data['promotion']) && !empty($data['promotion'])){
                        if ($data['promotion']['error'] == 0) {
                            $thumbnail = $this->Upload->uploadSingle($data['promotion']);
                            $data['promotion'] = $thumbnail;
                        } else {
                            $data['promotion'] = $data['promotion_edit'];
                        }
                    } else {
                        $data['promotion'] = $data['promotion_edit'];
                    }
                    if(isset($data['land_tour_drivesurchages']) && !empty($data['land_tour_drivesurchages'])){
                        foreach ($data['land_tour_drivesurchages'] as $k => $val){
                            $data['land_tour_drivesurchages'][$k]['price_adult'] = str_replace(',', '', $val['price_adult']);
                            $data['land_tour_drivesurchages'][$k]['price_crowd'] = str_replace(',', '', $val['price_crowd']);
                        }
                    }
//                    foreach ($data['land_tour_surcharges'] as $surchaseKey => $surcharge) {
//                        foreach ($surcharge['options'] as $key => $singleFee) {
//                            $data['land_tour_surcharges'][$surchaseKey]['options'][$key]['price'] = str_replace(',', '', $singleFee['price']);
//                        }
//                        $data['land_tour_surcharges'][$surchaseKey]['options'] = json_encode($data['land_tour_surcharges'][$surchaseKey]['options'], JSON_UNESCAPED_UNICODE);
//                    }
                    $landTour = $this->LandTours->patchEntity($landTour, $data);
                    if ($this->LandTours->save($landTour)) {
                        $this->Flash->success(__('The land tour has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The land tour could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
                }
            }
        }
        $users = $this->LandTours->Users->find('list', ['limit' => 200]);
        $departures = $this->LandTours->Departures->find('list', ['limit' => 200]);
        $destinations = $this->LandTours->Destinations->find('list', ['limit' => 200]);
        $this->set(compact('landTour', 'users', 'departures', 'destinations', 'list_images','date'));
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
            $this->LandTours->updateAll(['is_feature' => 1], ['id IN' => $data['ids']]);
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
            $this->LandTours->updateAll(['is_feature' => 0], ['id IN' => $data['ids']]);
        }
        $response['success'] = true;

        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public  function  addLandtourAgencyPrice(){
        $this->loadModel('Users');
        $this->loadModel('LandTourUserPrices');
        $listLandtours = $this->LandTours->find();
        if($this->request->is('post')){
            $data = $this->request->getData();
            $landTour = $this->LandTours->get($data['landtour_id'], [
                'contain' => ['LandTourUserPrices']
            ]);
            $LandTourUserPrices['land_tour_user_prices'] = [];
            foreach($data['agency_revenue'] as $k => $revenue){
                $price = str_replace(',', '', $revenue['revenue']);
                foreach ($revenue['user_id'] as $key => $userId){
                    $var = $this->LandTourUserPrices->find()->where([
                        'user_id' => $userId,
                        'land_tour_id' => $data['landtour_id']
                    ])->first();
                    if($var){
                        $LandTourUserPrices['land_tour_user_prices'][] = [
                            'price' => $price,
                            'user_id' => $userId,
                            'id' => $var->id
                        ];
                    } else {
                        $LandTourUserPrices['land_tour_user_prices'][] = [
                            'price' => $price,
                            'user_id' => $userId,
                        ];
                    }

                }
            }


            $landtour = $this->LandTours->patchEntity($landTour, $LandTourUserPrices);
            $this->LandTours->save($landTour);
        }

        $this->set(compact(['listLandtours']));
    }
}
