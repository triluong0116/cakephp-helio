<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Locations Controller
 *
 * @property \App\Model\Table\LocationsTable $Locations
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LocationsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'limit' => 10
        ];
        $locations = $this->paginate($this->Locations);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_locations = $this->Locations->find()->where([
                'OR' => [
                    'Locations.name LIKE' => '%' . $data . '%',
                    'Locations.description LIKE' => '%' . $data . '%',
                    'Locations.map LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_locations->count();
            $locations = $this->paginate($list_object_locations);
            $this->set(compact('locations', 'number', 'data'));
            $this->render('search');
        } else
            $this->set(compact('locations'));
    }

    /**
     * View method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $location = $this->Locations->get($id, [
            'contain' => ['Hotels']
        ]);

        $this->set('location', $location);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->loadComponent('Upload');
        $location = $this->Locations->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if ($data['thumbnail']['error'] == 0) {
                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                $data['thumbnail'] = $thumbnail;
            } else {
                unset($data['thumbnail']);
            }

            $location = $this->Locations->patchEntity($location, $data);
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $this->set(compact('location'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $this->loadComponent('Upload');
        $location = $this->Locations->get($id, [
            'contain' => []
        ]);
        $images = [];
        if ($location->media) {
            $medias = json_decode($location->media, true);
            foreach ($medias as $media) {
                if (file_exists($media)) {
                    $obj['name'] = basename($media);
                    $obj['size'] = filesize($media);
                    $images[] = $obj;
                }
            }
        }
        $list_images = json_encode($images);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if ($data['thumbnail']['error'] == 0) {
                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                $data['thumbnail'] = $thumbnail;
            } else {
                $data['thumbnail'] = $data['thumbnail_edit'];
            }

            $location = $this->Locations->patchEntity($location, $data);
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $this->set(compact('location', 'list_images'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $location = $this->Locations->get($id);
        if ($this->Locations->delete($location)) {
            $this->Flash->success(__('The location has been deleted.'));
        } else {
            $this->Flash->error(__('The location could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function setFeatured() {
        $this->viewBuilder()->autoLayout(false);
        $response = ['success' => false, 'message' => ''];
        $data = $this->request->getData();
        if ($data['ids']) {
            $this->Locations->updateAll(['is_featured' => 1], ['id IN' => $data['ids']]);
        }
        $response['success'] = true;

        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

    public function unsetFeatured() {
        $this->viewBuilder()->autoLayout(false);
        $response = ['success' => false, 'message' => ''];
        $data = $this->request->getData();
        if ($data['ids']) {
            $this->Locations->updateAll(['is_featured' => 0], ['id IN' => $data['ids']]);
        }
        $response['success'] = true;

        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }

}
