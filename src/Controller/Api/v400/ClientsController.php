<?php

namespace App\Controller\Api\v400;
use App\Controller\Api\AppController;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['update']);
    }

//    public function init()
//    {
//        $data = $this->getRequest()->getData();
//        $validate = $this->Clients->newEntity($data, ['validate' => 'init']);
//        if ($validate->getErrors()) {
//            $this->set([
//                'status' => STT_NOT_VALIDATION,
//                'data' => $validate->getErrors(),
//                '_serialize' => ['status', 'data']
//            ]);
//        } else {
//            $client = $this->Clients->find()->where(['clientId' => $data['clientId']])->first();
//            if (!$client) {
//                $client = $this->Clients->newEntity();
//            }
//            $client = $this->Clients->patchEntity($client, $data);
//            if ($this->Clients->save($client)) {
//                $this->set([
//                    'status' => STT_SUCCESS,
//                    'message' => "Thành công",
//                    'data' => [],
//                    '_serialize' => ['status', 'message', 'data']
//                ]);
//            } else {
//                $this->set([
//                    'status' => STT_NOT_SAVE,
//                    'message' => "Có lỗi xảy ra",
//                    'data' => [],
//                    '_serialize' => ['status', 'message', 'data']
//                ]);
//            }
//        }
//    }
//
//    public function check() {
//        $clientId = $this->getRequest()->getQuery('clientId');
//        $client = $this->Clients->find()->where(['clientId' => $clientId])->first();
//        if ($client) {
//            if ($client->name) {
//                $this->set([
//                    'status' => STT_SUCCESS,
//                    'data' => ['name' => $client->name],
//                    '_serialize' => ['status', 'data']
//                ]);
//            } else {
//                $this->set([
//                    'status' => STT_EMPTY_NAME,
//                    '_serialize' => ['status', 'message']
//                ]);
//            }
//        } else {
//            $this->set([
//                'status' => STT_NOT_FOUND,
//                '_serialize' => ['status']
//            ]);
//        }
//    }

    public function update()
    {
        $data = $this->getRequest()->getData();
        $validate = $this->Clients->newEntity($data, ['validate' => 'update']);
        if ($validate->getErrors()) {
            $this->set([
                'status' => STT_NOT_VALIDATION,
                'data' => $validate->getErrors(),
                '_serialize' => ['status', 'data']
            ]);
        } else {
            $client = $this->Clients->find()->where(['clientId' => $data['clientId']])->first();
            if (!$client) {
                $client = $this->Clients->newEntity();
            }
            $client = $this->Clients->patchEntity($client, $data);
            if ($this->Clients->save($client)) {
                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => "Thành công",
                    'data' => [],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            } else {
                $this->set([
                    'status' => STT_NOT_SAVE,
                    'message' => "Có lỗi xảy ra",
                    'data' => [],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $clients = $this->paginate($this->Clients);

        $this->set(compact('clients'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);

        $this->set('client', $client);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $client = $this->Clients->newEntity();
        if ($this->request->is('post')) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The client could not be saved. Please, try again.'));
        }
        $this->set(compact('client'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The client could not be saved. Please, try again.'));
        }
        $this->set(compact('client'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $client = $this->Clients->get($id);
        if ($this->Clients->delete($client)) {
            $this->Flash->success(__('The client has been deleted.'));
        } else {
            $this->Flash->error(__('The client could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
