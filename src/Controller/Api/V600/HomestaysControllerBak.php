<?php
namespace App\Controller\Api\V600;

/**
 * Homestays Controller
 *
 *
 *
 * @method \App\Model\Entity\Homestay[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HomestaysController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail', 'calPrice']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Locations']
        ];
        $homestays = $this->paginate($this->Homestays);

        $this->set(compact('homestays'));
    }

    public function lists() {
        $this->paginate = [
            'limit' => 10
        ];
        $location_id = $this->getRequest()->getQuery('location_id');
        $rating = $this->getRequest()->getQuery('rating');
        $price = $this->getRequest()->getQuery('price');
        $clientId = $this->getRequest()->getQuery('clientId');
        $fromDate = $this->getRequest()->getQuery('fromDate');
        $toDate = $this->getRequest()->getQuery('toDate');
        $keyword = $this->getRequest()->getQuery('keyword');
        $condition = [];
        if ($location_id) {
            $condition['location_id'] = $location_id;
        }
        if ($rating) {
            $condition['rating'] = $rating;
        }
        if ($price) {
            $price_arr = explode('-', $price);
            //do later
        }
        $condition['HomeStays.name LIKE'] = '%' . $keyword . '%';
        $query = $this->HomeStays->find()->contain(['Locations',
            'Favourites' => function ($q) use ($clientId) {
                return $q->where(['clientId' => $clientId]);
            },
            'PriceHomeStays'
        ])->where($condition);
        $homestays = $this->paginate($query);
        foreach ($homestays as $homestay) {
            if ($homestay->favourites) {
                $homestay->is_favourite = true;
            } else {
                $homestay->is_favourite = false;
            }
            unset($homestay->favourites);
            if ($fromDate) {
                $fromDate = date('Y-m-d', strtotime($fromDate));
            } else {
                $fromDate = date('Y-m-d');
            }

            $singlePrice = $this->Util->countingHomeStayPrice($fromDate, $homestay);
            $homestay->singlePrice = $singlePrice;
            unset($homestay->price_home_stays);
        }
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $homestays,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id) {
        $clientId = $this->getRequest()->getQuery('clientId');
        $homestay = $this->HomeStays->get($id, ['contain' => ['Locations',
            'Favourites' => function ($query) use ($clientId) {
                return $query->where(['clientId' => $clientId]);
            }
        ]]);
        $homestay->caption = json_decode($homestay->caption, true);
        $homestay->media = json_decode($homestay->media, true);
        $homestay->term = json_decode($homestay->term, true);
        if ($homestay->favourites) {
            $homestay->is_favourite = true;
        } else {
            $homestay->is_favourite = false;
        }
        unset($homestay->favourites);

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $homestay,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function calPrice() {

        $data = $this->getRequest()->getQuery();
        $homestay = $this->HomeStays->get($data['homestay_id'], ['contain' => 'PriceHomeStays']);

        $calSDate = $data['fromDate'];
        $calEDate = date('d-m-Y', strtotime($data['toDate']. "-1 days"));
        $dates = $this->Util->_dateRange($calSDate, $calEDate);

        $revenue = count($dates) * $homestay->price_customer ;

        $priceWeekDay = $priceWeekEnd = 0;
        $priceType = [];
        foreach ($homestay->price_home_stays as $price_home_stay) {
            if ($price_home_stay->type == WEEK_DAY) {
                $priceWeekDay = $price_home_stay->price;
            }
            if ($price_home_stay->type == WEEK_END) {
                $priceWeekEnd = $price_home_stay->price;
            }
            $singlePrice = $price_home_stay->price + $homestay->price_agency + $homestay->price_customer;
            $price_home_stay->singlePrice = $singlePrice;
            unset($price_home_stay->price);
            $priceType[] = $price_home_stay;
        }

        $totalPrice = 0;
        foreach ($dates as $date) {
            $unixTimestamp = strtotime($date);
            $weekday = date("l", $unixTimestamp);
            if ($weekday == 'Monday' || $weekday == 'Tuesday' || $weekday == 'Wednesday' || $weekday == 'Thursday') {
                $totalPrice += $priceWeekDay + $homestay->price_agency + $homestay->price_customer;
            } else {
                $totalPrice += $priceWeekEnd  + $homestay->price_agency + $homestay->price_customer;
            }
        }

        $response['priceType'] = $priceType;
        $response['totalPrice'] = $totalPrice;
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
     * @param string|null $id Homestay id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $homestay = $this->Homestays->get($id, [
            'contain' => ['Locations', 'Categories', 'PriceHomeStays']
        ]);

        $this->set('homestay', $homestay);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $homestay = $this->Homestays->newEntity();
        if ($this->request->is('post')) {
            $homestay = $this->Homestays->patchEntity($homestay, $this->request->getData());
            if ($this->Homestays->save($homestay)) {
                $this->Flash->success(__('The homestay has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The homestay could not be saved. Please, try again.'));
        }
        $locations = $this->Homestays->Locations->find('list', ['limit' => 200]);
        $categories = $this->Homestays->Categories->find('list', ['limit' => 200]);
        $this->set(compact('homestay', 'locations', 'categories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Homestay id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $homestay = $this->Homestays->get($id, [
            'contain' => ['Categories']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $homestay = $this->Homestays->patchEntity($homestay, $this->request->getData());
            if ($this->Homestays->save($homestay)) {
                $this->Flash->success(__('The homestay has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The homestay could not be saved. Please, try again.'));
        }
        $locations = $this->Homestays->Locations->find('list', ['limit' => 200]);
        $categories = $this->Homestays->Categories->find('list', ['limit' => 200]);
        $this->set(compact('homestay', 'locations', 'categories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Homestay id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $homestay = $this->Homestays->get($id);
        if ($this->Homestays->delete($homestay)) {
            $this->Flash->success(__('The homestay has been deleted.'));
        } else {
            $this->Flash->error(__('The homestay could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
