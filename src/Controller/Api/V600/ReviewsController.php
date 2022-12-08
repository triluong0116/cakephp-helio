<?php
namespace App\Controller\Api;

use App\Controller\AppController\V600;

/**
 * Reviews Controller
 *
 * @property \App\Model\Table\ReviewsTable $Reviews
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Review[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail', 'viewall']);
    }

    public function lists() {
        $this->loadModel('Categories');
        $this->paginate = [
            'limit' => 10
        ];
        $location_id = $this->getRequest()->getQuery('location_id');
        $rating = $this->getRequest()->getQuery('rating');
        $price = $this->getRequest()->getQuery('price');
        $condition = [];
        if ($location_id) {
            $condition['location_id'] = $location_id;
        }
        if ($rating) {
            $condition['rating'] = $rating;
        }
        if ($price) {
            $price_arr = explode('-', $price);
            //do later
        }
        $categories = $this->Categories->find()->where(['Categories.parent_id' => 63])->toArray();

        foreach ($categories as $k => $category) {
            $reviews = $this->Reviews->find()->where(['category_id' => $category['id'], 'location_id' => $location_id]);
            $count = $reviews->limit(4)->count();
            $reviews = $reviews->toArray();
            foreach ($reviews as $key => $review) {
                $reviews[$key]['url_detail'] = \Cake\Routing\Router::url(['_name' => 'review.view', 'slug' => $review['slug']], true);
            }
            $categories[$k]['reviews'] = $reviews;
            $categories[$k]['review_count'] = $count;
        }
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $categories,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id) {
        $review = $this->Reviews->get($id);
        $review->place = json_decode($review->place, true);
        $review->media = json_decode($review->media, true);
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $review,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function viewall($location_id, $category_id) {
        $this->paginate = [
            'limit' => 10
        ];
        $limit = 10;
        $page = $this->getRequest()->getQuery('page');
        $reviews = $this->Reviews->find()->where(['location_id' => $location_id, 'category_id' => $category_id])->limit($limit)->page($page)->toArray();
        foreach ($reviews as $key => $review) {
            $reviews[$key]->url_detail = \Cake\Routing\Router::url(['_name' => 'review.view', 'slug' => $review['slug']], true);
        }
        $this->set([
            'status' => STT_SUCCESS,
            'data' => $reviews,
            '_serialize' => ['status', 'data']
        ]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Categories', 'Locations']
        ];
        $reviews = $this->paginate($this->Reviews);

        $this->set(compact('reviews'));
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
        $review = $this->Reviews->newEntity();
        if ($this->request->is('post')) {
            $review = $this->Reviews->patchEntity($review, $this->request->getData());
            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('The review has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The review could not be saved. Please, try again.'));
        }
        $categories = $this->Reviews->Categories->find('list', ['limit' => 200]);
        $locations = $this->Reviews->Locations->find('list', ['limit' => 200]);
        $this->set(compact('review', 'categories', 'locations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Review id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $review = $this->Reviews->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $review = $this->Reviews->patchEntity($review, $this->request->getData());
            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('The review has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The review could not be saved. Please, try again.'));
        }
        $categories = $this->Reviews->Categories->find('list', ['limit' => 200]);
        $locations = $this->Reviews->Locations->find('list', ['limit' => 200]);
        $this->set(compact('review', 'categories', 'locations'));
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
}
