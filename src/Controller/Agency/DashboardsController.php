<?php

namespace App\Controller\Agency;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class DashboardsController extends AppController {

    public function initialize() {
        parent::initialize();
//        $this->Auth->allow(['logout']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $this->viewBuilder()->setLayout('backend');
//        $authors = $this->paginate($this->Authors);
//
//        $this->set(compact('authors'));
//        $this->set('_serialize', ['authors']);
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
