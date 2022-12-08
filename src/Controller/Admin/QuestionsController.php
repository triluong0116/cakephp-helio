<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Questions Controller
 *
 * @property \App\Model\Table\QuestionsTable $Questions
 *
 * @method \App\Model\Entity\Question[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QuestionsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'limit' => 10,
        ];
        $questions = $this->paginate($this->Questions);

        if($this->request->is('get') && $this->request->getQuery('search')){
            $data = trim($this->request->getQuery('search'));
            $list_object_questions = $this->Questions->find()->where([
                'Questions.content LIKE' => '%'.$data.'%',
            ]);
            $number = $list_object_questions->count();
            $questions = $this->paginate($list_object_questions);
            $this->set(compact('questions', 'number', 'data'));
            $this->render('search');
        } else $this->set(compact('questions'));
    }

    /**
     * View method
     *
     * @param string|null $id Question id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $question = $this->Questions->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('question', $question);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $question = $this->Questions->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $isCheck = false;
            foreach ($data['answer'] as $answer) {
                if (isset($answer['is_correct']) && !empty($answer['is_correct'])) {
                    $isCheck = true;
                    break;
                }
            }
            if ($isCheck) {
                $data['user_id'] = $this->Auth->user('id');
                $data['answer'] = json_encode($data['answer'], JSON_UNESCAPED_UNICODE);
                $question = $this->Questions->patchEntity($question, $data);
                if ($this->Questions->save($question)) {
                    $this->Flash->success(__('The question has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The question could not be saved. Please, try again.'));
            } else {
//                debug($this->request->getData());
                $this->Flash->error(__('Phải chọn câu trả lời đúng'));
            }
        }
        $users = $this->Questions->Users->find('list', ['limit' => 200]);
        $this->set(compact('question', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Question id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $question = $this->Questions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $isCheck = false;
            foreach ($data['answer'] as $answer) {
                if (isset($answer['is_correct']) && !empty($answer['is_correct'])) {
                    $isCheck = true;
                    break;
                }
            }
            if ($isCheck) {
                $data['user_id'] = $this->Auth->user('id');
                $data['answer'] = json_encode($data['answer'], JSON_UNESCAPED_UNICODE);
                $question = $this->Questions->patchEntity($question, $data);
                if ($this->Questions->save($question)) {
                    $this->Flash->success(__('The question has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The question could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('Phải chọn câu trả lời đúng'));
            }
        }
        $users = $this->Questions->Users->find('list', ['limit' => 200]);
        $this->set(compact('question', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Question id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $question = $this->Questions->get($id);
        if ($this->Questions->delete($question)) {
            $this->Flash->success(__('The question has been deleted.'));
        } else {
            $this->Flash->error(__('The question could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
