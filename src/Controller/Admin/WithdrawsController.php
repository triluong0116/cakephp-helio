<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Withdraws Controller
 *
 * @property \App\Model\Table\WithdrawsTable $Withdraws
 *
 * @method \App\Model\Entity\Withdraw[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WithdrawsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $withdraws = $this->Withdraws->find()->contain(['Users'])->where(['Withdraws.status' => 0])->order(['Withdraws.created' => 'DESC']);
        $this->set(compact('withdraws'));
    }

    /**
     * View method
     *
     * @param string|null $id Withdraw id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
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
    public function add()
    {
        $withdraw = $this->Withdraws->newEntity();
        if ($this->request->is('post')) {
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
     * Edit method
     *
     * @param string|null $id Withdraw id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
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
    public function delete($id = null)
    {
        $this->loadModel('Users');
        $this->request->allowMethod(['post', 'delete']);
        $withdraw = $this->Withdraws->get($id);
        $withdraw = $this->Withdraws->patchEntity($withdraw, ['status' => 1]);
        $this->Withdraws->save($withdraw);
//        if ($this->Withdraws->delete($withdraw)) {
//            $this->Flash->success(__('The withdraw has been deleted.'));
//        } else {
//            $this->Flash->error(__('The withdraw could not be deleted. Please, try again.'));
//        }

        return $this->redirect(['action' => 'index']);
    }
}
