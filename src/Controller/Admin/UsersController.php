<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validation;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\DepositLogsTable $DepositLogs
 * @property \App\Model\Table\UserTransactionsTable $UserTransactions
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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('backend_new');
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Roles']
        ];
        $users = $this->paginate($this->Users->find()->where(['is_active' => 1]));

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_users = $this->Users->find()->where([
                'OR' => [
                    'Users.username LIKE' => '%' . $data . '%',
                    'Users.screen_name LIKE' => '%' . $data . '%',
                    'Users.email LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_users->count();
            $users = $this->paginate($list_object_users);
            $this->set(compact('users', 'number', 'data'));
            $this->render('search');
        } else
            $this->set(compact('users'));
    }

    public function indexDatatable()
    {
        // xử lý dữ liệu gửi lên để phân trang
        $datarequest = $this->request->getData();
        if (!isset($datarequest) || !$datarequest) {
            $datarequest = [
                "pagination" => [
                    "page" => "1",
                    "perpage" => "15",
                ],
            ];
        }
        if (isset($datarequest['query']['generalSearch'])) {
            $search = $datarequest['query']['generalSearch'];
        } else {
            $search = '';
        }
        $posStart = ($datarequest['pagination']['page'] - 1) * $datarequest['pagination']['perpage'];
        //end xử lý dữ liệu gửi lên để phân trang
        $list_object_users = $this->Users->find()->contain('roles')->where(['is_active' => 1])->where([
            'OR' => [
                'Users.username LIKE' => '%' . $search . '%',
                'Users.screen_name LIKE' => '%' . $search . '%',
                'Users.email LIKE' => '%' . $search . '%'
            ]
        ]);

        //cắt dữ liệu
        $dataRespone = $list_object_users->take($datarequest['pagination']['perpage'], $posStart)->toList();
        $response = [
            'meta' => [
                "page" => $datarequest['pagination']['page'],
                "pages" => number_format(count($list_object_users->toList()) / $datarequest['pagination']['perpage']),
                "perpage" => $datarequest['pagination']['perpage'],
                "total" => count($list_object_users->toList()),
            ],
            'data' => $dataRespone
        ];
//        dd($dataRespone);
        //end cắt dữ liệu
        $this->set([
            'my_response' => $response,
            '_serialize' => 'my_response',
        ]);
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function indexPartner()
    {
        $this->paginate = [
            'limit' => 10,
//            'contain' => ['Hotels']
        ];
        $users = $this->paginate($this->Users->find()->where(['role_id' => 5]));

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_users = $this->Users->find()->where([
                'OR' => [
                    'Users.username LIKE' => '%' . $data . '%',
                    'Users.screen_name LIKE' => '%' . $data . '%',
                    'Users.email LIKE' => '%' . $data . '%'
                ], 'role_id' => 5
            ]);
            $number = $list_object_users->count();
            $users = $this->paginate($list_object_users);
            $this->set(compact('users', 'number', 'data'));
            $this->render('search_partner');
        } else
            $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles', 'Bookings', 'Comments']
        ]);
        if ($user->parent_id == 3) {
            $manager = $this->Users->get($user->parent_id);
            $this->set(compact('manager'));
        }
        $this->set('user', $user);
    }

    public function viewPartner($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Bookings', 'Comments']
        ]);
        $this->set('user', $user);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if ($data['role_id'] == 3) {
                $data['ref_code'] = $this->Util->generateRandomString(24);
            }
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                if ($user->parent_id) {
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
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list');
        $managers = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'screen_name'
        ])->where(['role_id' => 2]);
        $landtour_managers = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'screen_name'
        ])->where(['role_id' => 5]);
        $this->set(compact('user', 'roles', 'managers', 'landtour_managers'));
    }

    public function addPartner()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['role_id'] = 5;
            $data['parent_id'] = 0;
            $data['is_active'] = 1;
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'indexPartner']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $hotels = $this->Users->Hotels->find('list');
        $this->set(compact('user', 'hotels'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Bookings');
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            dd($data);
            if (!isset($data['is_active'])) {
                $data['is_active'] = 0;
            }
            if ($data['parent_id'] != $user->parent_id) {
                $listUserHotelBookings = $this->Bookings->find()->where(['user_id' => $user->id, 'type !=' => LANDTOUR, 'status IN' => [-1, 0, 1, 2]]);
                foreach ($listUserHotelBookings as $booking) {
                    $booking = $this->Bookings->patchEntity($booking, ['sale_id' => $data['parent_id']]);
                    $this->Bookings->save($booking);
                }
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
            }
            if ($data['landtour_parent_id'] != $user->landtour_parent_id) {
                $listUserLandtourBookings = $this->Bookings->find()->where(['user_id' => $user->id, 'type' => LANDTOUR, 'status IN' => [-1, 0, 1, 2]]);
                foreach ($listUserLandtourBookings as $booking) {
                    $booking = $this->Bookings->patchEntity($booking, ['sale_id' => $data['landtour_parent_id']]);
                    $this->Bookings->save($booking);
                }
            }
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        if ($user->role_id == 3) {
            $managers = $this->Users->find('list', [
                'keyField' => 'id',
                'valueField' => 'screen_name'
            ])->where(['role_id' => 2]);
            $landtour_managers = $this->Users->find('list', [
                'keyField' => 'id',
                'valueField' => 'screen_name'
            ])->where(['role_id' => 5]);
            $this->set(compact('managers', 'landtour_managers'));
        }
        $this->set(compact('user', 'roles'));
    }

    public function editPartner($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (!isset($data['is_active'])) {
                $data['is_active'] = 0;
            }
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'indexPartner']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $hotels = $this->Users->Hotels->find('list', ['limit' => 200]);
        $this->set(compact('user', 'hotels'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function newUser()
    {
        $this->paginate = [
            'limit' => 10,
            'contain' => ['Roles']
        ];
        $users = $this->paginate($this->Users->find()->where(['is_active' => 0]));

        if ($this->request->is('get') && $this->request->getQuery('search')) {
            $data = trim($this->request->getQuery('search'));
            $list_object_users = $this->Users->find()->where([
                'OR' => [
                    'Users.username LIKE' => '%' . $data . '%',
                    'Users.screen_name LIKE' => '%' . $data . '%',
                    'Users.email LIKE' => '%' . $data . '%'
                ]
            ]);
            $number = $list_object_users->count();
            $users = $this->paginate($list_object_users);
            $this->set(compact('users', 'number', 'data'));
            $this->render('search');
        } else
            $this->set(compact('users'));
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('backend_new-login');
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

    public function addRevenue()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => '', 'message' => ''];
        $this->loadModel('Bookings');
        $this->loadModel('UserTransactions');
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $user = $this->Users->get($data['user_id']);
            $booking = $this->Bookings->get($data['booking_id'], ['contain' => ['Hotels', 'Vouchers', 'HomeStays', 'LandTours']]);
            $newRevenue = $user->revenue + $booking->revenue;
            $user = $this->Users->patchEntity($user, ['revenue' => $newRevenue]);
            $this->Users->save($user);
            $booking = $this->Bookings->patchEntity($booking, ['status' => 4]);
            $this->Bookings->save($booking);
            $logRevenue = $this->UserTransactions->newEntity();
            $dataLog = [];
            $dataLog['customer_name'] = $booking->full_name;
            $dataLog['booking_id'] = $booking->id;
            $dataLog['user_id'] = $user->id;
            $dataLog['revenue'] = $booking->revenue;
            if ($booking->type == HOTEL) {
                $reason = "Đặt booking khách sạn " . $booking->hotels->name . " thành công";
            }
            if ($booking->type == COMBO) {
                $reason = "Đặt booking combo " . $booking->combos->name . " thành công";
            }
            if ($booking->type == VOUCHER) {
                $reason = "Đặt booking voucher " . $booking->vouchers->name . " thành công";
            }
            if ($booking->type == LANDTOUR) {
                $reason = "Đặt booking landtour " . $booking->land_tours->name . " thành công";
            }
            if ($booking->type == HOMESTAY) {
                $reason = "Đặt booking homestay " . $booking->home_stays->name . " thành công";
            }
            $dataLog['reason'] = $reason;
            $logRevenue = $this->UserTransactions->patchEntity($logRevenue, $dataLog);
            $this->UserTransactions->save($logRevenue);

            if ($this->Bookings->save($booking) && $this->UserTransactions->save($logRevenue) && $this->Users->save($user)) {
                $response['success'] = true;
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function isAuthorized($user)
    {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && $user['role_id'] === 1) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'admin'));
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
        $this->viewBuilder()->setLayout('backend_new-login');
        $this->loadComponent('Email');
        if ($this->request->is('post')) {
            $data = $this->getRequest()->getData();
            $query = $this->Users->find()->where(['email' => $data['email'], 'role_id' => 1]);
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

    public function changeActiveStatus($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'indexPartner']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
    }

    public function showModalUser()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false , 'data' => '' , 'message' => '321123'];
        $dataRQ = $this->request->getQuery();
        $id = $dataRQ['id'];
        $user = $this->Users->find()->where(['id' => $id])->first();
        $roles = $this->Users->Roles->find('list');
        $managers = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'screen_name'
        ])->where(['role_id' => 2]);
        $landtour_managers = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'screen_name'
        ])->where(['role_id' => 5]);
        $this->set(compact('user','roles','managers','landtour_managers'));

        $response['modal_user'] =  $this->render('modal_user')->body();
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }
    public function editUser2($id){
        $data = $this->getRequest()->getData();
        $user = $this->Users->get($id);
        if ($data['profile_avatar']['error'] == 0) {
            $avatar = $this->Upload->uploadSingle($data['profile_avatar']);
            $data['avatar'] = $avatar;
        } else {
            unset($data['profile_avatar']);
        }
        if (!isset($data['is_active'])) {
            $data['is_active'] = 0;
        }else{
            $data['is_active'] = 1;
        }
//        dd($data);
        if ($data['parent_id'] != $user->parent_id) {
            $listUserHotelBookings = $this->Bookings->find()->where(['user_id' => $user->id, 'type !=' => LANDTOUR, 'status IN' => [-1, 0, 1, 2]]);
            foreach ($listUserHotelBookings as $booking) {
                $booking = $this->Bookings->patchEntity($booking, ['sale_id' => $data['parent_id']]);
                $this->Bookings->save($booking);
            }
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
        }
        if ($data['landtour_parent_id'] != $user->landtour_parent_id) {
            $listUserLandtourBookings = $this->Bookings->find()->where(['user_id' => $user->id, 'type' => LANDTOUR, 'status IN' => [-1, 0, 1, 2]]);
            foreach ($listUserLandtourBookings as $booking) {
                $booking = $this->Bookings->patchEntity($booking, ['sale_id' => $data['landtour_parent_id']]);
                $this->Bookings->save($booking);
            }
        }
        $user = $this->Users->patchEntity($user, $data);
        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The user could not be saved. Please, try again.'));
    }
    public function transactionHistory($id){
        $this->viewBuilder()->setLayout('backend_new');
        $this->set(compact('id'));
    }

    public function transactionHistoryDatatable($id)
    {
        $this->loadModel('DepositLogs');
        // xử lý dữ liệu gửi lên để phân trang
        $datarequest = $this->request->getData();
        if (!isset($datarequest) || !$datarequest) {
            $datarequest = [
                "pagination" => [
                    "page" => "1",
                    "perpage" => "15",
                ],
            ];
        }
        if (isset($datarequest['query']['generalSearch'])) {
            $search = $datarequest['query']['generalSearch'];
        } else {
            $search = '';
        }
        $posStart = ($datarequest['pagination']['page'] - 1) * $datarequest['pagination']['perpage'];
        //end xử lý dữ liệu gửi lên để phân trang
        $list_deposits = $this->DepositLogs->find()->where(['type' => 1, 'user_id' => $id])->orderDesc('created');
        //cắt dữ liệu
        $dataRespone = $list_deposits->take($datarequest['pagination']['perpage'], $posStart)->toList();
        foreach ($dataRespone as $key => $value){
            $image = json_decode($value->images);
            $dataRespone[$key]['image'] =  $image[0];
        }
        $response = [
            'meta' => [
                "page" => $datarequest['pagination']['page'],
                "pages" => number_format(count($list_deposits->toList()) / $datarequest['pagination']['perpage']),
                "perpage" => $datarequest['pagination']['perpage'],
                "total" => count($list_deposits->toList()),
            ],
            'data' => $dataRespone
        ];
//        dd($response);
        //end cắt dữ liệu
        $this->set([
            'my_response' => $response,
            '_serialize' => 'my_response',
        ]);
        $this->RequestHandler->renderAs($this, 'json');
    }
}
