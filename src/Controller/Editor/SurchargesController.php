<?php
namespace App\Controller\Editor;

use App\Controller\AppController;

/**
 * Surcharges Controller
 *
 * @property \App\Model\Table\SurchargesTable $Surcharges
 *
 * @method \App\Model\Entity\Surcharge[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SurchargesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $surcharges = $this->paginate($this->Surcharges);

        $this->set(compact('surcharges'));
    }

    /**
     * View method
     *
     * @param string|null $id Surcharge id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $surcharge = $this->Surcharges->get($id, [
            'contain' => ['Hotels']
        ]);

        $this->set('surcharge', $surcharge);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $surcharge = $this->Surcharges->newEntity();
        if ($this->request->is('post')) {
            $data = $this->getRequest()->getData();
            $surcharge = $this->Surcharges->patchEntity($surcharge, $data);
            if ($this->Surcharges->save($surcharge)) {
                $this->Flash->success(__('The surcharge has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The surcharge could not be saved. Please, try again.'));
        }
        $types = [
            1 => 'Số lượng',
            2 => 'Thời gian'
        ];
        $this->set(compact('surcharge', 'types'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Surcharge id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $surcharge = $this->Surcharges->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $surcharge = $this->Surcharges->patchEntity($surcharge, $this->request->getData());
            if ($this->Surcharges->save($surcharge)) {
                $this->Flash->success(__('The surcharge has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The surcharge could not be saved. Please, try again.'));
        }
        $types = [
            1 => 'Số lượng',
            2 => 'Thời gian'
        ];
        $this->set(compact('surcharge', 'types'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Surcharge id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $surcharge = $this->Surcharges->get($id);
        if ($this->Surcharges->delete($surcharge)) {
            $this->Flash->success(__('The surcharge has been deleted.'));
        } else {
            $this->Flash->error(__('The surcharge could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 4)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'editor'));
        return parent::isAuthorized($user);
    }
}
