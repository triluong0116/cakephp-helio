<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Blogs Controller
 *
 * @property \App\Model\Table\BlogsTable $Blogs
 *
 * @method \App\Model\Entity\Blog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlogsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $blogs = $this->paginate($this->Blogs);

        $this->set(compact('blogs'));
    }

    /**
     * View method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $slug = $this->request->getParam('slug');
//        $blog = $this->Blogs->get($id);
        $blog = $this->Blogs->find()->where(['slug' => $slug])->first();
        $title = "Cẩm nang hướng dẫn";
        $headerType = 1;
        $this->set('blog', $blog);
        $this->set(compact('title', 'headerType', 'blog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $blog = $this->Blogs->newEntity();
        if ($this->request->is('post')) {
            $blog = $this->Blogs->patchEntity($blog, $this->request->getData());
            if ($this->Blogs->save($blog)) {
                $this->Flash->success(__('The blog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blog could not be saved. Please, try again.'));
        }
        $users = $this->Blogs->Users->find('list', ['limit' => 200]);
        $this->set(compact('blog', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $blog = $this->Blogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $blog = $this->Blogs->patchEntity($blog, $this->request->getData());
            if ($this->Blogs->save($blog)) {
                $this->Flash->success(__('The blog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blog could not be saved. Please, try again.'));
        }
        $users = $this->Blogs->Users->find('list', ['limit' => 200]);
        $this->set(compact('blog', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $blog = $this->Blogs->get($id);
        if ($this->Blogs->delete($blog)) {
            $this->Flash->success(__('The blog has been deleted.'));
        } else {
            $this->Flash->error(__('The blog could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function agencyP1() {
        $this->loadModel('Configs');
        $this->loadModel('Promotes');
        $today = date('Y-m-d');
        $promotes = $this->Promotes->find()->contain(['Locations', 'Hotels'])->where(['end_date >=' => $today]);
        if($promotes){
            $this->set(compact('promotes'));
        }
        $config = $this->Configs->find()->where(['type' => "chinh-sach-cong-tac-vien"])->first();
        $headerType = 1;
        $title = "CHÍNH SÁCH CỘNG TÁC VIÊN";
        $this->set(compact('headerType', 'title', 'config'));
    }

    public function agencyP2() {
        $this->loadModel('Configs');
        $headerType = 1;
        $title = "CHÍNH SÁCH CỘNG TÁC VIÊN";
        $config = $this->Configs->find()->where(['type' => "must-go-la-gi"])->first();
        $this->set(compact('headerType', 'title', 'config'));
    }

    public function agencyP3() {
        $this->loadModel('Blogs');
        $configs = $this->Blogs->find();
        $headerType = 1;
        $title = "CHÍNH SÁCH CỘNG TÁC VIÊN";
        $this->set(compact('headerType', 'title', 'configs'));
    }

    public function agencyP4() {
        $this->loadModel('Questions');
        $questions = $this->Questions->find();
        $headerType = 1;
        $title = "CHÍNH SÁCH CỘNG TÁC VIÊN";
        $this->set(compact('headerType', 'title', 'questions'));
    }

    public function paymentmethod() {
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "huong-dan-thanh-toan"])->first();
        $headerType = 1;
        $title = "HƯỚNG DẪN THANH TOÁN";
        $this->set(compact('headerType', 'title', 'config'));
    }
    public function usemethod() {
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "dieu-khoan-su-dung"])->first();
        $headerType = 1;
        $title = "ĐIỀU KHOẢN SỬ DỤNG";
        $this->set(compact('headerType', 'title', 'config'));
    }
    public function secretpolicy() {
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "chinh-sach-rieng-tu-bao-mat"])->first();
        $headerType = 1;
        $title = "CHÍNH SÁCH RIÊNG TƯ, BẢO MẬT";
        $this->set(compact('headerType', 'title', 'config'));
    }
    public function simplequestion() {
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "cau-hoi-thuong-gap"])->first();
        $headerType = 1;
        $title = "CHÍNH SÁCH RIÊNG TƯ, BẢO MẬT";
        $this->set(compact('headerType', 'title', 'config'));
    }
    public function bestprice() {
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "chinh-sach-cam-ket-ga-tot-nhat"])->first();
        $headerType = 1;
        $title = "CHÍNH SÁCH RIÊNG TƯ, BẢO MẬT";
        $this->set(compact('headerType', 'title', 'config'));
    }

    public function dispute() {
        $this->loadModel('Configs');
        $config = $this->Configs->find()->where(['type' => "giai-quyet-tranh-chap"])->first();
        $headerType = 1;
        $title = "CHÍNH SÁCH GIẢI QUYẾT TRANH CHẤP";
        $this->set(compact('headerType', 'title', 'config'));
    }
}
