<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Validation\Validation;

require_once(ROOT . DS . "vendor" . DS . "zaloplatform" . DS . "zalo-php-sdk" . DS . "src" . DS . "Zalo.php");
require_once(ROOT . DS . "vendor" . DS . "zaloplatform" . DS . "zalo-php-sdk" . DS . "src" . DS . "ZaloConfig.php");

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\SessionsTable $Sessions
 * @property \App\Model\Table\UserTransactionsTable $UserTransactions
 * @property \App\Model\Table\VinhmsbookingsTable $Vinhmsbookings
 * @property \App\Model\Table\BookingsTable $Bookings
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
//        $this->loadComponent('Upload');
        $this->Auth->allow(['loginViaFb']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Roles']
        ];
        $users = $this->paginate($this->Users);

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

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($this->Auth->user()) {
            $headerType = 1;

            $user_id = $this->Auth->user('id');
            $user = $this->Users->get($user_id);
            $data = $this->request->getData();
            $title = "TH??NG TIN C?? NH??N";
            $this->set(compact('headerType', 'title', 'user'));
        } else {
            $this->redirect(['controller' => 'Blogs', 'action' => 'agencyP1']);
        }
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

    public function loginViaFb()
    {
        $res = ['success' => false, 'message' => '', 'avatar' => '', 'name' => ''];
        $this->loadModel('Fanpages');
        $fb = $this->viewVars['fbGlobal'];
        $helper = $fb->getJavaScriptHelper();
        $data = $this->request->getData();
//        $_SESSION['FBRLH_state'] = $_GET['state'];
//        if (isset($_GET['state'])) {
//            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
//        }

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
            echo 'No Auth data could be obtained from the signed request. User has not authorized your app yet.';
            exit;
        }

        try {
            $response = $fb->get('me?fields=id,email,name,picture.width(800).height(800)', $accessToken->getValue());
            $response = $response->getDecodedBody();
            $user = $this->Users->find()->where(['username' => 'fb' . $response['id']])->first();
            $dataUser = [
                'username' => 'fb' . $response['id'],
                'screen_name' => $response['name'],
                'email' => $response['email'],
                'avatar' => $response['picture']['data']['url'],
                'access_token' => $accessToken->getValue(),
                'fbid' => 'https://facebook.com/profile.php?' . $response['id'],
                'role_id' => 3
            ];
            if (!$user) {
                $user = $this->Users->newEntity();
                $dataUser['ref_code'] = $this->Util->generateRandomString(24);
                $dataUser['is_active'] = 0;
            }

            $refCodeAgency = $this->request->getSession()->read('refAgencyCode');
            if ($refCodeAgency) {
                $parentUser = $this->Users->find()->where(['ref_code' => $refCodeAgency])->first();
                if ($parentUser->role_id == 2) {
                    $dataUser['parent_id'] = $parentUser->id;
                } else if ($parentUser->role_id == 3) {
                    $dataUser['parent_id'] = $parentUser->parent_id;
                }
            }
            $user = $this->Users->patchEntity($user, $dataUser);
            if ($this->Users->save($user)) {
                $this->Auth->setUser($user);
                $this->request->getSession()->write('isShowPopupPromote', false);
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
                $res['success'] = true;
                $res['avatar'] = $response['picture']['data']['url'];
                $res['name'] = $response['name'];
                $this->Auth->setUser($user);
            } else {
                $res['message'] = 'C?? l???i x???y ra';
            }
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($res));
        return $output;
    }

    private function _checkLoginViaFbId($fbInfo)
    {
        // check user is exists
        $userInfo = $this->Users->find()->where(['fbid' => $fbInfo['id']])->first();
        if (!empty($userInfo)) {
            $data['screen_name'] = $fbInfo['name'];
            if (filter_var($userInfo->avatar, FILTER_VALIDATE_URL) || empty($userInfo->avatar)) {
                $data['avatar'] = $fbInfo['picture']['data']['url'];
            }

            $this->Users->patchEntity($userInfo, $data);
            $this->Users->save($userInfo);
        } else {
            $newUser = $this->Users->newEntity();
            $this->Users->patchEntity($newUser, [
                'role_id' => 3,
                'username' => 'fb' . $fbInfo['id'],
                'screen_name' => $fbInfo['name'],
                'email' => '',
                'is_active' => 1,
                'avatar' => $fbInfo['picture']['data']['url']
            ]);
            $this->Users->save($newUser);

            $userInfo = $this->Users->find()->where(['username' => 'fb' . $fbInfo['id']])->first();
        }

        $this->Auth->setUser($userInfo);
        $this->RememberMe->rememberData($userInfo['username']);
    }

    private function _checkLoginViaFbEmail($fbInfo)
    {
        // check user is exists
        $userInfo = $this->Users->find()->where(['email' => $fbInfo['email']])->first();
        if (!empty($userInfo)) {
            $data['screen_name'] = $fbInfo['name'];
            if (filter_var($userInfo->avatar, FILTER_VALIDATE_URL) || empty($userInfo->avatar)) {
                $data['avatar'] = $fbInfo['picture']['data']['url'];
            }
            $this->Users->patchEntity($userInfo, $data);
            $this->Users->save($userInfo);
        } else {
            $newUser = $this->Users->newEntity();
            $this->Users->patchEntity($newUser, [
                'role_id' => 3,
                'username' => 'fb' . $fbInfo['id'],
                'screen_name' => $fbInfo['name'],
                'email' => $fbInfo['email'],
                'is_active' => 1,
                'avatar' => $fbInfo['picture']['data']['url']
            ]);
            $this->Users->save($newUser);

            $userInfo = $this->Users->find()->where(['email' => $fbInfo['email']])->first();
        }

        $this->Auth->setUser($userInfo);
        $this->RememberMe->rememberData($userInfo['username']);
    }

    public function checkZalo()
    {
        $res = ['success' => false, 'message' => '', 'zalo' => '', 'avatar' => ''];
        if ($this->Auth->user('zalo')) {
            $res['success'] = true;
        } else {
            $res['message'] = 'C?? l???i x???y ra';
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($res));
        return $output;
    }

    public function updateZalo()
    {
        $this->autoRender = false;
        $response = ['success' => false, 'message' => ''];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $user = $this->Users->get($this->Auth->user('id'));
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $response['success'] = true;
            } else {
                $response['message'] = 'C?? l???i x???y ra';
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function checkSession()
    {
        $this->autoRender = false;
        $response = ['success' => false, 'message' => '', 'user_id' => ''];
        if ($this->request->is('ajax')) {
            if ($this->Auth->user('is_active') == 1) {
                $response['success'] = true;
                $response['user_id'] = $this->Auth->user('id');
            } else if ($this->getRequest()->getSession()->read('refAgencyCode')) {
                $refCode = $this->getRequest()->getSession()->read('refAgencyCode');
                $response['success'] = true;
                $response['user_id'] = $this->Users->find()->where(['ref_code' => $refCode])->extract('id')->first();
            } else {
                $response['message'] = 'C?? l???i x???y ra';
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function loginViaTrippal()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $validate = $this->Users->newEntity($data, ['validate' => 'login']);
            if ($validate->getErrors()) {
                $response['errors'] = $validate->getErrors();
                $response;
            } else {
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
                    $response['success'] = true;
                    $this->Auth->setUser($user);
                    $this->request->getSession()->write('isShowPopupPromote', false);
                } else {
                    $response['errors'] = ['password' => ['T??n truy c???p ho???c m???t kh???u kh??ng ch??nh x??c.']];
                }
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function forgetPassword()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadComponent('Email');
        $res = ['success' => false, 'errors' => []];
        if ($this->request->is('post')) {

            $data = $this->getRequest()->getData();
            $query = $this->Users->find()->where(['email' => $data['email'], 'role_id' => 3]);
            $user = $query->first();
            if ($user) {
                $pass = uniqid();
                $user = $this->Users->patchEntity($user, ['password' => $pass]);
                if ($this->Users->save($user)) {
                    $bodyEmail = "Ch??o b???n!";
                    $bodyEmail .= "<br />B???n ???? kh??i ph???c th??nh c??ng m???t kh???u.";
                    $bodyEmail .= "<br />M???t kh???u m???i c???a b???n l??: <strong>" . $pass . "</strong>";
                    $bodyEmail .= "<br />Vui l??ng ????ng nh???p v?? ti???n h??nh ?????i m???t kh???u.";
                    $bodyEmail .= "<br />The Mustgo Team!";
                    $data_sendEmail = [
                        'to' => $data['email'],
                        'subject' => 'Kh??i ph???c m???t kh???u th??nh c??ng',
                        'title' => 'Kh??i ph???c m???t kh???u th??nh c??ng',
                        'body' => $bodyEmail
                    ];
                    if ($this->Email->sendEmailForgotPassword($data_sendEmail)) {
                        $res['success'] = true;
                    } else {
                        $res['errors'] = ['email' => ['Kh??ng g???i ??c email. Vui l??ng th??? l???i']];
                    }
                } else {
                    $res['errors'] = ['email' => ['C?? l???i x???y ra vui l??ng th??? l???i']];
                }
            } else {
                $res['errors'] = ['email' => ['Kh??ng t??m th???y User v???i Email t????ng ???ng']];
            }
        } else {
            $res['errors'] = ['email' => ['Ph????ng th???c kh??ng h???p l???']];
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($res));
        return $output;
    }

    public function logout()
    {
        $this->Auth->logout();
        return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
    }


    public function dataBoard()
    {
        if ($this->Auth->user()) {
            $this->loadModel('UserTransactions');
            $this->loadModel('Bookings');
            $this->loadModel('Vinhmsbookings');

            $headerType = 1;
            $user_id = $this->Auth->user('id');
            $role = $this->Auth->user('role_id');
            $title = "Th??ng tin Booking";
            if ($role == 3) {
                $datas = $this->Bookings->find()->contain(['Hotels', 'Vouchers', 'LandTours', 'HomeStays', 'BookingSurcharges', 'BookingRooms', 'BookingLandtours', 'BookingLandtourAccessories', 'Payments'])
                    ->where(['Bookings.user_id' => $user_id])
                    ->order(['Bookings.created' => 'DESC'])
                    ->toArray();
                $listVinBookings = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['user_id' => $user_id])->order(['Vinhmsbookings.created' => 'DESC']);
                $headerType = 1;
                $date = $this->request->getQuery();
                if ($date) {
                    $datas = $this->Bookings->find()->contain(['Hotels', 'Vouchers', 'LandTours', 'HomeStays', 'BookingSurcharges', 'BookingLandtours', 'BookingLandtourAccessories', 'Payments'])
                        ->where(['Bookings.user_id' => $user_id,
                            'DATE(Bookings.created) >=' => date('Y-m-d', strtotime($date['fromDate'])),
                            'DATE(Bookings.created) <= ' => date('Y-m-d', strtotime($date['toDate']))
                        ])->toArray();
                    $listVinBookings = $this->Vinhmsbookings->find()->contain(['VinhmsbookingRooms', 'Hotels', 'Vinpayments'])->where(['user_id' => $user_id,
                        'created >=' => date('Y-m-d', strtotime($date['fromDate'])),
                        'created <=' => date('Y-m-d', strtotime($date['toDate'])),
                    ])->order(['Vinhmsbookings.created' => 'DESC']);
                }
                $totalRevenue = 0;
                $totalLandtourRevenue = 0;
                foreach ($datas as $key => $data) {
                    if ($data->booking_type == 1) {
                        if($data->status != 5) {
                            if($data->type == LANDTOUR){
                                $totalLandtourRevenue += $data->revenue;
                            } else {
                                $totalRevenue += $data->revenue;
                            }
                        }
                    }
                    $totalSurchargePrice = 0;
                    foreach ($data->booking_surcharges as $surcharge) {
                        $totalSurchargePrice += $surcharge->price;
                    }
                    $datas[$key]['total_price'] = $data->price +
                        ($data->adult_fee ? $data->adult_fee : 0)
                        + ($data->children_fee ? $data->children_fee : 0)
                        + ($data->holiday_fee ? $data->holiday_fee : 0)
                        + ($data->other_fee ? $data->other_fee : 0)
                        + ($data->car ? $data->car : 0)
                        + $totalSurchargePrice;
                }
                $this->set(compact('datas', 'title', 'headerType', 'totalRevenue', 'totalLandtourRevenue', 'listVinBookings'));

            } else {
                $this->redirect(['controller' => 'Pages', 'action' => 'home']);
            }
        } else {
            $this->redirect(['controller' => 'Blogs', 'action' => 'agencyP1']);
        }

    }

    /**
     *
     */
    public function tradehistory()
    {
        if ($this->Auth->user()) {
            $this->loadModel('Withdraws');
            $user = $this->Users->get($this->Auth->user('id'));
            $withdraws = $this->Withdraws->find()->contain(['Users'])->where(['Withdraws.user_id' => $user->id]);
//            dd($withdraws->toArray());
            $headerType = 1;
            $title = "L???CH S??? GIAO D???CH";
            $this->set(compact('headerType', 'title', 'user', 'withdraws'));
        } else {
            $this->redirect(['controller' => 'Blogs', 'action' => 'agencyP1']);
        }
    }

    public function editInfor()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $user_id = $this->Auth->user('id');
        $user = $this->Users->get($user_id);
        $response = ['success' => false, 'errors' => []];
        $success = false;
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            if ((isset($data['screen_name']) && !empty($data['screen_name'])) || (isset($data['email']) && !empty($data['email'])) || (isset($data['phone']) && !empty($data['phone'])) || (isset($data['bank_code']) && !empty($data['bank_code'])) || (isset($data['bank']) && !empty($data['bank'])) || (isset($data['bank_master']) && !empty($data['bank_master'])) || (isset($data['bank_name']) && !empty($data['bank_name']))) {
                if ($data['avatar']['error'] == 0) {
                    $avatar = $this->Upload->uploadSingle($data['avatar']);
                    $data['avatar'] = $avatar;
                } else {
                    unset($data['avatar']);
                }
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $response['success'] = true;
                } else {
                    $response['errors'] = "C?? l???i x???y ra.";
                }
            } else {
                $response['errors'] = ['info' => ['B???n ch??a ??i???n th??ng tin n??o.']];
            }
            if ($this->Auth->user('id') === $user->id) {
                $data = $user->toArray();
                unset($data['password']);

                $this->Auth->setUser($data);
            }
            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function editPassword()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $user_id = $this->Auth->user('id');
        $user = $this->Users->get($user_id);
        $response = ['success' => false, 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            if (isset($data['password']) && !empty($data['password'])) {
                if (isset($data['re_password']) && !empty($data['re_password'])) {
                    if ($data['password'] == $data['re_password']) {
                        $user = $this->Users->patchEntity($user, $data);
                        if ($this->Users->save($user)) {
                            $response['success'] = true;
                        } else {
                            $response['message'] = 'C?? l???i x???y ra';
                        }
                    } else {
                        $response['errors'] = ['re_password' => ['Xin m???i nh???p l???i m???t kh???u ch??nh x??c.']];
                    }
                } else {
                    $response['errors'] = ['re_password' => ['B???n ch??a nh???p l???i m???t kh???u.']];
                }
            } else {
                $response['errors'] = ['password' => ['B???n ch??a nh???p m???t kh???u.']];
            }

            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function connectFacebook()
    {
        $res = ['success' => false, 'message' => '', 'avatar' => '', 'name' => ''];
        $this->loadModel('Fanpages');
        $fb = $this->viewVars['fbGlobal'];
        $helper = $fb->getJavaScriptHelper();
        $data = $this->request->getData();
        $user_id = $this->Auth->user('id');

//        $_SESSION['FBRLH_state'] = $_GET['state'];
//        if (isset($_GET['state'])) {
//            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
//        }

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


            $responsePage = $fb->get('me/accounts', $accessToken->getValue());
            $responsePage = $responsePage->getDecodedBody();
            foreach ($responsePage['data'] as $item) {
                $fanpage = $this->Fanpages->find()->where(['page_id' => $item['id']])->first();
                if (!$fanpage) {
                    $fanpage = $this->Fanpages->newEntity();
                }
                $data_fanpage = [
                    'user_id' => $this->Auth->user('id'),
                    'name' => $item['name'],
                    'page_id' => $item['id'],
                    'access_token' => $item['access_token']
                ];
                $fanpage = $this->Fanpages->patchEntity($fanpage, $data_fanpage);
                $this->Fanpages->save($fanpage);
            }
            $res['success'] = true;
            $res['avatar'] = $response['picture']['data']['url'];
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($res));
        return $output;
    }


    public function editZalo()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $user_id = $this->Auth->user('id');
        $user = $this->Users->get($user_id);
        $response = ['success' => false, 'errors' => []];
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();

            if (isset($data['zalo']) && !empty($data['zalo'])) {
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $response['success'] = true;
                } else {
                    $response['errors'] = "C?? l???i x???y ra.";
                }
            } else {
                $response['errors'] = ['info' => ['B???n ch??a th??ng tin n??o.']];
            }

            $output = $this->response;
            $output = $output->withType('json');
            $output = $output->withStringBody(json_encode($response));
            return $output;
        }
    }

    public function shareZaloSuccess()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->loadModel('UserShares');

        $response = ['success' => true];
        if ($this->request->is('ajax')) {
            $data = $this->getRequest()->getData();
            $userShare = $this->UserShares->newEntity();
            $data_share = [
                'user_id' => $this->Auth->user('id'),
                'type' => ZALO_POST_TYPE,
                'object_type' => $data['object_type'],
                'object_id' => $data['object_id']
            ];
            $userShare = $this->UserShares->patchEntity($userShare, $data_share);
            $this->UserShares->save($userShare);
        }

        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function checkPopupPromoteStatus()
    {
        $this->loadModel('Promotes');
        $today = date('Y-m-d');
        $promotes = $this->Promotes->find()->where(['end_date >=' => $today]);
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false];
        if ($this->request->is('ajax')) {
            if ($promotes) {
                $check = $this->request->getSession()->read('isShowPopupPromote');
                if ($check == false) {
                    $response['success'] = true;
                    $this->request->getSession()->write('isShowPopupPromote', true);
                }
            }
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

}
