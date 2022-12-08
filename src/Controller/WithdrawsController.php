<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Withdraws Controller
 *
 * @property \App\Model\Table\WithdrawsTable $Withdraws
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\Withdraw[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WithdrawsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $withdraws = $this->paginate($this->Withdraws);

        $this->set(compact('withdraws'));
    }

    /**
     * View method
     *
     * @param string|null $id Withdraw id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $withdraw = $this->Withdraws->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('withdraw', $withdraw);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('Users');
        $response = ['success' => false, 'errors' => [], 'message' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $data['amount'] = str_replace(',', '', $data['amount']);
            $data['user_id'] = $this->Auth->user('id');
            $validate = $this->Withdraws->newEntity($data, ['validate' => 'withdraw']);
            if ($validate->getErrors()) {
                $response['errors'] = $validate->getErrors();
            } elseif ($data['amount'] > $this->Auth->user('revenue')) {
                $response['errors'] = ['amount' => ['Số dư tài khoản của bạn hiện không đủ!']];
            } else {
                $withdraw = $this->Withdraws->newEntity();
                $withdraw = $this->Withdraws->patchEntity($withdraw, $data);
//dd($requestVoucher);
                if ($this->Withdraws->save($withdraw)) {
                    $response['success'] = true;
                    $user = $this->Users->get($data['user_id']);
                    $user = $this->Users->patchEntity($user, ['revenue' => $user->revenue - $data['amount']]);
                    $this->Users->save($user);
                } else {
//                    dd($requestVoucher);
                    $response['message'] = 'Có lỗi xảy ra!';
                }
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Withdraw id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $withdraw = $this->Withdraws->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $withdraw = $this->Withdraws->patchEntity($withdraw, $this->request->getData());
            if ($this->Withdraws->save($withdraw)) {
                $this->Flash->success(__('The withdraw has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The withdraw could not be saved. Please, try again.'));
        }
        $users = $this->Withdraws->Users->find('list', ['limit' => 200]);
        $this->set(compact('withdraw', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Withdraw id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $withdraw = $this->Withdraws->get($id);
        if ($this->Withdraws->delete($withdraw)) {
            $this->Flash->success(__('The withdraw has been deleted.'));
        } else {
            $this->Flash->error(__('The withdraw could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
