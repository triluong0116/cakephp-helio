<?php

namespace App\Controller\Manager;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validation;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout', 'forgetPassword']);
    }

    public function index()
    {
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Roles']
        ];
        $condition = [
            'role_id' => 3,
            'parent_id' => $this->Auth->user('id')
        ];
//        dd($condition);
        $paginate = $this->Users->find()->where($condition);
        $users = $this->paginate($paginate);

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
//            dd($data);
            $list_object_users = $this->Users->find()->where([
                'role_id' => 3,
                'parent_id' => $this->Auth->user('id'),
                'OR' => [
                    'Users.username LIKE' => '%' . $data . '%',
                    'Users.screen_name LIKE' => '%' . $data . '%',
                    'Users.email LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_users->count();
            $users = $this->paginate($list_object_users);
            $this->set(compact('users', 'number', 'data'));
        } else
            $this->set(compact('users'));
    }

    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['parent_id'] = $this->Auth->user('id');
            $data['ref_code'] = $this->Util->generateRandomString(24);
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200])->where(['Roles.id' => 3]);
        $landtour_managers = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'screen_name'
        ])->where(['role_id' => 5]);
        $this->set(compact('user', 'roles', 'landtour_managers'));
    }

    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles'));
    }

    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles', 'Bookings', 'Comments']
        ]);
        $manager = $this->Users->get($user->parent_id);

        $this->set('user', $user);
        $this->set(compact('manager'));
    }

    public function signature($id = null)
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set('user', $user);
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('backend-login');
        if ($this->request->is('post')) {
            if (Validation::email($this->request->data['username'])) {
                $this->Auth->setConfig('authenticate', [
                    'Form' => [
                        'fields' => ['username' => 'email']
                    ]
                ]);
                $this->Auth->constructAuthenticate();
                $this->request->data['email'] = $this->request->data['username'];
                unset($this->request->data['username']);
            }
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

    public function logout()
    {
        $this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
    }


    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 6)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'manager'));
        return parent::isAuthorized($user);
    }

    public function changePassword()
    {
        if ($this->request->is('post')) {
            $data = $this->request->data();
            $res['success'] = FALSE;
            $user = $this->Users->get($this->Auth->user('id'));
            if ((new DefaultPasswordHasher)->check($data['oldPassword'], $user['password'])) {
                if ($data['newPassword'] == $data['confPassword']) {
                    $userEntity = $this->Users->get($this->Auth->user('id'));
                    $userEntity->password = $data['newPassword'];
                    if ($this->Users->save($userEntity)) {
                        $this->Flash->success('Thay đổi mật khẩu thành công');
                    }
                } else {
                    $this->Flash->error('Nhập lại mật khẩu không đúng với mật khẩu mới');
                }

            } else {
                $this->Flash->error('Mật khẩu cũ của bạn đã sai');
            }
        }
    }

    public function forgetPassword()
    {
        $this->viewBuilder()->setLayout('backend-login');
        $this->loadComponent('Email');
        if ($this->request->is('post')) {
            $data = $this->getRequest()->getData();
            $query = $this->Users->find()->where(['email' => $data['email'], 'role_id' => 2]);
            $user = $query->first();
            if ($user) {
                $pass = uniqid();
                $user = $this->Users->patchEntity($user, ['password' => $pass]);
                if ($this->Users->save($user)) {
                    $bodyEmail = "Chào bạn!";
                    $bodyEmail .= "<br />Bạn đã khôi phục thành công mật khẩu.";
                    $bodyEmail .= "<br />Mật khẩu mới của bạn là: <strong>" . $pass . "</strong>";
                    $bodyEmail .= "<br />Vui lòng đăng nhập và tiến hành đổi mật khẩu.";
                    $bodyEmail .= "<br />The Mustgo Team!";
                    $data_sendEmail = [
                        'to' => $data['email'],
                        'subject' => 'Khôi phục mật khẩu thành công',
                        'title' => 'Khôi phục mật khẩu thành công',
                        'body' => $bodyEmail
                    ];
                    if ($this->Email->sendEmailForgotPassword($data_sendEmail)) {
                        $this->Flash->success('Gửi mail thành công');
                    } else {
                        $this->Flash->error('Không gửi được mail, vui lòng thử lại');
                    }
                } else {
                    $this->Flash->error('Đã có lỗi sảy ra vui lòng thử lại');
                }
            } else {
                $this->Flash->error('Không tìm thấy user với mail tương ứng, vui lòng thử lại');
            }
        }
    }
}
