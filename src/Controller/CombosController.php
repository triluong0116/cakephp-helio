<?php

namespace App\Controller;

use App\Controller\AppController;

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
        $this->loadModel('Locations');
        $limit = 12;
        $page = $this->request->getQuery('p');
        if(!$page){
            $page = 1;
        }

        $departure_id = $this->request->getQuery('departure_id');
        $destination_id = $this->request->getQuery('destination_id');
        $fromDate = $this->request->getQuery('fromDate');
        $toDate = $this->request->getQuery('toDate');
        if ($fromDate) {
            $fromDate = date('Y-m-d', strtotime($fromDate));
        }
        if ($toDate) {
            $toDate = date('Y-m-d', strtotime($toDate));
        }

        $sortPrice = $this->request->getQuery('sort');
//        dd($sortPrice);
        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $listLocation = explode(',', $filterLocation);
        $listRating = explode(',', $filterRating);

        $condition = $order = [];
        if ($departure_id) {
            $condition['departure_id'] = $departure_id;
        }
        if ($destination_id) {
            $condition['destination_id'] = $destination_id;
        }
        if ($filterLocation) {
            $condition['destination_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
        }

        $title = "HOTDEAL";
        $headerType = 1;
        $breadcrumbs = [
                ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
                ['title' => 'Hot Deal', 'href' => '']
        ];
        $today = date('Y-m-d');
//        dd($condition);
        $tmpCombos = $this->Combos->find()->contain([
                    'Destinations',
                    'Departures',
                    'Hotels',
                    'Hotels.PriceHotels'])->where($condition)->order($order)->toArray();
        $combos = [];

        foreach ($tmpCombos as $key => $combo) {
            $tmpCombos[$key]->totalPrice = $this->Util->countingComboPrice($today, $combo);
        }

        if ($filterPrice) {
            $filterPriceArr = explode('-', $filterPrice);
            foreach ($tmpCombos as $combo) {
                if ($combo->totalPrice >= $filterPriceArr[0] && $combo->totalPrice <= $filterPriceArr[1]) {
                    $combos[] = $combo;
                }
            }
            $listPrice = implode(',', $filterPriceArr);
        } else {
            $combos = $tmpCombos;
            $listPrice = '';
        }

        if ($sortPrice) {
            if ($sortPrice == "ASC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'asc');
            }
            if ($sortPrice == "DESC") {
                $combos = \Cake\Utility\Hash::sort($combos, '{n}.totalPrice', 'desc');
            }
        }

        $amountItems = count($combos);
        $combos = array_slice($combos, ($page - 1) * $limit, $page * $limit);

        $locations = $this->Locations->find();
        $this->set(compact('combos', 'title', 'breadcrumbs', 'sortPrice', 'listLocation', 'listPrice', 'listRating', 'locations', 'headerType', 'fromDate', 'toDate', 'amountItems'));
    }

    /**
     * View method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $this->loadModel('Users');
        $currentDay = $slug = $this->request->getParam('slug');
        $combo = $this->Combos->find()
                        ->contain(['Departures', 'Destinations', 'Hotels.Categories', 'Hotels',
                            'Hotels.PriceHotels'
                        ])
                        ->where(['Combos.slug' => $slug])->first();
        if (!$combo) {
            return $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        if ($this->request->getSession()->read('refAgencyCode')) {
            $ref = $this->request->getSession()->read('refAgencyCode');
            $user = $this->Users->find()->where(['ref_code' => $ref])->first();
            $hotline = $user->phone;
        } else {
            $hotline = "092.5959.777";
        }
//        dd($refcode);
//        dd($user);
        $title = $combo->name;
        $headerType = 1;
        $this->set('combo', $combo);
//        dd($combo->toArray());
        $this->set(compact('title', 'headerType', 'hotline'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
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
        $this->set(compact('combo', 'departures', 'destinations', 'rooms'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $combo = $this->Combos->get($id, [
            'contain' => ['Rooms']
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
        $this->set(compact('combo', 'departures', 'destinations', 'rooms'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $combo = $this->Combos->get($id);
        if ($this->Combos->delete($combo)) {
            $this->Flash->success(__('The combo has been deleted.'));
        } else {
            $this->Flash->error(__('The combo could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getPriceByDate() {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => true, 'new_price' => ''];
        $data = $this->request->getData();
//        dd($data);
        $combo = $this->Combos->get($data['combo_id'], ['contain' => ['Hotels', 'Hotels.PriceHotels']]);
//        dd($combo);
        $priceByDate = $this->Util->countingComboPrice($data['date'], $combo);
//        dd($priceByDate);
        $response['new_price'] = number_format($priceByDate);
        $response['combo_price'] = number_format(round($priceByDate / (100 - $combo->promote) * 100, -3));
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

}
