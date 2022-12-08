<?php

namespace App\Controller\Sale;

use App\Controller\AppController;

/**
 * RequestVouchers Controller
 *
 * @property \App\Model\Table\RequestVouchersTable $RequestVouchers
 *
 * @method \App\Model\Entity\RequestVoucher[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RequestVouchersController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'limit' => 10
        ];
        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_vouchers = $this->RequestVouchers->find()->where([
                'OR' => [
                    'RequestVouchers.title LIKE' => '%' . $data . '%',
                    'RequestVouchers.time LIKE' => '%' . $data . '%',
                    'RequestVouchers.price LIKE' => '%' . $data . '%',
                    'RequestVouchers.full_name LIKE' => '%' . $data . '%',
                    'RequestVouchers.phone LIKE' => '%' . $data . '%',
                    'RequestVouchers.email LIKE' => '%' . $data . '%'
                ]
            ])->order(['created' => 'DESC']);
            $number = $list_object_vouchers->count();
            $vouchers = $this->paginate($list_object_vouchers);
            $this->set(compact('vouchers', 'number', 'data'));
        } else {
            $vouchers = $this->paginate($this->RequestVouchers);
            $this->set(compact('vouchers'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Request Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $requestVoucher = $this->RequestVouchers->get($id, [
            'contain' => []
        ]);

        $this->set('requestVoucher', $requestVoucher);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $requestVoucher = $this->RequestVouchers->newEntity();
        if ($this->request->is('post')) {
            $requestVoucher = $this->RequestVouchers->patchEntity($requestVoucher, $this->request->getData());
            if ($this->RequestVouchers->save($requestVoucher)) {
                $this->Flash->success(__('The request voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The request voucher could not be saved. Please, try again.'));
        }
        $this->set(compact('requestVoucher'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Request Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $requestVoucher = $this->RequestVouchers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestVoucher = $this->RequestVouchers->patchEntity($requestVoucher, $this->request->getData());
            if ($this->RequestVouchers->save($requestVoucher)) {
                $this->Flash->success(__('The request voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The request voucher could not be saved. Please, try again.'));
        }
        $this->set(compact('requestVoucher'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Request Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $requestVoucher = $this->RequestVouchers->get($id);
        if ($this->RequestVouchers->delete($requestVoucher)) {
            $this->Flash->success(__('The request voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The request voucher could not be deleted. Please, try again.'));
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
}
