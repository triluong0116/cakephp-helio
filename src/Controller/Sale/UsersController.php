<?php

namespace App\Controller\Sale;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Log\Log;
use Cake\Validation\Validation;
use Google\Cloud\Firestore\FirestoreClient;
use Cake\Datasource\ConnectionManager;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\ChatsTable $Chats
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
        } else {
            $this->set(compact('users'));
        }
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
                $firestore = new FirestoreClient([
                    'projectId' => 'mustgoproj',
                ]);
                $timeNow = time();
                $roomId = $user->id . '-' . $user->parent_id;
                if (!$firestore->collection('chatroom')->document($roomId)->snapshot()->exists()) {
                    $firestore->collection('chatroom')->document($roomId)->create([
                        'createdAt' => $timeNow,
                        'id' => $user->id . '-' . $user->parent_id,
                        'latestMessage' => [
                            'createdAt' => $timeNow,
                            'createdBy' => $user->id,
                            'text' => $data['message']
                        ],
                        'is_read' => 0,
                        'members' => [$user->id, $user->parent_id],
                        'updatedAt' => $timeNow
                    ]);
                    $document = $firestore->collection('chatroom')->document($roomId);
                    $document->collection('messages')->document($timeNow)->create([
                        'createdAt' => $timeNow,
                        'createdBy' => $user->id,
                        'id' => $timeNow,
                        'text' => $timeNow,
                        'is_read' => 0,
                        'type' => 1
                    ]);
                }
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
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 2 || $user['role_id'] === 5 || $user['role_id'] === 7)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'sale'));
        return parent::isAuthorized($user);
    }

    public function changePassword()
    {
        if ($this->request->is('post')) {
            $data = $this->request->data();
            $res['success'] = false;
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
            $query = $this->Users->find()->where([
                'email' => $data['email'],
                'role_id IN' => [2, 5]
            ]);
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

    public function message()
    {
        $this->loadModel('Chats');
        $sale = $this->Auth->user();
//        $search = $this->getRequest()->getData();
//        $condition = [];
//        if (!empty($search)){
//            $text = trim($this->getRequest()->getData('search'));
//            $condition['OR'] = [
//                'Users.username LIKE' => '%' . $text . '%',
//                'Users.screen_name LIKE' => '%' . $text . '%',
//                'Users.email LIKE' => '%' . $text . '%',
//                'Users.phone LIKE' => '%' . $text . '%'
//            ];
//        }
        if ($sale) {
            if ($sale['role_id'] == 2) {
                $chatRoomIds = [];
                $listChatRoomId = [];
                $data = $this->Users->find()->where(['parent_id' => $sale['id']])->all()->toArray();
                $list = $this->Chats->find()->group('chat_room_id')->orderDesc('id')->all()->toArray();
                foreach ($list as $arr) {
                    $idRoom = explode('-', $arr['chat_room_id']);
                    $idSale = isset($idRoom[1]) ? intval($idRoom[1]) : 0;
                     if ($idSale == $sale['id']) {
                         $listChatRoomId[] = $arr['chat_room_id'];
                     }
                }
                foreach ($data as $chat) {
                    $listChatRoomId[] = $chat['id']."-".$sale['id'];
                }
                $listChatRoomId = array_unique($listChatRoomId);
                $user_id = $sale['id'];
                $listRoom = [];
                foreach ($listChatRoomId as $ChatId) {
                    $id = explode('-', $ChatId);
//                    $dataChat = $this->Chats->find()->where(['chat_room_id' => $ChatId])->orderDesc('id')->first();
//                    if (isset($dataChat)) {
//                        $user = $this->Users->find()->where(['id' => $id[0]])->first();
//                        $chatRoomId['user_id'] = $user['id'];
//                        $chatRoomId['user_name'] = $user['screen_name'];
//                        $chatRoomId['user_phone'] = $user['phone'];
//                        $chatRoomId['user_avatar'] = $user['avatar'];
//                        $chatRoomId['roomId'] = $dataChat['chat_room_id'];
//                        $listRoom[] = $dataChat['chat_room_id'];
//                        if (isset($dataChat['msg']) && !empty('msg')) {
//                            $chatRoomId['msg'] = $dataChat['msg'];
//                        }
//                        if (isset($dataChat['img']) && !empty('img')) {
//                            $chatRoomId['img'] = $dataChat['img'];
//                        }
//                        $chatRoomId['created'] = $dataChat['created'];
//                        $chatRoomIds[$user['id']] = $chatRoomId;
//                    } else {
                    $user = $this->Users->find()->where(['id' => $id[0]])->first();
                    $chatRoomId['user_id'] = $user['id'];
                    $chatRoomId['user_name'] = $user['screen_name'];
                    $chatRoomId['user_phone'] = $user['phone'];
                    $chatRoomId['user_avatar'] = $user['avatar'];
                    $chatRoomId['roomId'] = $ChatId;
                    $listRoom[] = $ChatId;
                    $chatRoomIds[$user['id']] = $chatRoomId;
//                    }
                }
                $this->set(compact('chatRoomIds',  'user_id', 'listRoom' , 'listChatRoomId'));
//                return $this->render('message',  'user_id', 'listRoom' , 'listChatRoomId');
            }
        }
    }

    public function getMessage()
    {
        $this->loadModel('Chats');
        $this->viewBuilder()->enableAutoLayout(false);
        $data = $this->getRequest()->getData();
        $id = explode('-', $data['roomId']);
        $userId = $id[0];
        $sale = $this->Auth->user();
        $room_id = $userId.'-'.$sale['id'];
        $dataMessage = $this->Chats->find()->where(['chat_room_id' => $room_id])->toArray();
        $dataUser = $this->Users->find()->where(['id' => $userId])->first();
        $data = [];
        $chatRoomId = $room_id;
        $chat = $this->Chats->find()->where(['chat_room_id' => $room_id, 'user_id'=>$userId])->limit(200)->last();
        if ($chat && $chat->is_read == 0 && $chat->user_id != $sale['id']){
            $timeNow = time();
//            $firestore = new FirestoreClient([
//                'projectId' => 'mustgoproj',
//            ]);
//            if ($firestore->collection('chatroom')->document($room_id)->snapshot()->exists()) {
//                $document = $firestore->collection('chatroom')->document($room_id);
//                $document->set([
//                    'latestMessage' => [
//                        'createdAt' => $chat['sessionId'],
//                        'createdBy' => $chat->user_id,
//                        'text' => $chat['msg'],
//                        'img' => $chat['img'],
//                    ],
//                    'is_read' => 1,
//                    'is_read_number' => 0,
//                    'sale_id' => $sale['id'],
//                    'updatedAt' => $timeNow
//                ]);
//            }
            $this->Chats->query()->update('chats')->set(['is_read' => 1])->where(['chat_room_id' => $room_id, 'user_id'=>$userId,'is_read'=> 0])->execute();
        }

        $data['user'] = $dataUser;
        $data['roomId'] = $room_id;
        $data['dataMessage'] = $dataMessage;
        $this->set(compact('data'));
//        dd($this->render('getMessage'));
//        return $this->render('getMessage', 'chatRoomId', 'userId');
    }

    public function listMessage()
    {
        $this->loadModel('Chats');
        $this->viewBuilder()->enableAutoLayout(false);
        $sale = $this->Auth->user();
        $search = $this->getRequest()->getData();
        $condition = [];
        if (!empty($search)){
            $text = trim($this->getRequest()->getData('search'));
            $condition['OR'] = [
                'Users.username LIKE' => '%' . $text . '%',
                'Users.screen_name LIKE' => '%' . $text . '%',
                'Users.email LIKE' => '%' . $text . '%',
                'Users.phone LIKE' => '%' . $text . '%'
            ];
        }
        if ($sale) {
            if ($sale['role_id'] == 2) {
                $chatRoomIds = [];
                $data = $this->Users->find()->where(
                    [
                        'parent_id' => $sale['id'],
                        $condition
                    ])->all()->toArray();
                $listChatRoomId = [];
                foreach ($data as $k => $chat) {
                    $listChatRoomId[$k] = $chat['id']."-".$sale['id'];
                }
                $user_id = $sale['id'];
                $listChatRoomId = array_unique($listChatRoomId);
                $listRoom = [];
                foreach ($listChatRoomId as $ChatId) {
                    $id = explode('-', $ChatId);
                    $dataChat = $this->Chats->find()->where(['chat_room_id' => $ChatId])->first();
                    $user = $this->Users->find()->where(['id' => $id[0]])->first();
                    $chatRoomId['user_id'] = $user['id'];
                    $chatRoomId['user_name'] = $user['screen_name'];
                    $chatRoomId['user_phone'] = $user['phone'];
                    $chatRoomId['user_avatar'] = $user['avatar'];
                    $chatRoomId['roomId'] = $ChatId;
                    $listRoom[] = $ChatId;
                    $chatRoomIds[$user['id']] = $chatRoomId;

                }
                $this->set(compact('chatRoomIds',  'user_id', 'listRoom' , 'listChatRoomId'));
                return $this->render('listMessage');
            }
        }
    }
}
