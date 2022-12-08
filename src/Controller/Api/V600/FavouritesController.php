<?php

namespace App\Controller\Api\V600;

/**
 * Favourites Controller
 *
 * @property \App\Model\Table\FavouritesTable $Favourites
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\HotelSearchsTable $HotelSearchs
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\Favourite[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FavouritesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'doFavourite', 'listByType']);
    }

    public function lists()
    {
        $this->loadModel('Users');
        $this->loadModel('Hotels');
        $this->loadModel('HotelSearchs');
        $this->loadModel('LandTours');
        $status = STT_SUCCESS;
        $message = '';
        $data = [];
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            if ($user) {
                $page = $this->request->getQuery('page');
                $type = $this->request->getQuery('type');
                $dataObjs = [];
                switch ($type) {
                    case HOTEL:
                        $listObjIds = $this->Favourites->find('list', ['keyField' => 'object_id', 'valueField' => 'object_id'])->where([
                            'object_type' => HOTEL,
                            'user_id' => $user->id
                        ])->toArray();
                        $today = date('Y-m-d');
                        if ($listObjIds){
                            $listObjIds = array_slice($listObjIds, 10 * ($page - 1), 10);
                            $listHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id IN' => $listObjIds])->toArray();
                            $hotelSearchPrice = $this->HotelSearchs->find('list', ['keyField' => 'id', 'valueField' => 'price_day_app'])->where([
                                'id IN' => $listObjIds,
                                'single_day' => $today
                            ])->toArray();
                            foreach ($listHotels as $hotel) {
                                $data[] = [
                                    'id' => $hotel->id,
                                    'name' => $hotel->name,
                                    'thumbnail' => $hotel->thumbnail,
                                    'location' => $hotel->location->name,
                                    'rating' => $hotel->rating,
                                    'singlePrice' => isset($hotelSearchPrice[$hotel->id]) ? number_format($hotelSearchPrice[$hotel->id]) : 'Đang cập nhật',
                                ];
                            }
                            $status = STT_SUCCESS;
                            $message = "success";
                        }
                        else
                        {
                            $status = STT_SUCCESS;
                            $message = "success";
                        }
                        break;
                    case LANDTOUR:
                        $listObjIds = $this->Favourites->find('list', ['keyField' => 'object_id', 'valueField' => 'object_id'])->where([
                            'object_type' => LANDTOUR,
                            'user_id' => $user->id
                        ])->toArray();
                        if ($listObjIds)
                        {
                            $listObjIds = array_slice($listObjIds, 10 * ($page - 1), 10);
                            $landtours = $this->LandTours->find()->contain([
                                'Destinations',
                                'LandTourAccessories',
                                'LandTourUserPrices' => function ($q) use ($user) {
                                    return $q->where(['user_id' => $user->id]);
                                }
                            ])->where(['LandTours.id IN' => $listObjIds])->toArray();
                            foreach ($landtours as $landtour) {
                                foreach ($landtour->land_tour_accessories as $accessory) {
                                    $landtour->singlePrice += $accessory->adult_price;
                                }
                                if (count($landtour->land_tour_user_prices) > 0) {
                                    $landtour->singlePrice += $landtour->price + $landtour->customer_price + $landtour->land_tour_user_prices[0]->price;
                                } else {
                                    $landtour->singlePrice += $landtour->price + $landtour->trippal_price + $landtour->customer_price;
                                }
                                $data[] = [
                                    'id' => $landtour->id,
                                    'name' => $landtour->name,
                                    'thumbnail' => $landtour->thumbnail,
                                    'location' => $landtour->destination->name,
                                    'rating' => $landtour->rating,
                                    'singlePrice' => number_format($landtour->singlePrice),
                                ];
                            }
                            $status = STT_SUCCESS;
                            $message = "success";
                        }
                        else
                        {
                            $status = STT_SUCCESS;
                            $message = "success";
                        }

                        break;
                    case VINPEARL:
                        $listObjIds = $this->Favourites->find('list', ['keyField' => 'object_id', 'valueField' => 'object_id'])->where([
                            'object_type' => VINPEARL,
                            'user_id' => $user->id
                        ])->toArray();
                        $today = date('Y-m-d');
                        if ($listObjIds){
                            $listObjIds = array_slice($listObjIds, 10 * ($page - 1), 10);
                            $listHotels = $this->Hotels->find()->contain('Locations')->where(['Hotels.id IN' => $listObjIds])->toArray();
                            foreach ($listHotels as $hotel) {
                                $data[] = [
                                    'id' => $hotel->id,
                                    'name' => $hotel->name,
                                    'thumbnail' => $hotel->thumbnail,
                                    'location' => $hotel->location->name,
                                    'rating' => $hotel->rating,
                                    'singlePrice' => 'Đang cập nhật',
                                    'extends' => $hotel->extends ? json_decode($hotel->extends, true) : []
                                ];
                            }
                            $status = STT_SUCCESS;
                            $message = "success";
                        }
                        else
                        {
                            $status = STT_SUCCESS;
                            $message = "success";
                        }
                        break;
                }
                $dataObjs = array_slice($dataObjs, 10 * ($page - 1), 10);

            } else {
                $status = STT_INVALID;
                $message = 'User Not Found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not Logged In';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function listByType()
    {
        $clientId = $this->getRequest()->getQuery('clientId');
        $object_type = $this->getRequest()->getQuery('object_type');
        $contain = '';
        switch ($object_type) {
            case COMBO:
                $contain = 'Combos';
                break;
            case VOUCHER:
                $contain = 'Vouchers';
                break;
            case LANDTOUR:
                $contain = 'LandTours';
                break;
            case HOTEL:
                $contain = 'Hotels';
                break;
            case HOMESTAY:
                $contain = 'HomeStays';
                break;
        }

        $data = $this->Favourites->find()->contain([$contain])->where(['clientId' => $clientId, 'object_type' => $object_type]);
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function doFavourite()
    {
        $this->loadModel('Users');
        $data = $this->getRequest()->getData();
        $check = $this->Api->checkLoginApi();
        $status = STT_ERROR;
        $message = '';
        $arr = [];
        if ($check['status']) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            if ($user) {
                $existFavourite = $this->Favourites->find()->where(['user_id' => $user->id, 'object_id' => $data['object_id'], 'object_type' => $data['object_type']])->first();
                if ($existFavourite) {
                    $this->Favourites->delete($existFavourite);
                    $status = STT_SUCCESS;
                    $message = 'Success';
                    $arr['is_unFavourite'] = true;
                } else {
                    $data['user_id'] = $user->id;
                    $favourite = $this->Favourites->newEntity();
                    $favourite = $this->Favourites->patchEntity($favourite, $data);
                    if ($this->Favourites->save($favourite)) {
                        $status = STT_SUCCESS;
                        $message = 'Success';
                        $arr['is_unFavourite'] = false;
                    } else {
                        $status = STT_ERROR;
                        $message = 'Save Unsuccessfully';
                    }
                }
            } else {
                $status = STT_NOT_FOUND;
                $message = 'User Not Found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not Logged In';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $arr,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Objects']
        ];
        $favourites = $this->paginate($this->Favourites);

        $this->set(compact('favourites'));
    }

    /**
     * View method
     *
     * @param string|null $id Favourite id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $favourite = $this->Favourites->get($id, [
            'contain' => ['Objects']
        ]);

        $this->set('favourite', $favourite);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $favourite = $this->Favourites->newEntity();
        if ($this->request->is('post')) {
            $favourite = $this->Favourites->patchEntity($favourite, $this->request->getData());
            if ($this->Favourites->save($favourite)) {
                $this->Flash->success(__('The favourite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The favourite could not be saved. Please, try again.'));
        }
        $objects = $this->Favourites->Objects->find('list', ['limit' => 200]);
        $this->set(compact('favourite', 'objects'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Favourite id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $favourite = $this->Favourites->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $favourite = $this->Favourites->patchEntity($favourite, $this->request->getData());
            if ($this->Favourites->save($favourite)) {
                $this->Flash->success(__('The favourite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The favourite could not be saved. Please, try again.'));
        }
        $objects = $this->Favourites->Objects->find('list', ['limit' => 200]);
        $this->set(compact('favourite', 'objects'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Favourite id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $favourite = $this->Favourites->get($id);
        if ($this->Favourites->delete($favourite)) {
            $this->Flash->success(__('The favourite has been deleted.'));
        } else {
            $this->Flash->error(__('The favourite could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
