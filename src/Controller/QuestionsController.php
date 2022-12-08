<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Questions Controller
 *
 * @property \App\Model\Table\QuestionsTable $Questions
 * @property \App\Model\Table\UsersTable $Users
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
            'contain' => ['Users']
        ];
        $questions = $this->paginate($this->Questions);

        $this->set(compact('questions'));
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
            $question = $this->Questions->patchEntity($question, $this->request->getData());
            if ($this->Questions->save($question)) {
                $this->Flash->success(__('The question has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The question could not be saved. Please, try again.'));
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
            $question = $this->Questions->patchEntity($question, $this->request->getData());
            if ($this->Questions->save($question)) {
                $this->Flash->success(__('The question has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The question could not be saved. Please, try again.'));
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

    public function agencyquiz() {
//        $this->autoRender = false;
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => '', 'count' => 0, 'total' => 0];
        $this->loadModel('Users');

        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $count = 0;
            $totalQuestion = $this->Questions->find()->count();
//            dd($data);
            foreach ($data as $key => $answer) {
                $quiz = $this->Questions->get($key);
                
//                dd($totalQuestion);
//                dd($answer);
                $contentAnswers = json_decode($quiz->answer, true);
                foreach ($contentAnswers as $contentAnswer) {
                    if (isset($contentAnswer['is_correct'])) {
                        $correctAnswer = $contentAnswer;
                        break;
                    }

//                    dd($contentAnswer);
                }
                if ($answer == $correctAnswer['content'])
                    $count++;
            }
            $score = $count . "/" . $totalQuestion;
            $user = $this->Users->get($this->Auth->user('id'));
            $user = $this->Users->patchEntity($user, ['score_test' => $score]);
            $this->Users->save($user);
//            dd($count); chrome dau>?????????
            $response['success'] = true;
            $response['count'] = $count;
            $response['total'] = $totalQuestion;

            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            dd($output);

            return $output;
        }
    }

}
