<?php

namespace App\Controller;

use App\Controller\AppController;

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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Locations');
        $this->loadModel('Categories');

        $this->paginate = [
            'limit' => 6
        ];

        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');

        $listLocation = explode(',', $filterLocation);
        $listPrice = explode(',', $filterPrice);
        $listRating = explode(',', $filterRating);

//        dd($filterPrice);

        $condition = $order = [];

        if ($filterLocation) {
            $condition['Reviews.location_id IN'] = $listLocation;
        }
        if ($filterRating) {
            $condition['rating IN'] = $listRating;
        }
        if ($filterPrice) {
            $price_condition = [];
            foreach ($listPrice as $key => $price) {
                $price_arr = explode('-', $price);
                if (count($price_arr) == 2) {
                    $price_condition[$key]['price_start >='] = $price_arr[0];
                    $price_condition[$key]['price_end <='] = $price_arr[1];
                } else {
                    if ($price == '2000000') {
                        $price_condition[$key]['price_end < '] = $price;
                    }
                    if ($price == '10000000') {
                        $price_condition[$key]['price_start > '] = $price;
                    }
                }
            }
            $condition['OR'] = $price_condition;
        }
//        dd($listLocation);
        $title = "REVIEW";
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Review', 'href' => \Cake\Routing\Router::url('/review', true)],
        ];

        $paginate = $this->Reviews->find()->contain('Locations')->andWhere($condition)->order(['Reviews.created' => 'DESC']);
        $locations = $this->Locations->find();
        $sidebarReviews = $this->Reviews->find()->contain('Locations')->order(['Reviews.created' => 'DESC'])->limit(4);


        $listreviews = $this->Categories->find()->contain(['Reviews', 'Reviews.Locations'])->where(['parent_id' => 63]);

//        $listreviews  = $this->Categories->find()
//            ->contain(['Reviews' => function($query) use ($listLocation) {
//                return $query->where (['location_id IN'=>$listLocation]);
//            }])
//            ->where(['parent_id'=>63]);


//        dd($paginate->toArray());
        $reviews = $this->paginate($paginate);
//        $newReviews= $this->Reviews->find()->contain('Locations')->order(['Reviews.created'=>'DESC'])->limit(4);
        $this->set(compact('reviews', 'headerType', 'breadcrumbs', 'title', 'locations', 'sidebarReviews', 'listLocation', 'listPrice', 'listRating', 'listreviews'));
    }

    public function location()
    {
        $this->loadModel('Locations');
        $locations = $this->Locations->find()->toArray();
        $title = 'Tất cả điểm đến';
        $headerType = 1;
        $this->set(compact('headerType', 'locations', 'title'));
    }

    public function locationDetail()
    {
        $this->loadModel('Locations');
        $this->loadModel('Categories');


        $slug = $this->request->getParam('slug');
        $location = $this->Locations->find()->where(['slug' => $slug])->first();
        $currentLocation = $location;

        $this->paginate = [
            'limit' => 6
        ];

        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');

//

        $listLocation = explode(',', $filterLocation);
        $listLocation = array_filter($listLocation);

        $listPrice = explode(',', $filterPrice);
        $listPrice = array_filter($listPrice);

        $listRating = explode(',', $filterRating);
        $listRating = array_filter($listRating);

        $listLocation[] = $location->id;
        $outputSlider = '';
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }
        $condition = $order = [];

        if ($listLocation) {
            $condition['location_id IN'] = $listLocation;
        }
        if ($listRating) {
            $condition['rating IN'] = $listRating;
        }

        if ($listPrice) {
//            dd($filterPrice);
            $price_condition = [];
            foreach ($listPrice as $key => $price) {
                $price_arr = explode('-', $price);
                if (count($price_arr) == 2) {
                    $price_condition[$key]['price_start >='] = $price_arr[0];
                    $price_condition[$key]['price_end <='] = $price_arr[1];
                } else {
//                    echo 1;die;
                    if ($price == '2000000') {
                        $price_condition[$key]['price_end < '] = $price;
                    }
                    if ($price == '10000000') {
                        $price_condition[$key]['price_start > '] = $price;
                    }
                }
            }
            $condition['OR'] = $price_condition;
        }
//        dd($condition);
//        dd($condition);
        $title = "REVIEW";
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Review', 'href' => \Cake\Routing\Router::url('/review', true)],
        ];
        $categories = $this->Categories->find()->contain([
            'Reviews' => function ($query) use ($condition) {
                if ($condition) {
                    return $query->where($condition);
                } else {
                    return $query;
                }
            },
            'Reviews.Locations'
        ])->where(['Categories.parent_id' => 63]);
        $locations = $this->Locations->find()->where(['id !=' => $location->id]);

        $sidebarReviews = $this->Reviews->find()->contain('Locations')->order(['Reviews.created' => 'DESC'])->limit(4);
        $this->set(compact('headerType', 'breadcrumbs', 'title', 'locations', 'currentLocation', 'sidebarReviews', 'listLocation', 'listPrice', 'listRating', 'categories', 'outputSlider'));
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
        $slug = $this->request->getParam('slug');
        $newReviews = $this->Reviews->find()
            ->contain('Locations')
            ->where(['Reviews.slug' => $slug])->first();

//        dd(json_decode($newReviews->place));
        $lists = json_decode($newReviews->place, true);
        $listPlaces = [];
        foreach ($lists as $list) {
            $listPlaces[] = $list['address'];
        }
        $title = mb_strtoupper($newReviews->title);
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Cẩm nang', 'href' => \Cake\Routing\Router::url('/cam-nang', true)],
            ['title' => $newReviews->title, 'href' => '#']
        ];
        $reviewN = $this->Reviews->find()->order(['Reviews.created' => 'DESC'])->limit(4);
        $this->set(compact('newReviews', 'reviewN', 'headerType', 'title', 'breadcrumbs', 'listPlaces'));
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
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
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

    public function viewall()
    {

        $this->loadModel('Locations');
        $this->loadModel('Categories');
        $slug = $this->request->getParam('slug');

        $locationId = $this->request->getQuery('location');
        if ($locationId) {
            $location = $this->Locations->find()->where(['id' => $locationId])->first();
        } else {
            $location['id'] = 0;
        }
//
        $this->paginate = [
            'limit' => 6
        ];

        $filterLocation = $this->request->getQuery('location');
        $filterPrice = $this->request->getQuery('price');
        $filterRating = $this->request->getQuery('rating');
        $filterSlider = $this->request->getQuery('slider-price');

        $listLocation = explode(',', $filterLocation);
        $listLocation = array_filter($listLocation);

        $listPrice = explode(',', $filterPrice);
        $listPrice = array_filter($listPrice);

        $listRating = explode(',', $filterRating);
        $listRating = array_filter($listRating);

        $outputSlider = '';
        if ($filterSlider) {
            $listPrice[] = $filterSlider;
            $sliderArray = explode('-', $filterSlider);
            $outputSlider = implode(',', $sliderArray);
        }

        $condition = $order = [];

        if ($listLocation) {
            $condition['location_id IN'] = $listLocation;
        }
        if ($listRating) {
            $condition['rating IN'] = $listRating;
        }
        if ($filterPrice) {
            $price_condition = [];
            foreach ($listPrice as $key => $price) {
                $price_arr = explode('-', $price);
                if (count($price_arr) == 2) {
                    $price_condition[$key]['price_start >='] = $price_arr[0];
                    $price_condition[$key]['price_end <='] = $price_arr[1];
                } else {
                    if ($price == '2000000') {
                        $price_condition[$key]['price_end < '] = $price;
                    }
                    if ($price == '10000000') {
                        $price_condition[$key]['price_start > '] = $price;
                    }
                }
            }
            $condition['OR'] = $price_condition;
        }
        $title = "REVIEW";
        $headerType = 1;
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'href' => \Cake\Routing\Router::url('/', true)],
            ['title' => 'Review', 'href' => \Cake\Routing\Router::url('/review', true)],
        ];
        $condition['Categories.slug'] = $slug;

        $category = $this->Categories->find()->where(['slug' => $slug])->first();
        $listReviews = $this->Reviews->find()->contain(['Locations', 'Categories'])->where($condition);

//        $paginate = $this->Reviews->find()->contain('Locations')->andWhere($condition)->order(['Reviews.created' => 'DESC']);
        $locations = $this->Locations->find()->where(['id !=' => $location['id']]);
        $sidebarReviews = $this->Reviews->find()->contain('Locations')->order(['Reviews.created' => 'DESC'])->limit(4);
//        dd($paginate->toArray());
//        $reviews = $this->paginate($paginate);
//        $newReviews= $this->Reviews->find()->contain('Locations')->order(['Reviews.created'=>'DESC'])->limit(4);
        $this->set(compact('reviews', 'headerType', 'breadcrumbs', 'categories', 'title', 'location', 'locations', 'sidebarReviews', 'listLocation', 'listPrice', 'listRating', 'listReviews', 'outputSlider', 'category'));

    }


}
