<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Hotels Controller
 *
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Hotel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HotelsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'limit' => 10,
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
    public function view($id = null) {
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Locations', 'Rooms', 'Categories']
        ]);

        $this->set('hotel', $hotel);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->loadModel('Categories');
        $hotel = $this->Hotels->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
//            dd($data);
            if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
            } else {
                $data['icon_list'] = json_encode([]);
            }
            $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
            $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
            $reservations = $data['reservation'];
            unset($data['reservation']);
            if ($data['thumbnail']['error'] == 0) {
                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                $data['thumbnail'] = $thumbnail;
            } else {
                unset($data['thumbnail']);
            }
            $data['price_agency'] = str_replace(',', '', $data['price_agency']);
            $data['price_customer'] = str_replace(',', '', $data['price_customer']);
//            dd($data);
            $hotel = $this->Hotels->patchEntity($hotel, $data);

            if (isset($data['address'])) {
                $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($data['address']) . '&key=' . MAP_API);
                $output = json_decode($geocode);
                $hotel->lat = $output->results[0]->geometry->location->lat;
                $hotel->lon = $output->results[0]->geometry->location->lng;
            }

            if ($this->Hotels->save($hotel)) {
                foreach ($reservations as $reservation) {
                    $data_price_hotel = [
                        'hotel_id' => $hotel->id
                    ];
                    $data_price_hotel['price'] = str_replace(',', '', $reservation['price']);
                    $date_array = explode(' - ', $reservation['date']);
                    $data_price_hotel['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
                    $data_price_hotel['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');

                    $price_hotel = $this->Hotels->PriceHotels->patchEntity($this->Hotels->PriceHotels->newEntity(), $data_price_hotel);
                    $this->Hotels->PriceHotels->save($price_hotel);
                }
                $this->Flash->success(__('The hotel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
        }
        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
//        dd($ultilities->toArray());
        $this->set(compact('hotel', 'locations', 'ultilities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $this->loadModel('Categories');
        $this->loadModel('HotelsCategories');
        $this->loadModel('Rooms');
        $hotel = $this->Hotels->get($id, [
            'contain' => ['Categories', 'Rooms', 'Rooms.PriceRooms']
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
                    if (isset($data['list_icon']) && count($data['list_icon']) > 0) {
                        $data['icon_list'] = json_encode($data['list_icon'], JSON_UNESCAPED_UNICODE);
                    } else {
                        $data['icon_list'] = json_encode([]);
                    }
                    if (!isset($data['is_special'])) {
                        $data['is_special'] = 0;
                    }
                    $data['caption'] = json_encode($data['list_caption'], JSON_UNESCAPED_UNICODE);
                    $data['email'] = json_encode($data['list_email'], JSON_UNESCAPED_UNICODE);
                    $data['term'] = json_encode($data['list_term'], JSON_UNESCAPED_UNICODE);
                    if(isset($data['list_payment'])){
                        $data['payment_information'] = json_encode($data['list_payment'], JSON_UNESCAPED_UNICODE);
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

                    $data['price_agency'] = str_replace(',', '', $data['price_agency']);
                    $data['price_customer'] = str_replace(',', '', $data['price_customer']);

                    if (isset($data['address'])) {
                        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($data['address']) . '&key=' . MAP_API);
                        $output = json_decode($geocode);
                        $hotel->lat = $output->results[0]->geometry->location->lat;
                        $hotel->lon = $output->results[0]->geometry->location->lng;
                    }

                    $hotel = $this->Hotels->patchEntity($hotel, $data);
                    if ($this->Hotels->save($hotel)) {
                        $this->Flash->success(__('The hotel has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('Phải thêm ít nhất 1 Email'));
            }
        }


        $locations = $this->Hotels->Locations->find('list', ['limit' => 200]);
        $ultilities = $this->Categories->find('list')->where(['parent_id' => 1]);
        $this->set(compact('hotel', 'locations', 'ultilities', 'list_images'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Hotel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $hotel = $this->Hotels->get($id);
        if ($this->Hotels->delete($hotel)) {
            $this->Flash->success(__('The hotel has been deleted.'));
        } else {
            $this->Flash->error(__('The hotel could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
