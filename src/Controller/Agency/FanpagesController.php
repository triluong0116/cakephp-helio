<?php
namespace App\Controller\Agency;

use App\Controller\AppController;

/**
 * Fanpages Controller
 *
 * @property \App\Model\Table\FanpagesTable $Fanpages
 *
 * @method \App\Model\Entity\Fanpage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FanpagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $fanpages = $this->paginate($this->Fanpages->find()->where(['user_id' => $this->Auth->user('id')]));

        $this->set(compact('fanpages'));
    }    

    /**
     * Delete method
     *
     * @param string|null $id Fanpage id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fanpage = $this->Fanpages->get($id);
        if ($this->Fanpages->delete($fanpage)) {
            $this->Flash->success(__('The fanpage has been deleted.'));
        } else {
            $this->Flash->error(__('The fanpage could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function getUserFacebookInfo() {
        $user_id = $this->Auth->user('id');
        $fb = $this->viewVars['fbGlobal'];
        $this->Util->getListFanPage($fb, $user_id);        
        return $this->redirect(['action' => 'index']);
    }
    
    public function isAuthorized($user) {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 3)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'agency'));
        return parent::isAuthorized($user);
    }
}
