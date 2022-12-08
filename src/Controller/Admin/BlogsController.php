<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Blogs Controller
 *
 * @property \App\Model\Table\BlogsTable $Blogs
 *
 * @method \App\Model\Entity\Blog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlogsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 10];
        $agencies = $this->paginate($this->Blogs);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_questions = $this->Blogs->find()->where([
                'Blogs.content LIKE' => '%' . $data . '%',
                'Blogs.title LIKE' => '%' . $data . '%'
            ]);
            $number = $list_object_questions->count();
            $questions = $this->paginate($list_object_questions);
            $this->set(compact('agencies', 'number', 'data'));
        } else
            $this->set(compact('agencies'));
    }

    /**
     * View method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $agency = $this->Blogs->get($id);
//        dd($agency);
        $this->set(compact('agency'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $blog = $this->Blogs->newEntity();
        if ($this->request->is('post')) {
            $this->loadComponent('Upload');
            $data = $this->request->getData();
            $blog = $this->Blogs->patchEntity($blog, $data);
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
    public function edit($id = null)
    {
        $blog = $this->Blogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->loadComponent('Upload');
            $data = $this->request->getData();
//            if ($data['thumbnail']['error'] == 0) {
//                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
//                $data['thumbnail'] = $thumbnail;
//            } else {
//                $data['thumbnail'] = $data['thumbnail_edit'];
//            }
            $blog = $this->Blogs->patchEntity($blog, $data);
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
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $blog = $this->Blogs->get($id);
        if ($this->Blogs->delete($blog)) {
            $this->Flash->success(__('The blog has been deleted.'));
        } else {
            $this->Flash->error(__('The blog could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
