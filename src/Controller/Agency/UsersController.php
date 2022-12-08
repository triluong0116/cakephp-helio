<?php

namespace App\Controller\Agency;

use App\Controller\AppController;

//use App\Application\Facebook;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\FanpagesTable $Fanpages 
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['logout', 'loginViaFb', 'login']);
    }

    public function login() {
        $this->viewBuilder()->setLayout('backend-login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

    public function loginViaFb() {
        $this->loadModel('Fanpages');
        $fb = $this->viewVars['fbGlobal'];
        $helper = $fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state'] = $_GET['state'];
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }

        try {

            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            echo 'No OAuth data could be obtained from the signed request. User has not authorized your app yet.';
            exit;
        }

        try {

            $response = $fb->get('me?fields=id,email,name,picture.width(800).height(800)', $accessToken->getValue());
            $response = $response->getDecodedBody();
            $user = $this->Users->find()->where(['username' => 'fb' . $response['id']])->first();
            if (!$user) {
                $user = $this->Users->newEntity();
            }
            $dataUser = [
                'username' => 'fb'.$response['id'],
                'screen_name' => $response['name'],
                'email' => $response['email'],
                'avatar' => $response['picture']['data']['url'],
                'access_token' => $accessToken->getValue(),
                'role_id' => 3
            ];
            $user = $this->Users->patchEntity($user, $dataUser);
            $this->Users->save($user);
            $this->Auth->setUser($user);
            
            $responsePage = $fb->get('me/accounts', $accessToken->getValue());
            $responsePage = $responsePage->getDecodedBody();
            
            foreach ($responsePage['data'] as $item) {
                $fanpage = $this->Fanpages->find()->where(['page_id' => $item['id']])->first();
                if (!$fanpage) {
                    $fanpage = $this->Fanpages->newEntity();
                }
                $data_fanpage = [
                    'user_id' => $user->id,
                    'name' => $item['name'],
                    'page_id' => $item['id'],
                    'access_token' => $item['access_token']                    
                ];
                $fanpage = $this->Fanpages->patchEntity($fanpage, $data_fanpage);
                $this->Fanpages->save($fanpage);
            }            
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return $this->redirect(['controller' => 'Dashboards', 'action' => 'index', 'prefix' => 'agency']);
    }

    public function logout() {
        $this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
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
