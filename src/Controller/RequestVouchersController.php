<?php

namespace App\Controller;

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
        $requestVouchers = $this->paginate($this->RequestVouchers);

        $this->set(compact('requestVouchers'));
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
        $this->autoRender = false;
        $response = ['success' => false, 'url' => '', 'message' => '', 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $validate = $this->RequestVouchers->newEntity($data, ['validate' => 'addVoucher']);
            if ($validate->getErrors()) {
                $response['errors'] = $validate->getErrors();
            } else {
                $requestVoucher = $this->RequestVouchers->newEntity();
                $requestVoucher = $this->RequestVouchers->patchEntity($requestVoucher, $this->request->getData());
//dd($requestVoucher);
                if ($this->RequestVouchers->save($requestVoucher)) {
                    $response['success'] = true;
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

}
