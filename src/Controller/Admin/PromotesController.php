<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Promotes Controller
 *
 * @property \App\Model\Table\PromotesTable $Promotes
 *
 * @method \App\Model\Entity\Promote[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PromotesController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Hotels', 'Locations']
        ];
        $promotes = $this->paginate($this->Promotes);
//        dd($promotes->toArray());
        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_promotes = $this->Promotes->find()->where([
                'OR' => [
                    'Promotes.title LIKE' => '%' . $data . '%',
                    'Promotes.description LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_promotes->count();
            $promotes = $this->paginate($list_object_promotes);
            $this->set(compact('promotes', 'number', 'data'));
        } else
            $this->set(compact('promotes'));
    }

    /**
     * View method
     *
     * @param string|null $id Promote id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $promote = $this->Promotes->get($id, [
            'contain' => ['Hotels', 'Locations']
        ]);

        $this->set('promote', $promote);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $promote = $this->Promotes->newEntity();
        if ($this->request->is('post')) {
            $data = $this->getRequest()->getData();
            $date_array = explode(' - ', $data['reservation']);
            $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
            $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');
            $data['revenue'] = str_replace(',', '', $data['revenue']);
            $promote = $this->Promotes->patchEntity($promote, $data);
            if ($this->Promotes->save($promote)) {
                $this->Flash->success(__('The promote has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The promote could not be saved. Please, try again.'));
        }
        $promoteTypes = [
            P_REG_CONNECT => 'Đăng ký và Kết nối fanpage',
            P_BOOK_SHARE => 'Số Booking/Chia sẻ trong khoảng thời gian',
            P_BOOK_SHARE_HOTEL => 'Số Booking/Chia sẻ theo Khách sạn trong khoảng thời gian',
            P_BOOK_SHARE_LOCATION => 'Số Booking/Chia sẻ theo Địa điểm trong khoảng thời gian'
        ];
        $this->set(compact('promote', 'promoteTypes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Promote id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $promote = $this->Promotes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->getRequest()->getData();
            $date_array = explode(' - ', $data['reservation']);
            $data['start_date'] = $this->Util->formatSQLDate($date_array[0], 'd/m/Y');
            $data['end_date'] = $this->Util->formatSQLDate($date_array[1], 'd/m/Y');
            $data['revenue'] = str_replace(',', '', $data['revenue']);
            $promote = $this->Promotes->patchEntity($promote, $data);
            if ($this->Promotes->save($promote)) {
                $this->Flash->success(__('The promote has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The promote could not be saved. Please, try again.'));
        }
        $promoteTypes = [
            P_REG_CONNECT => 'Đăng ký và Kết nối fanpage',
            P_BOOK_SHARE => 'Số Booking/Chia sẻ trong khoảng thời gian',
            P_BOOK_SHARE_HOTEL => 'Số Booking/Chia sẻ theo Khách sạn trong khoảng thời gian',
            P_BOOK_SHARE_LOCATION => 'Số Booking/Chia sẻ theo Địa điểm trong khoảng thời gian'
        ];
        $this->set(compact('promote', 'promoteTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Promote id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $promote = $this->Promotes->get($id);
        if ($this->Promotes->delete($promote)) {
            $this->Flash->success(__('The promote has been deleted.'));
        } else {
            $this->Flash->error(__('The promote could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getOtherComponentPromote($type) {
        $this->viewBuilder()->enableAutoLayout(false);
        $objects = [];
        $data = $this->getRequest()->getQuery();
//        dd($data);
        $object_id = $data['object_id'];
        $num_book = $data['num_book'];
        $num_share = $data['num_share'];
        switch ($type) {
            case P_BOOK_SHARE_HOTEL:
                $this->loadModel('Hotels');
                $objects = $this->Hotels->find('list');
                break;
            case P_BOOK_SHARE_LOCATION:
                $this->loadModel('Locations');
                $objects = $this->Locations->find('list');
                break;
        }
//        dd($objects->toArray());
        $this->set(compact('objects', 'type', 'object_id', 'num_book', 'num_share'));
        $this->render('get_other_component_promote')->body();
    }

}
