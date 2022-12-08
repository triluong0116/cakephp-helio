<?php

namespace App\Controller\Api;

/**
 * Vouchers Controller
 *
 * @property \App\Model\Table\VouchersTable $Vouchers
 *
 * @method \App\Model\Entity\Voucher[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VouchersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail', 'calPrice', 'calPriceBooking']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Departures', 'Destinations', 'Hotels']
        ];
        $vouchers = $this->paginate($this->Vouchers);

        $this->set(compact('vouchers'));
    }

    public function lists()
    {
        $this->paginate = [
            'limit' => 10
        ];
        $location_id = $this->getRequest()->getQuery('location_id');
        $rating = $this->getRequest()->getQuery('rating');
        $price = $this->getRequest()->getQuery('price');
        $clientId = $this->getRequest()->getQuery('clientId');
        $keyword = $this->getRequest()->getQuery('keyword');
        $condition = [];
        if ($location_id) {
            $condition['destination_id'] = $location_id;
        }
        if ($rating) {
            $condition['rating'] = $rating;
        }
        if ($price) {
            $price_arr = explode('-', $price);
            if (count($price_arr) == 2) {
                if (is_numeric($price_arr[0])) {
                    $condition['(price + trippal_price + customer_price) >= '] = $price_arr[0];
                }
                if (is_numeric($price_arr[1])) {
                    $condition['(price + trippal_price + customer_price) <= '] = $price_arr[1];
                }
            }
        }
        $condition['Vouchers.name LIKE'] = '%' . $keyword . '%';
        $query = $this->Vouchers->find()->contain([
            'Favourites' => function ($q) use ($clientId) {
                return $q->where(['clientId' => $clientId]);
            }
        ])->where($condition);
        $vouchers = $this->paginate($query);
        foreach ($vouchers as $voucher) {
            if ($voucher->favourites) {
                $voucher->is_favourite = true;
            } else {
                $voucher->is_favourite = false;
            }
            unset($voucher->favourites);
            $voucher->singlePrice = $voucher->price + $voucher->trippal_price + $voucher->customer_price;
        }
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $vouchers,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id)
    {
        $clientId = $this->getRequest()->getQuery('clientId');
        $voucher = $this->Vouchers->get($id, ['contain' => ['Departures', 'Destinations', 'Hotels',
            'Hotels.Categories' => function ($q) {
                return $q->select(['icon', 'name']);
            },
            'Favourites' => function ($query) use ($clientId) {
                return $query->where(['clientId' => $clientId]);
            }
        ]]);
        $listCaption = json_decode($voucher->caption, true);
        foreach($listCaption as $key => $caption){
            $listCaption[$key]['content'] = strip_tags(html_entity_decode($caption['content']));
        }
        $voucher->caption = $listCaption;
        $voucher->media = json_decode($voucher->media, true);
        $voucher->term = json_decode($voucher->term, true);
        if (isset($voucher->hotel) && !empty($voucher->hotel)) {
            $voucher->hotel->caption = json_decode($voucher->hotel->caption, true);
            $voucher->hotel->term = json_decode($voucher->hotel->term, true);
            $voucher->hotel->long = $voucher->hotel->lon;
            unset($voucher->hotel->lon);
        }
        if ($voucher->favourites) {
            $voucher->is_favourite = true;
        } else {
            $voucher->is_favourite = false;
        }
        unset($voucher->favourites);

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $voucher,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function calPrice()
    {
        $data = $this->getRequest()->getQuery();

        $voucher = $this->Vouchers->get($data['voucher_id']);
        $price = ($voucher->price + $voucher->customer_price + $voucher->trippal_price) * $data['numVoucher'];
        $revenue = $voucher->customer_price * $data['numVoucher'];

        $response['singlePrice'] = $voucher->price + $voucher->customer_price + $voucher->trippal_price;
        $response['totalPrice'] = $price;
        $response['revenue'] = $revenue;

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $response,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $voucher = $this->Vouchers->get($id, [
            'contain' => ['Users', 'Departures', 'Destinations', 'Hotels']
        ]);

        $this->set('voucher', $voucher);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $voucher = $this->Vouchers->newEntity();
        if ($this->request->is('post')) {
            $voucher = $this->Vouchers->patchEntity($voucher, $this->request->getData());
            if ($this->Vouchers->save($voucher)) {
                $this->Flash->success(__('The voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
        }
        $users = $this->Vouchers->Users->find('list', ['limit' => 200]);
        $departures = $this->Vouchers->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Vouchers->Destinations->find('list', ['limit' => 200]);
        $hotels = $this->Vouchers->Hotels->find('list', ['limit' => 200]);
        $this->set(compact('voucher', 'users', 'departures', 'destinations', 'hotels'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $voucher = $this->Vouchers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $voucher = $this->Vouchers->patchEntity($voucher, $this->request->getData());
            if ($this->Vouchers->save($voucher)) {
                $this->Flash->success(__('The voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
        }
        $users = $this->Vouchers->Users->find('list', ['limit' => 200]);
        $departures = $this->Vouchers->Departures->find('list', ['limit' => 200]);
        $destinations = $this->Vouchers->Destinations->find('list', ['limit' => 200]);
        $hotels = $this->Vouchers->Hotels->find('list', ['limit' => 200]);
        $this->set(compact('voucher', 'users', 'departures', 'destinations', 'hotels'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $voucher = $this->Vouchers->get($id);
        if ($this->Vouchers->delete($voucher)) {
            $this->Flash->success(__('The voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function calPriceBooking() {
        $this->loadModel('Vouchers');
        $this->loadModel('Users');
        $data = $this->getRequest()->getData();
        $voucher = $this->Vouchers->get($data['item_id']);
        $price = ($voucher->price + $voucher->trippal_price + $voucher->customer_price) * $data['amount'];
        $profit = 0;
        $user = $this->Users->find()->where(['id' => $data['user_id']])->first();
        if ($user && $user->role_id == 3) {
            $profit = $voucher->customer_price * $data['amount'];
            if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
                $price = $price - $profit;
                $profit = 0;
            }
        }
        $response['price'] = $price;
        $response['profit'] = $profit;

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $response,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }
}
