<?php
namespace App\Controller\Sale;

use App\Controller\AppController;

/**
 * LandTourAccessories Controller
 *
 * @property \App\Model\Table\LandTourAccessoriesTable $LandTourAccessories
 *
 * @method \App\Model\Entity\LandTourAccessory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LandTourAccessoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LandTours']
        ];
        $landTourAccessories = $this->paginate($this->LandTourAccessories);

        $this->set(compact('landTourAccessories'));
    }

    /**
     * View method
     *
     * @param string|null $id Land Tour Accessory id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $landTourAccessory = $this->LandTourAccessories->get($id, [
            'contain' => ['LandTours', 'BookingLandtourAccessories']
        ]);

        $this->set('landTourAccessory', $landTourAccessory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $landTourAccessory = $this->LandTourAccessories->newEntity();
        if ($this->request->is('post')) {
            $landTourAccessory = $this->LandTourAccessories->patchEntity($landTourAccessory, $this->request->getData());
            if ($this->LandTourAccessories->save($landTourAccessory)) {
                $this->Flash->success(__('The land tour accessory has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The land tour accessory could not be saved. Please, try again.'));
        }
        $landTours = $this->LandTourAccessories->LandTours->find('list', ['limit' => 200]);
        $this->set(compact('landTourAccessory', 'landTours'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Land Tour Accessory id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $landTourAccessory = $this->LandTourAccessories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $landTourAccessory = $this->LandTourAccessories->patchEntity($landTourAccessory, $this->request->getData());
            if ($this->LandTourAccessories->save($landTourAccessory)) {
                $this->Flash->success(__('The land tour accessory has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The land tour accessory could not be saved. Please, try again.'));
        }
        $landTours = $this->LandTourAccessories->LandTours->find('list', ['limit' => 200]);
        $this->set(compact('landTourAccessory', 'landTours'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Land Tour Accessory id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $landTourAccessory = $this->LandTourAccessories->get($id);
        if ($this->LandTourAccessories->delete($landTourAccessory)) {
            $this->Flash->success(__('The land tour accessory has been deleted.'));
        } else {
            $this->Flash->error(__('The land tour accessory could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
