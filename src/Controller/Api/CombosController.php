<?php
namespace App\Controller\Api;

/**
 * Combos Controller
 *
 * @property \App\Model\Table\CombosTable $Combos
 * @property \App\Model\Table\CombosHotelsTable $CombosHotels
 * @property \App\Model\Table\HotelsTable $Hotels
 *
 * @method \App\Model\Entity\Combo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CombosController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail']);
    }

    public function lists() {
        $this->paginate = [
            'limit' => 10
        ];
        $location_id = $this->getRequest()->getQuery('location_id');
        $rating = $this->getRequest()->getQuery('rating');
        $price = $this->getRequest()->getQuery('price');
        $condition = [];
        if ($location_id) {
            $condition['destination_id'] = $location_id;
        }
        if ($rating) {
            $condition['rating'] = $rating;
        }
        if ($price) {
            $price_arr = explode('-', $price);
            //do later
        }
        $combos = $this->Combos->find()->contain(['Destinations'])->where($condition);
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $combos,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id) {
        $this->loadModel('Hotels');
        $this->loadModel('CombosHotels');
        $combo = $this->Combos->get($id);
        if ($combo) {
            $hotelIds = $this->CombosHotels->find()->where(['combo_id' => $combo->id])->extract('hotel_id')->toArray();
            if ($hotelIds) {
                $hotels = $this->Hotels->find()->where(['id IN' => $hotelIds]);
            } else {
                $hotels = [];
            }
            $combo->hotels = $hotels;
        } else {
            $combo = [];
        }

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $combo,
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
            'contain' => ['Departures', 'Destinations']
        ];
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
    public function view($id = null)
    {
        $combo = $this->Combos->get($id, [
            'contain' => ['Departures', 'Destinations', 'Rooms', 'Hotels', 'Bookings']
        ]);

        $this->set('combo', $combo);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $combo = $this->Combos->newEntity();
        if ($this->request->is('post')) {
            $combo = $this->Combos->patchEntity($combo, $this->request->getData());
            if ($this->Combos->save($combo)) {
                $this->Flash->success(__('The combo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The combo could not be saved. Please, try again.'));
        }
        $departures = $this->Combos->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Combos->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Combos->Rooms->find('list', ['limit' => 200]);
        $hotels = $this->Combos->Hotels->find('list', ['limit' => 200]);
        $this->set(compact('combo', 'departures', 'destinations', 'rooms', 'hotels'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $combo = $this->Combos->get($id, [
            'contain' => ['Rooms', 'Hotels']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $combo = $this->Combos->patchEntity($combo, $this->request->getData());
            if ($this->Combos->save($combo)) {
                $this->Flash->success(__('The combo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The combo could not be saved. Please, try again.'));
        }
        $departures = $this->Combos->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Combos->Destinations->find('list', ['limit' => 200]);
        $rooms = $this->Combos->Rooms->find('list', ['limit' => 200]);
        $hotels = $this->Combos->Hotels->find('list', ['limit' => 200]);
        $this->set(compact('combo', 'departures', 'destinations', 'rooms', 'hotels'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $combo = $this->Combos->get($id);
        if ($this->Combos->delete($combo)) {
            $this->Flash->success(__('The combo has been deleted.'));
        } else {
            $this->Flash->error(__('The combo could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
