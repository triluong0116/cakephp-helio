<?php

namespace App\Controller\Sale;

use App\Controller\AppController;

/**
 * Reviews Controller
 *
 * @property \App\Model\Table\ReviewsTable $Reviews
 *
 * @method \App\Model\Entity\Review[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Locations', 'Categories']
        ];
        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_reviews = $this->Reviews->find()->where([
                'OR' => [
                    'Reviews.title LIKE' => '%' . $data . '%',
                    'Reviews.caption LIKE' => '%' . $data . '%',
                    'Reviews.content LIKE' => '%' . $data . '%',
                ]
            ]);
            $number = $list_object_reviews->count();
            $reviews = $this->paginate($list_object_reviews);
            $this->set(compact('reviews', 'number', 'data'));
        } else {
            $reviews = $this->paginate($this->Reviews);
            $this->set(compact('reviews'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Review id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $review = $this->Reviews->get($id, [
            'contain' => ['Categories', 'Locations']
        ]);

        $this->set('review', $review);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadComponent('Upload');
        $review = $this->Reviews->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['price_start'] = str_replace(',', '', $data['price_start']);
            $data['price_end'] = str_replace(',', '', $data['price_end']);
            if ($data['thumbnail']['error'] == 0) {
                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                $data['thumbnail'] = $thumbnail;
            } else {
                unset($data['thumbnail']);
            }
            foreach($data['list_review'] as $k => $place){
                $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($place['address']) . '&key=' . MAP_API);
                $output = json_decode($geocode);
                $lat = $output->results[0]->geometry->location->lat;
                $long = $output->results[0]->geometry->location->lng;
                $data['list_review'][$k]['lat'] = $lat;
                $data['list_review'][$k]['lon'] = $long;
                $data['list_review'][$k]['rating'] = $data['rating'];
            }
            $data['place'] = json_encode($data['list_review'], JSON_UNESCAPED_UNICODE);
            $review = $this->Reviews->patchEntity($review, $data);
            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('The review has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The review could not be saved. Please, try again.'));
        }
        $categories = $this->Reviews->Categories->find('list', ['limit' => 200])->where(['parent_id' => 63]);
        $locations = $this->Reviews->Locations->find('list');
        $this->set(compact('review', 'categories', 'locations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Review id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Upload');
        $review = $this->Reviews->get($id, [
            'contain' => []
        ]);
//        dd($review);
        $images = [];
        if ($review->media) {
            $medias = json_decode($review->media, true);
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
            $data['price_start'] = str_replace(',', '', $data['price_start']);
            $data['price_end'] = str_replace(',', '', $data['price_end']);
            if ($data['thumbnail']['error'] == 0) {
                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                $data['thumbnail'] = $thumbnail;
            } else {
                $data['thumbnail'] = $data['thumbnail_edit'];
            }
            foreach($data['list_review'] as $k => $place){
                $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($place['address']) . '&key=' . MAP_API);
                $output = json_decode($geocode);
                $lat = $output->results[0]->geometry->location->lat;
                $long = $output->results[0]->geometry->location->lng;
                $data['list_review'][$k]['lat'] = $lat;
                $data['list_review'][$k]['lon'] = $long;
                $data['list_review'][$k]['rating'] = $data['rating'];
            }
            $data['place'] = json_encode($data['list_review'], JSON_UNESCAPED_UNICODE);
            $review = $this->Reviews->patchEntity($review, $data);
            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('The review has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The review could not be saved. Please, try again.'));
        }
        $categories = $this->Reviews->Categories->find('list', ['limit' => 200])->where(['parent_id' => 63]);
        $locations = $this->Reviews->Locations->find('list');
        $this->set(compact('review', 'categories', 'locations', 'list_images'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Review id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $review = $this->Reviews->get($id);
        if ($this->Reviews->delete($review)) {
            $this->Flash->success(__('The review has been deleted.'));
        } else {
            $this->Flash->error(__('The review could not be deleted. Please, try again.'));
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
