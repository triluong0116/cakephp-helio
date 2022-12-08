<?php

namespace App\Controller\Api\v400;
use App\Controller\Api\AppController;

/**
 * Favourites Controller
 *
 * @property \App\Model\Table\FavouritesTable $Favourites
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

    public function lists() {
        $clientId = $this->getRequest()->getQuery('clientId');
        $combos = $this->Favourites->find()->contain(['Combos'])->where(['clientId' => $clientId, 'object_type' => COMBO])->limit(5)->toArray();
        $vouchers = $this->Favourites->find()->contain(['Vouchers'])->where(['clientId' => $clientId, 'object_type' => VOUCHER])->limit(5)->toArray();
        $landtours = $this->Favourites->find()->contain(['LandTours'])->where(['clientId' => $clientId, 'object_type' => LANDTOUR])->limit(5)->toArray();
        $hotels = $this->Favourites->find()->contain(['Hotels'])->where(['clientId' => $clientId, 'object_type' => HOTEL])->limit(5)->toArray();
        $homestays = $this->Favourites->find()->contain(['HomeStays'])->where(['clientId' => $clientId, 'object_type' => HOMESTAY])->limit(5)->toArray();
        $data = [
            'combos' => $combos,
            'vouchers' => $vouchers,
            'land_tours' => $landtours,
            'hotels' => $hotels,
            'homestays' => $homestays
        ];

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function listByType() {
        $clientId = $this->getRequest()->getQuery('clientId');
        $object_type = $this->getRequest()->getQuery('object_type');
        $contain = '';
        switch ($object_type) {
            case COMBO:
                $contain ='Combos';
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
        $data = $this->getRequest()->getData();
        $validate = $this->Favourites->newEntity($data);
        if ($validate->getErrors()) {
            $this->set([
                'status' => STT_NOT_VALIDATION,
                'data' => $validate->getErrors(),
                '_serialize' => ['status', 'data']
            ]);
        } else {
            $existFavourite = $this->Favourites->find()->where(['clientId' => $data['clientId'], 'object_id' => $data['object_id'], 'object_type' => $data['object_type']])->first();
            if ($existFavourite) {
                $this->Favourites->delete($existFavourite);
                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => "Thành công",
                    'data' => ['is_unFavourite' => true],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            } else {
                $favourite = $this->Favourites->newEntity();
                $favourite = $this->Favourites->patchEntity($favourite, $data);
                if ($this->Favourites->save($favourite)) {
                    $this->set([
                        'status' => STT_SUCCESS,
                        'message' => "Thành công",
                        'data' => ['is_unFavourite' => false],
                        '_serialize' => ['status', 'message', 'data']
                    ]);
                } else {
                    $this->set([
                        'status' => STT_NOT_SAVE,
                        'message' => "Có lỗi xảy ra",
                        'data' => [],
                        '_serialize' => ['status', 'message', 'data']
                    ]);
                }
            }
        }

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
