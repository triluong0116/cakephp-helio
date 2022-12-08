<?php

namespace App\Controller\Api\v400;
use App\Controller\Api\AppController;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Cake\Validation\Validation;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\BookingsTable $Bookings
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
        $this->Auth->allow(['login', 'forgotPassword', 'changePassword', 'updateInfo', 'listBooking', 'upload', 'logout', 'findByPhone', 'getListImage', 'updateInfoClient']);
    }

    public function findByPhone()
    {
        $this->loadModel('Clients');
        $phoneNumnber = $this->getRequest()->getQuery('phoneNumber');
        $phoneNumnber = preg_replace('/\s+/', '', $phoneNumnber);
        if ($phoneNumnber) {
            $agency = $this->Users->find()->where(['phone' => $phoneNumnber, 'role_id' => 3])->first();
            if ($agency) {
                $clientIds = $this->Clients->find()->where(['user_id' => $agency->id]);
                $listClientId = [];
                foreach ($clientIds as $id) {
                    $listClientId[] = $id->clientId;
                }

                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => 'Thành công',
                    'data' => [
                        'user_id' => $agency->id,
                        'screen_name' => $agency->screen_name,
                        'avatar' => $agency->avatar,
                        'phone' => $agency->phone,
                        'fbid' => $agency->fbid,
                        'zalo' => $agency->zalo,
                        'agencyFingerprint' => $listClientId
                    ],
                    '_serialize' => ['status', 'data', 'message']
                ]);
            } else {
                $this->set([
                    'status' => STT_NOT_FOUND,
                    'message' => 'Not found',
                    'data' => [],
                    '_serialize' => ['status', 'data', 'message']
                ]);
            }

        } else {
            $this->set([
                'status' => STT_NOT_FOUND,
                'message' => 'Not found',
                'data' => [],
                '_serialize' => ['status', 'data', 'message']
            ]);
        }
    }

    public function login()
    {
        $this->loadModel('Clients');
        if (Validation::email($this->getRequest()->getData('username'))) {
            $this->Auth->setConfig('authenticate', [
                'Form' => [
                    'fields' => ['username' => 'email']
                ]
            ]);
            $this->Auth->constructAuthenticate();
//            $this->request->data['email'] = $this->request->data['username'];
            $this->request->data['email'] = $this->request->data['username'];
            unset($this->request->data['username']);
        }
        $user = $this->Auth->identify();
        if ($user) {
            $clientId = $this->getRequest()->getData('clientId');
            $client = $this->Clients->find()->where(['clientId' => $clientId])->first();
            if (!$client) {
                $client = $this->Clients->newEntity();
            }
            $token = $this->Api->genUserToken($user, $clientId);

            $save_data_login = [
                'clientId' => $clientId,
                'user_id' => $user['id'],
//                'api_token_login' => $token,
//                'login_expire' => $login_expire
            ];

            $client = $this->Clients->patchEntity($client, $save_data_login);
            $this->Clients->save($client);

            $this->set([
                'status' => STT_SUCCESS,
                'data' => [
                    'token' => $token,
                    'user' => $user
                ],
                '_serialize' => ['status', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_ERROR,
                'message' => 'Username/Password is invalid',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function logout()
    {
        $this->loadModel('Clients');
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $status = STT_ERROR;
            $message = '';
            $data = $this->getRequest()->getData();
            $client = $this->Clients->find()->where(['user_id' => $check['user_id'], 'clientId' => $data['clientId']])->first();
            if ($client) {
                $client = $this->Clients->patchEntity($client, ['api_token_login' => '', 'login_expire' => '']);
                $this->Clients->save($client);

                $status = STT_SUCCESS;
                $message = 'Thành công';
            } else {
                $status = STT_INVALID;
                $message = 'Dữ liệu không hợp lệ';
            }

            $this->set([
                'status' => $status,
                'message' => $message,
                '_serialize' => ['status', 'message']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => $check['message'],
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function forgotPassword()
    {
        $this->loadComponent('Email');
        $res = ['status' => STT_ERROR, 'message' => ''];
        if ($this->request->is('post')) {

            $data = $this->getRequest()->getData();
            $query = $this->Users->find()->where(['email' => $data['email']]);
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
                        $res['status'] = STT_SUCCESS;
                        $res['message'] = 'Mật khẩu đã được gửi đến email của bạn. Vui lòng check email để lấy password mới.';
                    } else {
                        $res['message'] = 'Không gửi đc email. Vui lòng thử lại';
                    }
                } else {
                    $res['message'] = 'Có lỗi xảy ra vui lòng thử lại';
                }
            } else {
                $res['message'] = 'Không tìm thấy User với Email tương ứng';
            }
        } else {
            $res['message'] = 'Phương thức không hợp lệ';
        }

        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            '_serialize' => ['status', 'message']
        ]);
    }

    public function changePassword()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $status = STT_ERROR;
            $message = '';
            $data = $this->getRequest()->getData();
            $user = $this->Users->get($check['user_id']);
            if (isset($data['password']) && !empty($data['password'])) {
                if (isset($data['re_password']) && !empty($data['re_password'])) {
                    if ($data['password'] == $data['re_password']) {
                        $user = $this->Users->patchEntity($user, $data);
                        if ($this->Users->save($user)) {
                            $status = STT_SUCCESS;
                            $message = 'Đổi mật khẩu thành công';
                        } else {
                            $message = 'Có lỗi xảy ra';
                        }
                    } else {
                        $message = 'Xin mời nhập lại mật khẩu chính xác.';
                    }
                } else {
                    $message = 'Bạn chưa nhập lại mật khẩu.';
                }
            } else {
                $message = 'Bạn chưa nhập mật khẩu.';
            }

            $this->set([
                'status' => $status,
                'message' => $message,
                '_serialize' => ['status', 'message']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => $check['message'],
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function updateInfo()
    {
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            if ($this->getRequest()->is('post')) {
                $data = $this->getRequest()->getData();
                $user = $this->Users->get($check['user_id']);
                if ($user) {
                    $user = $this->Users->patchEntity($user, $data);
                    if ($this->Users->save($user)) {
                        $res['status'] = STT_SUCCESS;
                        $res['message'] = "Chỉnh sửa thông tin thành công";
                    } else {
                        $res['message'] = "Có lỗi xảy ra.";
                    }
                } else {
                    $res['message'] = 'Không tìm thấy User';
                }
            } else {
                $res['message'] = 'Phương thức không hợp lệ';
            }
        } else {
            $res['status'] = STT_NOT_LOGIN;
            $res['message'] = $check['message'];
        }

        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            '_serialize' => ['status', 'message']
        ]);
    }

    public function updateInfoClient()
    {
        $this->loadModel('Clients');
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();
            $client = $this->Clients->find()->where(['clientId' => $data['clientId']])->first();
            if ($client) {
                $client = $this->Clients->patchEntity($client, $data);
                if ($this->Clients->save($client)) {
                    $res['status'] = STT_SUCCESS;
                    $res['message'] = "Chỉnh sửa thông tin thành công";
                } else {
                    $res['message'] = "Có lỗi xảy ra.";
                }
            } else {
                $res['message'] = 'Không tìm thấy thông tin Khách hàng';
            }
        } else {
            $res['message'] = 'Phương thức không hợp lệ';
        }
        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            '_serialize' => ['status', 'message']
        ]);
    }

    public function listBooking($isBookingLandtour = false)
    {
        $this->loadModel('UserTransactions');
        $this->loadModel('Bookings');

        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $data = $this->getRequest()->getQuery();
        $condition = [];
        if (isset($data['fromDate']) && !empty($data['fromDate'])) {
            $condition['created >='] = date('Y-m-d', strtotime($data['fromDate']));
        }
        if (isset($data['toDate']) && !empty($data['toDate'])) {
            $condition['created <='] = date('Y-m-d', strtotime($data['toDate']));
        }

        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
            $condition['Bookings.user_id'] = $user_id;
            $role_id = $check['role_id'];
        } else {
            $condition['Bookings.client_id'] = $data['client_id'];
        }
        if(!$isBookingLandtour){
            $condition['type IN'] = [VOUCHER, HOTEL, HOMESTAY];
        } else {
            $condition['type'] = LANDTOUR;
        }

        $data = $this->Bookings->find()->contain([
            'Hotels',
            'Vouchers',
            'LandTours',
            'HomeStays',
            'Combos',
            'BookingSurcharges',
            'BookingLandtours'
        ])->where($condition)->orderDesc('Bookings.created')->toArray();
        foreach ($data as $key => $booking) {
            foreach ($booking['booking_surcharges'] as $surcharge) {
                $data[$key]['price'] += $surcharge['price'];
            }
            $booking->status_str = "";
            if (isset($role_id)) {
                switch ($booking->status) {
                    case -1:
                        $booking->status_str = "Khách đã gửi đơn đặt phòng";
                        break;
                    case 0:
                        $booking->status_str = "Đại lý mới đặt";
                        break;
                    case 1:
                        $booking->status_str = "Chờ KS xác nhận rồi gửi mail đề nghị thanh toán";
                        break;
                    case 2:
                        $booking->status_str = $booking->agency_pay == 1 ? "ĐL đã TT, chờ KT TT" : "Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán";
//                            $booking->status_str = "Đang chờ CTV thanh toán ";
                        break;
                    case 3:
                        $booking->status_str = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? 'Hoàn thành' : 'Hoàn thành';
                        break;
                    case 4:
                        $booking->status_str = "Hoàn thành";
                        break;
                    case 5:
                        $booking->status_str = "Đơn hàng đã bị hủy";
                        break;
                }
            } else {
                switch ($booking->status) {
                    case -1:
                        $booking->status_str = "Đã gửi đơn đặt phòng";
                        break;
                    case 0:
                        $booking->status_str = "Chờ kiểm tra tình trạng phòng";
                        break;
                    case 1:
                        $booking->status_str = "Chờ kiểm tra tình trạng phòng";
                        break;
                    case 2:
                        $booking->status_str = "Còn phòng , đề nghị thanh toán đơn hàng ";
                        break;
                    case 3:
                        $booking->status_str = "Đã thanh toán, chờ mã xác nhận";
                        break;
                    case 4:
                        $booking->status_str = "Hoàn thành";
                        break;
                    case 5:
                        $booking->status_str = "Đơn hàng đã bị hủy";
                        break;
                }
            }
            if($isBookingLandtour == true){
                if ($booking->payment_method == AGENCY_PAY){
                    $data[$key]['mustgo_deposit'] = 0;
                    $data[$key]['different'] = 0;
                }
                if($booking->payment_method == MUSTGO_DEPOSIT){
                    $data[$key]['mustgo_deposit'] = $booking->mustgo_deposit;
                    $data[$key]['different'] = $booking->mustgo_deposit - $booking->price;
                }
            }
        }
        $res['status'] = STT_SUCCESS;
        $res['data'] = $data;

        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => $res['data'],
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function upload()
    {
        $this->loadComponent('Upload');
        $res = ['status' => STT_ERROR, 'data' => [], 'message' => ''];
        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();
            if ($data['image']['error'] == 0) {
                $img = $this->Upload->uploadSingle($data['image']);
                $res['status'] = STT_SUCCESS;
                $res['data']['link'] = $img;
            } else {
                $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại';
            }
        } else {
            $res['status'] = STT_NOT_ALLOW;
            $res['message'] = 'Method không hợp lệ';
        }


        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => $res['data'],
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function getListImage()
    {
        $this->loadModel('Chats');
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];

        $user_id = $this->getRequest()->getQuery('user_id');
        $clientId = $this->getRequest()->getQuery('clientId');
        $chatImgs = $this->Chats->find()->where(['user_id' => $user_id, 'clientId' => $clientId, 'img !=' => ''])->extract('img');
        $res['status'] = true;
        $res['data'] = $chatImgs;

        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => $res['data'],
            '_serialize' => ['status', 'message', 'data']
        ]);
    }
}
