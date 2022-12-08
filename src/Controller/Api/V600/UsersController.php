<?php

namespace App\Controller\Api\V600;

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Cake\Validation\Validation;
use Firebase\JWT\JWT;
use Google\Cloud\Firestore\FirestoreClient;
use Cake\Auth\DefaultPasswordHasher;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\BookingsTable $Bookings
 * @property \App\Model\Table\BookingRoomsTable $BookingRooms
 * @property \App\Model\Table\ConfigsTable $Configs
 * @property \App\Model\Table\DepositLogsTable $DepositLogs
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\ChatsTable $Chats
 * @property \App\Model\Table\HotelsTable $Hotels
 * @property \App\Model\Table\VinhmsbookingsTable $Vinhmsbookings
 * @property \App\Model\Table\VinhmsbookingRoomsTable $VinhmsbookingRooms
 * @property \App\Model\Table\BookingSurchargesTable $BookingSurcharges
 * @property \App\Controller\Component\UtilComponent $Util
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login', 'saleCreateMerchant', 'forgotPassword', 'changePassword', 'updateInfo', 'listBooking', 'upload', 'logout', 'findByPhone', 'getListImage', 'updateInfoClient', 'getBalance', 'addBalanceInformation', 'addBalance', 'logTransaction', 'detailTransaction', 'listBookingVinpearl', 'listAgencyChat', 'createAccount', 'changeAvatar', 'listAgency', 'userInfo', 'editUserInfo', 'addClientExpoToken', 'clearSession', 'test']);
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

            if ($user['role_id'] == 3) {
                $user['chat_id'] = $user['id'] . '-' . $user['parent_id'];
            }

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

    public function listAgency()
    {
        $check = $this->Api->checkLoginApi();
        $status = STT_ERROR;
        $message = '';
        $data = [];
        if ($check['status']) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            if ($user) {
                if ($user->role_id == 2) {
                    $listAgency = $this->Users->find('list', ['keyField' => 'id', 'valueField' => 'screen_name'])->where(['parent_id' => $user->id])->toArray();
                    foreach ($listAgency as $k => $agency) {
                        $data[] = [
                            'id' => $k,
                            'screen_name' => $agency
                        ];
                    }
                    $status = STT_SUCCESS;
                    $message = 'Success';
                } else {
                    $status = STT_NOT_ALLOW;
                    $message = "You don't have permission";
                }
            } else {
                $status = STT_NOT_FOUND;
                $message = 'User Not Found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not Logged In';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
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

    public function listBooking($isBooking, $isBookingLandtour = false)
    {
        $this->loadModel('UserTransactions');
        $this->loadModel('Bookings');
        $this->loadModel('BookingSurcharges');
        $this->loadModel('BookingRooms');
        $this->loadModel('Payments');

        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $data = $this->getRequest()->getQuery();
        $condition = [];
        if (isset($data['fromDate']) && !empty($data['fromDate'])) {
            $condition['DATE(Bookings.start_date) >='] = date('Y-m-d', strtotime($data['fromDate']));
        }
        if (isset($data['toDate']) && !empty($data['toDate'])) {
            $condition['DATE(Bookings.end_date) <='] = date('Y-m-d', strtotime($data['toDate']));
        }
        if (isset($data['agency_id']) && !empty($data['agency_id'])) {
            $id = intval($data['agency_id']);
            $condition['Bookings.user_id'] = $id;
        }
        if (isset($data['code']) && !empty($data['code'])) {
            $condition['Bookings.code'] = $data['code'];
        }
//        if (isset($data['page']) && !empty($data['page'])) {
//            $page = $data['page'];
//        }
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
            $role_id = $check['role_id'];

            $contain = [];
            if (isset($isBooking)) {
                if ($isBooking == "true") {
                    $condition['Bookings.type'] = LANDTOUR;
                    $contain = ['LandTours', 'BookingLandtours', 'Users', 'Payments'];
                }
                if ($isBooking == "false") {
                    $condition['Bookings.type IN'] = [VOUCHER, HOTEL, HOMESTAY];
                    $contain = ['Hotels', 'BookingSurcharges', 'Users', 'BookingRooms', 'Payments'];
                }
            } else {
                if (!$isBookingLandtour) {
                    $condition['Bookings.type IN'] = [VOUCHER, HOTEL, HOMESTAY];
                    $contain = ['Hotels', 'BookingSurcharges', 'Users', 'Payments'];
                } else {
                    $condition['Bookings.type'] = LANDTOUR;
                    $contain = ['LandTours', 'BookingLandtours', 'Users', 'BookingRooms', 'Payments'];
                }
            }
            if ($role_id == 3) {
                $condition['Bookings.user_id'] = $user_id;
                $data = $this->Bookings->find()->contain($contain)->where($condition)->orderDesc('Bookings.created')->limit(50)->toArray();
                $list = [];
                $listFirst = [];
                $listSecond = [];

                foreach ($data as $key => $booking) {
                    $arr = [];
                    if (isset($booking['booking_surcharges'])) {
                        foreach ($booking['booking_surcharges'] as $surcharge) {
                            $data[$key]['price'] += $surcharge['price'];
                        }
                    }
                    //end check
                    $status = $this->Util->getStatusBooking($booking, $role_id);
                    if ($isBookingLandtour == true) {
                        if ($booking->payment_method == AGENCY_PAY) {
                            $data[$key]['mustgo_deposit'] = 0;
                            $data[$key]['different'] = 0;
                        }
                        if ($booking->payment_method == MUSTGO_DEPOSIT) {
                            $data[$key]['mustgo_deposit'] = $booking->mustgo_deposit;
                            $data[$key]['different'] = $booking->mustgo_deposit - $booking->price;
                        }
                    }
                    $numRoom = $booking['booking_rooms'] ? count($booking['booking_rooms']) : 0;
                    $dateStart = date('d/m/Y', strtotime($booking['start_date']));
                    $dateEnd = date('d/m/Y', strtotime($booking['end_date']));
                    $day = date_diff(date_create($booking['start_date']), date_create($booking['end_date']));
                    $created = date('H:m - d/m/Y', strtotime($booking['created']));

                    $arr['id'] = $booking['id'];
                    $arr['code'] = $booking['code'];
                    $arr['name'] = $booking['full_name'];
                    $arr['hotel_name'] = $booking['type'] == 4 ? $booking['hotels']['name'] : $booking['land_tours']['name'];
                    $arr['start_date'] = $dateStart;
                    $arr['end_date'] = $dateEnd;
                    $arr['status_str'] = $status['status_str'];
                    $arr['status_color'] = $status['status_color'];
                    $arr['created'] = $created;
                    $arr['price'] = $booking['price'] - $booking['revenue'];
                    $arr['day'] = $day->days + 1;;
                    $arr['night'] = $day->days;
                    $arr['numRoom'] = $numRoom;
                    if ($status['sort'] == 0) {
                        $listFirst[] = $arr;
                    } else {
                        $listSecond[] = $arr;
                    }
                }
                $list = array_merge($listFirst, $listSecond);
                $res['status'] = STT_SUCCESS;
                $res['data'] = $list;

                $this->set([
                    'status' => $res['status'],
                    'message' => $res['message'],
                    'data' => $res['data'],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
            if ($role_id == 2) {
                if (isset($data['code']) && !empty($data['code'])) {
                    $condition['Bookings.code'] = $data['code'];
                }
                $condition['Bookings.sale_id'] = $user_id;
                $data = $this->Bookings->find()->contain($contain)->where($condition)->orderDesc('Bookings.created')->limit(20)->toArray();
                $list = [];
                $listFirst = [];
                $listSecond = [];
                foreach ($data as $key => $booking) {
                    $arr = [];
                    if ($booking['booking_surcharges']) {
                        foreach ($booking['booking_surcharges'] as $surcharge) {
                            $data[$key]['price'] += $surcharge['price'];
                        }
                    }
                    //check data trên Payment xem đã thanh toán hay chưa?
                    $payment_booking = $this->Payments->find()->where(['booking_id' => $booking['id']])->first();
                    if ($payment_booking) {
                        if ($booking->status < 3) {
                            if ($payment_booking->type == PAYMENT_TRANSFER) {
                                $booking->status = empty($payment_booking->images) ? $booking->status : 99;
                            } else {
                                $booking->status = 99;
                            }
                        }
                    }
                    //end check
                    $status = $this->Util->getStatusBooking($booking, $role_id);
                    if ($isBookingLandtour == true) {
                        if ($booking->payment_method == AGENCY_PAY) {
                            $data[$key]['mustgo_deposit'] = 0;
                            $data[$key]['different'] = 0;
                        }
                        if ($booking->payment_method == MUSTGO_DEPOSIT) {
                            $data[$key]['mustgo_deposit'] = $booking->mustgo_deposit;
                            $data[$key]['different'] = $booking->mustgo_deposit - $booking->price;
                        }
                    }
                    $numRoom = $booking['booking_rooms'] ? count($booking['booking_rooms']) : 0;
                    $dateStart = date('d/m/Y', strtotime($booking['start_date']));
                    $dateEnd = date('d/m/Y', strtotime($booking['end_date']));
                    $day = date_diff(date_create($booking['start_date']), date_create($booking['end_date']));

                    $arr['id'] = $booking['id'];
                    $arr['code'] = $booking['code'];
                    $arr['name'] = $booking['full_name'];
                    $arr['user_id'] = $booking['user_id'];
                    $arr['user_name'] = $booking->user->screen_name;
                    $arr['hotel_name'] = $booking['hotels']['name'];
                    $arr['start_date'] = $dateStart;
                    $arr['end_date'] = $dateEnd;
                    $arr['status_str'] = $status['status_str'];
                    $arr['status_color'] = $status['status_color'];
                    $arr['created'] = $booking['created'];
                    $arr['price'] = $booking['price'] - $booking['revenue'];
                    $arr['price_net'] = $booking['price'] - $booking['revenue'] - $booking['sale_revenue'];
                    $arr['profit'] = $booking['sale_revenue'];
                    $arr['day'] = $day->days + 1;;
                    $arr['night'] = $day->days;
                    $arr['numRoom'] = $numRoom;
                    if ($status['sort'] == 0) {
                        $listFirst[] = $arr;
                    } else {
                        $listSecond[] = $arr;
                    }
                }
                $list = array_merge($listFirst, $listSecond);
                $res['status'] = STT_SUCCESS;
                $res['data'] = $list;

                $this->set([
                    'status' => $res['status'],
                    'message' => $res['message'],
                    'data' => $res['data'],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function upload()
    {
        $this->loadComponent('Upload');
        $res = ['status' => STT_ERROR, 'data' => [], 'message' => ''];
        if ($this->getRequest()->is('post')) {
            $data = $this->request->getData();
            if (isset($data['image'])) {
                if ($data['image']['error'] == 0) {
                    $img = $this->Upload->uploadSingle($data['image']);
                    $res['status'] = STT_SUCCESS;
                    $res['data']['link'] = $img;
                } else {
                    $res['message'] = 'Có lỗi xảy ra. Vui lòng thử lại';
                }
            } else {
                $res['status'] = STT_NOT_VALIDATION;
                $res['message'] = 'Không có file ảnh';
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

    public function getBalance()
    {
        $check = $this->Api->checkLoginApi();
        $data = [];
        if ($check['status']) {
            $user = $this->Users->get($check['user_id']);
            if ($user) {
                if ($user->role_id == 3) {
                    $data['balance'] = $user->balance;
                } else {
                    $data['balance'] = 0;
                }
                $status = STT_SUCCESS;
                $message = 'Success';
            } else {
                $status = STT_NOT_FOUND;
                $message = 'User not found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not login';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function addBalanceInformation()
    {
        $check = $this->Api->checkLoginApi();
        $data = [];
        if ($check['status']) {
            $this->loadModel('Configs');
            $this->loadModel('DepositLogs');
            $transferAgencyInfor = $this->Configs->find()->where(['type' => 'bank-recharge-infor'])->first();
            if ($transferAgencyInfor && $transferAgencyInfor->value) {
                $data['bank_information'] = json_decode($transferAgencyInfor->value, true);
                $messageCode = $this->Util->generateRandomCode();
                $checkCode = $this->DepositLogs->find()->where(['message' => $messageCode])->first();
                while ($checkCode) {
                    $messageCode = $this->Util->generateRandomCode();
                    $checkCode = $this->DepositLogs->find()->where(['message' => $messageCode])->first();
                }
                $data['message_code'] = $messageCode;
                $status = STT_SUCCESS;
                $message = 'Success';
            } else {
                $status = STT_ERROR;
                $message = 'Information is not available';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not login';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function addBalance()
    {
        $check = $this->Api->checkLoginApi();
        $data = [];
        if ($check['status']) {
            $this->loadModel('DepositLogs');
            $dataPost = $this->request->getData();
            $validate = true;
            if (!isset($dataPost['amount']) || $dataPost['amount'] <= 0) {
                $validate = false;
                $message['amount'] = "Nhập chính xác số tiền nạp";
            }
            if (!isset($dataPost['message']) || !$dataPost['message']) {
                $validate = false;
                $message['message'] = "Thiếu mã nạp";
            }
            if (!isset($dataPost['images']) || !$dataPost['message']) {
                $validate = false;
                $message['images'] = "Phải tải lên ít nhất 1 ảnh";
            }
//            else {
//                foreach ($dataPost['images'] as $singleImage) {
//                    foreach ($singleImage as $image){
//                        if ($image['error'] != 0) {
//                            $validate = false;
//                            $message['images'] = 'Lỗi ảnh';
//                        }
//                    }
//                }
//            }
            $check_code = $this->DepositLogs->find()->where(['message' => $dataPost['message']])->first();
            if ($check_code) {
                $validate = false;
                $message['message'] = "Mã nạp đã tồn tại. Vui lòng thử lại";
            }
            if ($validate) {
//                $images = [];
//                foreach ($dataPost['images'] as $singleImage) {
//                    $thumbnail = $this->Upload->uploadSingle($singleImage);
//                    $images[] = $thumbnail;
//                }

                $depositLog = $this->DepositLogs->newEntity();
                $dataSave = [
                    'user_id' => $check['user_id'],
                    'creator_id' => $check['user_id'],
                    'title' => 'Nạp tiền tài khoản',
                    'message' => $dataPost['message'],
                    'amount' => $dataPost['amount'],
                    'type' => 1,
                    'status' => 2,
                    'images' => json_encode($dataPost['images'])
                ];
                $depositLog = $this->DepositLogs->patchEntity($depositLog, $dataSave);
                if ($this->DepositLogs->save($depositLog)) {
                    $code = [
                        'code' => "MNT" . str_pad($depositLog['id'], 9, '0', STR_PAD_LEFT)
                    ];
                    $depositLog = $this->DepositLogs->patchEntity($depositLog, $code);
                    if ($this->DepositLogs->save($depositLog)) {
                        $status = STT_SUCCESS;
                        $message = 'Success';
                        $data = $depositLog;
                    } else {
                        $status = STT_ERROR;
                        $message = 'Lỗi xảy ra trong quá trình lưu, vui lòng thử lại';
                    }
                } else {
                    $status = STT_ERROR;
                    $message = 'Lỗi xảy ra trong quá trình lưu, vui lòng thử lại';
                }
            } else {
                $status = STT_NOT_VALIDATION;
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not login';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function logTransaction()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user_id = $check['user_id'];
            $condition = [];
            $condition['user_id'] = $user_id;
            $this->loadModel('DepositLogs');
//            $limit = $this->request->getQuery('limit') ? $this->request->getQuery('limit') : 10;
//            $page = $this->request->getQuery('page') ? $this->request->getQuery('page') : 1;
            $listDeposit = $this->DepositLogs->find()->where($condition)
//                ->offset($limit * ($page - 1))->limit($limit)
                ->orderDesc('modified')->toArray();
            $list = [];
            foreach ($listDeposit as $k => $item) {
//                $item->amount = number_format($item->amount);
                if ($item->type == 1) {
                    if ($item->status == 2) {
                        if ($item->balance == 0) {
                            $item->balance = "Chờ xác nhận số dư";
                        }
                    } elseif ($item->status == 0) {
                        $item->balance = "Yêu cầu của bạn bị hủy";
                    } else {
                        $item->balance = number_format($item->balance);
                    }
                } else {
                    $item->balance = number_format($item->balance);
                }
                if ($listDeposit[$k]['images']) {
                    $listDeposit[$k]['images'] = json_decode($listDeposit[$k]['images'], true) ? json_decode($listDeposit[$k]['images'], true) : [0 => $listDeposit[$k]['images']];
                    foreach ($listDeposit[$k]['images'] as $key => $image) {
                        if (!$image) {
                            unset($listDeposit[$k]['images'][$key]);
                        }
                    }
                } else {
                    $listDeposit[$k]['images'] = [];
                }
                $list[] = $item;
            }
            $user = $this->Users->find()->where(['id' => $user_id])->first();
            $total_balance = number_format($user->balance);
            $data = [];
            $data['total_balance'] = $total_balance;
            $data['listDeposit'] = $list;
            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'Thành công',
                'data' => $data,
                '_serialize' => ['status', 'message', 'data']
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

    public function detailTransaction($id)
    {
        $this->loadModel('DepositLogs');

        $check = $this->Api->CheckLoginApi();
        if ($check['status']) {
            $condition = [];
            $condition['id'] = $id;

            $Deposit = $this->DepositLogs->find()->where($condition)->orderDesc('id')->toArray();
            foreach ($Deposit as $k => $item) {
                if ($Deposit[$k]['images']) {
                    $Deposit[$k]['images'] = json_decode($Deposit[$k]['images'], true) ? json_decode($Deposit[$k]['images'], true) : [0 => $Deposit[$k]['images']];
                    foreach ($Deposit[$k]['images'] as $key => $image) {
                        if (!$image) {
                            unset($Deposit[$k]['images'][$key]);
                        }
                    }
                } else {
                    $Deposit[$k]['images'] = [];
                }

            }
            $this->set([
                'status' => STT_SUCCESS,
                'message' => 'Thành công',
                'data' => $Deposit,
                '_serialize' => ['status', 'message', 'data']
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

    public function listBookingVinpearl()
    {
        $this->loadModel('Vinhmsbookings');
        $this->loadModel('VinhmsbookingRooms');
        $this->loadModel('Hotels');

        $check = $this->Api->checkLoginApi();
        $data = $this->getRequest()->getQuery();
        $condition = [];
        if (isset($data['fromDate']) && !empty($data['fromDate'])) {
            $condition['DATE(Vinhmsbookings.start_date) >='] = date('Y-m-d', strtotime($data['fromDate']));
        }
        if (isset($data['toDate']) && !empty($data['toDate'])) {
            $condition['DATE(Vinhmsbookings.end_date) <='] = date('Y-m-d', strtotime($data['toDate']));
        }
        if (isset($data['code']) && !empty($data['code'])) {
            $condition['code'] = $data['code'];
        }
        if (isset($data['agency_id']) && !empty($data['agency_id'])) {
            $id = intval($data['agency_id']);
            $condition['user_id'] = $id;
        }
//        if (isset($data['page']) && !empty($data['page'])) {
//            $page = $data['page'];
//        }
        if ($check['status']) {
            $role_id = $check['role_id'];
            $user_id = $check['user_id'];
            if ($role_id == 3) {
                $condition['user_id'] = $user_id;
                $listBookingVinpearl = $this->Vinhmsbookings->find()->contain(['Vinpayments'])->where($condition)->orderDesc('Vinhmsbookings.id')->toArray();
                $list = [];
                foreach ($listBookingVinpearl as $booking) {
                    $data = [];
                    $status = $this->Util->getStatusBookingVinpearl($booking, $role_id);
                    $numRoom = $this->VinhmsbookingRooms->find()->where(['vinhmsbooking_id' => $booking->id])->groupBy('room_index')->count();
                    $hotel = $this->Hotels->find()->where(['id' => $booking['hotel_id']])->first();

                    $dateStart = date('d/m/Y', strtotime($booking['start_date']));
                    $dateEnd = date('d/m/Y', strtotime($booking['end_date']));
                    $name = $booking['first_name'] . " " . $booking['sur_name'];
                    $day = date_diff(date_create($booking['start_date']), date_create($booking['end_date']));
                    $created = date('H:m - d/m/Y', strtotime($booking['created']));

                    $data['id'] = $booking['id'];
                    $data['code'] = $booking['code'];
                    $data['name'] = $name;
                    $data['hotel_name'] = $hotel['name'];
                    $data['start_date'] = $dateStart;
                    $data['end_date'] = $dateEnd;
                    $data['status_str'] = $status['status_str'];
                    $data['status_color'] = $status['status_color'];
                    $data['created'] = $created;
                    $data['price'] = $booking['price'] - $booking->revenue;
                    $data['day'] = $day->days + 1;
                    $data['night'] = $day->days;
                    $data['numRoom'] = $numRoom;
                    $list[] = $data;
                }
                $res['status'] = STT_SUCCESS;
                $res['data'] = $list;

                $this->set([
                    'status' => $res['status'],
                    'message' => 'Thành công',
                    'data' => $res['data'],
                    '_serialize' => ['status', 'message', 'data']
                ]);

            }
            if ($role_id == 2 || $role_id == 1) {
                $user = [];
                $user['id'] = "";
                $user['screen_name'] = "";
                if (isset($data['agency_id']) && !empty($data['agency_id'])) {
                    $user = $this->Users->find()->where(['id' => $condition['user_id']])->first();
                }
                $listBookingVinpearl = $this->Vinhmsbookings->find()->where($condition)->orderDesc('id')->toArray();
                $list = [];
                foreach ($listBookingVinpearl as $booking) {
                    if (!empty($user)) {
                        $user = $this->Users->find()->where(['id' => $booking['user_id']])->first();
                        $user['id'] = $user['id'];
                        $user['screen_name'] = $user['screen_name'];
                    }
                    $data = [];
                    $status = $this->Util->getStatusBookingVinpearl($booking, $role_id);
                    $numRoom = $this->VinhmsbookingRooms->find()->where(['vinhmsbooking_id' => $booking->id])->groupBy('room_index')->count();
                    $hotel = $this->Hotels->find()->where(['id' => $booking['hotel_id']])->first();

                    $dateStart = date('d/m/Y', strtotime($booking['start_date']));
                    $dateEnd = date('d/m/Y', strtotime($booking['end_date']));
                    $name = $booking['first_name'] . " " . $booking['sur_name'];
                    $day = date_diff(date_create($booking['start_date']), date_create($booking['end_date']));
                    $created = date('H:m - d/m/Y', strtotime($booking['created']));

                    $data['id'] = $booking['id'];
                    $data['code'] = $booking['code'];
                    $data['name'] = $name;
                    $data['user_id'] = $user['id'];
                    $data['user_name'] = $user['screen_name'];
                    $data['hotel_name'] = $hotel['name'];
                    $data['start_date'] = $dateStart;
                    $data['end_date'] = $dateEnd;
                    $data['status_str'] = $status['status_str'];
                    $data['status_color'] = $status['status_color'];
                    $data['created'] = $created;
                    $data['price'] = $booking['price'] - $booking['revenue'];
                    $data['price_net'] = $booking['price'] - $booking['revenue'] - $booking['sale_revenue'];
                    $data['profit'] = $booking['sale_revenue'];
                    $data['day'] = $day->days + 1;;
                    $data['night'] = $day->days;
                    $data['numRoom'] = $numRoom;
                    $list[] = $data;
                }
                $res['status'] = STT_SUCCESS;
                $res['data'] = $list;

                $this->set([
                    'status' => $res['status'],
                    'message' => 'Thành công',
                    'data' => $res['data'],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }

        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => $check['message'],
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }


    }

    public function listAgencyChat()
    {
        Log::write('debug', 'start call function: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
        $this->loadModel('Clients');
        $this->loadModel('Chats');
        $check = $this->Api->checkLoginApi();
        $status = STT_ERROR;
        $message = '';
        $data = [];
        if ($check['status']) {
            $sale = $this->Users->find()->where(['id' => $check['user_id']])->first();
            if ($sale) {
                if ($sale->role_id == 2) {
                    $dataChats = [];
                    $listAgencyUserIds = $this->Users->find('list', ['keyField' => 'id', 'valueField' => array('screen_name', 'avatar')])->where(['parent_id' => $sale->id, 'is_active' => 1])->toArray();
                    $clients = $this->Clients->find()->where(['user_id IN' => array_keys($listAgencyUserIds), 'login_expire IS NOT NULL'])->orderDesc('login_expire')->groupBy('user_id')->toArray();
                    $currentTime = time();
                    $listAgencyUser = [];
                    foreach ($listAgencyUserIds as $k => $value) {
                        $a = explode(';', $value);
                        $listAgencyUser[$k] = $a;
                    }

                    foreach ($listAgencyUser as $userId => $singleUser) {

                        $isOnline = false;
                        if (isset($clients[$userId]) && count($clients[$userId]) > 0 && (strtotime($clients[$userId][0]->login_expire) > $currentTime)) {
                            $isOnline = true;
                        }
                        $chatRoomId = $userId . '-' . $sale->id;
                        $chats = $this->Chats->find()->where(['chat_room_id' => $chatRoomId])->last();
                        $connection = ConnectionManager::get('default');
                        $count_read = $connection->execute('SELECT user_id, chat_room_id, COUNT(*) AS CountOf FROM chats WHERE  is_read = 0 && chat_room_id ="' . $chatRoomId . '" && user_id = ' . $userId . ' GROUP BY chat_room_id,user_id')->fetch('assoc');
                        if ($chats) {
                            $dataChats[$chats['sessionId']] = [
                                'chat_id' => $userId . '-' . $sale->id,
                                'screen_name' => $singleUser[0],
                                'avatar' => $singleUser[1],
                                'text' => $chats ? $chats['msg'] : '',
                                'video' => '',
                                'image' => $chats ? $chats['img'] : '',
                                'created' => $chats ? $chats['created'] : '',
                                'unread_count' => $count_read ? $count_read['CountOf'] : 0,
                                'is_online' => $isOnline,
                            ];
                        } else {
                            $dataChats[$userId] = [
                                'chat_id' => $userId . '-' . $sale->id,
                                'screen_name' => $singleUser[0],
                                'avatar' => $singleUser[1],
                                'text' => $chats ? $chats['msg'] : '',
                                'video' => '',
                                'image' => $chats ? $chats['img'] : '',
                                'created' => $chats ? $chats['created'] : '',
                                'unread_count' => $count_read ? $count_read['CountOf'] : 0,
                                'is_online' => $isOnline,
                            ];
                        }


                    }
                    ksort($dataChats);
                    $reverse = array_reverse($dataChats, true);
                    $data = array_values($reverse);
                    $status = STT_SUCCESS;
                    $message = 'Success';
                } else {
                    $status = STT_INVALID;
                    $message = 'No Permission';
                }
            } else {
                $status = STT_INVALID;
                $message = 'User Not Found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not Logged In';
        }

        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status', 'message', 'data']
        ]);
        Log::write('debug', 'end call function: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
    }

    public function createAccount()
    {
        $data = $this->request->getData();
        $data['role_id'] = 3;
        $data['is_active'] = 0;
        $data['parent_id'] = 0;

        $status = STT_ERROR;
        $message = null;
        $isValidate = true;
        if (!isset($data['username']) || empty($data['username'])) {
            $isValidate = false;
            $message['username'] = 'Tên đăng nhập không được để trống.';
        }
        if (!isset($data['password']) || empty($data['password'])) {
            $isValidate = false;
            $message['password'] = 'Mật khẩu không được để trống.';
        }
        if (!isset($data['email']) || empty($data['email'])) {
            $isValidate = false;
            $message['email'] = 'Email không được để trống.';
        }
        if (!isset($data['screen_name']) || empty($data['screen_name'])) {
            $isValidate = false;
            $message['screen_name'] = 'Họ và tên không được để trống.';
        }
        if (!isset($data['phone']) || empty($data['phone'])) {
            $isValidate = false;
            $message['phone'] = 'Số điện thoại không được để trống.';
        }
        $emailExist = $this->Users->find()->where(['email' => $data['email']]);
        if ($emailExist->count() > 0) {
            $isValidate = false;
            $message['email'] = 'Email đã tồn tại.';
        }
        $usernameExist = $this->Users->find()->where(['username' => $data['username']]);
        if ($usernameExist->count() > 0) {
            $isValidate = false;
            $message['username'] = 'Tên đăng nhập đã tồn tại.';
        }
        if ($isValidate) {
            $hasher = new DefaultPasswordHasher();
            $data['password'] = $hasher->hash($data['password']);
            $user = $this->Users->newEntity();
            if ($this->request->is('post')) {
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $status = STT_SUCCESS;
                    $message = 'Đăng ký thành công';
                } else {
                    $status = STT_ERROR;
                    $message = 'Đăng ký Không thành công';
                }

            }
        }

        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $user,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function changeAvatar()
    {
        $this->loadModel('Users');
        $this->loadComponent('Upload');
        $status = STT_ERROR;
        $message = '';
        $img = '';
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            if ($user) {
                if ($this->getRequest()->is('post')) {
                    $data = $this->getRequest()->getData();
                    if (isset($data['image'])) {
                        $image_type = getimagesize($data['image']['tmp_name'])[2];
                        if (in_array($image_type, array(IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
                            if ($data['image']['error'] == 0) {
                                $img = $this->Upload->uploadSingle($data['image']);
                                $user = $this->Users->patchEntity($user, ['avatar' => $img]);
                                if ($this->Users->save($user)) {
                                    $status = STT_SUCCESS;
                                } else {
                                    $status = STT_ERROR;
                                    $message = 'Có lỗi xảy ra. Vui lòng thử lại';
                                }
                            } else {
                                $message = 'Có lỗi xảy ra. Vui lòng thử lại';
                            }
                        } else {
                            $status = STT_NOT_VALIDATION;
                            $message = 'Sai định dạng file';
                        }
                    } else {
                        $status = STT_NOT_VALIDATION;
                        $message = 'Thông tin không hợp lệ';
                    }
                } else {
                    $status = STT_NOT_ALLOW;
                    $message = 'Method không hợp lệ';
                }
            } else {
                $status = STT_NOT_FOUND;
                $message = 'User Not Found';
            }
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Not Logged In';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => $img,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function saleCreateMerchant()
    {
        $status = STT_ERROR;
        $message = null;
        $check = $this->Api->checkLoginApi();
        if ($check) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            if ($user) {
                if ($user->role_id == 2) {
                    $data = $this->request->getData();
                    $isValidate = true;
                    if (!isset($data['username']) || empty($data['username'])) {
                        $isValidate = false;
                        $message['username'] = 'Tên đăng nhập không được để trống.';
                    }
                    if (!isset($data['password']) || empty($data['password'])) {
                        $isValidate = false;
                        $message['password'] = 'Mật khẩu không được để trống.';
                    }
                    if (!isset($data['email']) || empty($data['email'])) {
                        $isValidate = false;
                        $message['email'] = 'Email không được để trống.';
                    }
                    if (!isset($data['screen_name']) || empty($data['screen_name'])) {
                        $isValidate = false;
                        $message['screen_name'] = 'Họ và tên không được để trống.';
                    }
                    if (!isset($data['phone']) || empty($data['phone'])) {
                        $isValidate = false;
                        $message['phone'] = 'Số điện thoại không được để trống.';
                    }
                    $emailExist = $this->Users->find()->where(['email' => $data['email']]);
                    if ($emailExist->count() > 0) {
                        $isValidate = false;
                        $message['email'] = 'Email đã tồn tại.';
                    }
                    $usernameExist = $this->Users->find()->where(['username' => $data['username']]);
                    if ($usernameExist->count() > 0) {
                        $isValidate = false;
                        $message['username'] = 'Tên đăng nhập đã tồn tại.';
                    }
                    if ($isValidate) {
                        $hasher = new DefaultPasswordHasher();
                        $data['password'] = $hasher->hash($data['password']);
                        $data['role_id'] = 3;
                        $data['is_active'] = 1;
                        $data['parent_id'] = $user->id;
                        $newAgency = $this->Users->newEntity();
                        if ($this->request->is('post')) {
                            $newAgency = $this->Users->patchEntity($newAgency, $data);
                            if ($this->Users->save($newAgency)) {
                                $status = STT_SUCCESS;
                                $message = 'Đăng ký thành công';
                            } else {
                                $status = STT_ERROR;
                                $message = 'Đăng ký Không thành công';
                            }
                        } else {

                        }
                    }
                } else {

                }
            } else {

            }
        } else {

        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => [],
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function userInfo()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            $data = [];
            $data['id'] = $user['id'];
            $data['role_id'] = $user['role_id'];
            $data['parent_id'] = $user['parent_id'];
            $data['username'] = $user['username'];
            $data['screen_name'] = $user['screen_name'];
            $data['email'] = $user['email'];
            $data['phone'] = $user['phone'];
            $data['avatar'] = $user['avatar'];
            $data['balance'] = $user['balance'];
            $data['bank_code'] = $user['bank_code'];
            $data['bank_name'] = $user['bank_name'];
            $data['bank_master'] = $user['bank_master'];

            $this->set([
                'status' => STT_SUCCESS,
                'message' => "Success",
                'data' => $data,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_ERROR,
                'mesage' => "Bạn chưa đăng nhập",
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function editUserInfo()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $data = $this->request->getData();
            $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
            $info = [];
            if (isset($data['screen_name']) && !empty($data['screen_name'])) {
                $user['screen_name'] = ($data['screen_name']);
                $info['screen_name'] = $data['screen_name'];
            }
            if (isset($data['email']) && !empty($data['email'])) {
                $user['email'] = ($data['email']);
                $info['email'] = $data['email'];
            }
            if (isset($data['phone']) && !empty($data['phone'])) {
                $user['phone'] = ($data['phone']);
                $info['phone'] = $data['phone'];
            }
            if (isset($data['bank_code']) && !empty($data['bank_code'])) {
                $user['bank_code'] = ($data['bank_code']);
                $info['bank_code'] = $data['bank_code'];
            }
            if (isset($data['bank_name']) && !empty($data['bank_name'])) {
                $user['bank_name'] = ($data['bank_name']);
                $info['bank_name'] = $data['bank_name'];
            }
            if (isset($data['bank_master']) && !empty($data['bank_master'])) {
                $user['bank_master'] = ($data['bank_master']);
                $info['bank_master'] = $data['bank_master'];
            }
            $this->Users->save($user);
            $info['id'] = $user['id'];
            $info['role_id'] = $user['role_id'];
            $info['parent_id'] = $user['parent_id'];
            $info['username'] = $user['username'];
            $info['balance'] = $user['balance'];
            $this->set([
                'status' => STT_SUCCESS,
                'message' => "Success",
                'data' => $info,
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_ERROR,
                'mesage' => "Bạn chưa đăng nhập",
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function addClientExpoToken()
    {
        $check = $this->Api->checkLoginApi();
        $this->loadModel('UserExpotokens');
//        dd($check);
        if ($check['status']) {
            $data = $this->request->getData();
            $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên', 'data' => []];
            $error_dataRequest = false;
            if (isset($data['expo_push_token']) && $data['expo_push_token']) {
            } else {
                $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên expo_push_token'];
                $error_dataRequest = true;
            }
            if (isset($data['client_id']) && $data['client_id']) {
            } else {
                $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên client_id'];
                $error_dataRequest = true;
            }
            if (isset($data['user_id']) && $data['user_id']) {
            } else {
                $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên user_id'];
                $error_dataRequest = true;
            }
            if (!$error_dataRequest) {
                $userExpos = $this->UserExpotokens->find()->where(['clientId' => $data['client_id']])->first();
                $dataSave = [
                    'user_id' => $data['user_id'],
                    'clientId' => $data['client_id'],
                    'expo_push_token' => $data['expo_push_token'],
                ];
                if ($userExpos) {
                    $userExpos['expo_push_token'] = $data['expo_push_token'];
                    $userExpos['user_id'] = $data['user_id'];
                    $this->UserExpotokens->save($userExpos);
                } else {
                    $userExpotokens = $this->UserExpotokens->newEntity();
                    $userExpotokens = $this->UserExpotokens->patchEntity($userExpotokens, $dataSave);
                    $this->UserExpotokens->save($userExpotokens);
                }
                $response = ['success' => STT_SUCCESS, 'message' => 'Thành Công'];
            }

            $this->set([
                'status' => $response['success'],
                'message' => $response['message'],
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set(['status' => STT_ERROR,
                'mesage' => "Bạn chưa đăng nhập",
                'data' => [],
                '_serialize' => ['status', 'message', 'data']]);
        }
    }

    public function clearSession()
    {
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $this->loadModel('Users');
            $this->loadModel('Clients');
            $data = $this->request->getData();
            $user = $this->Users->get($check['user_id']);
            $Clients = $this->Clients->find()->where(['user_id' => $check['user_id'], 'clientId' => $data['clientId']])->first();
            if ($Clients) {
                $this->Clients->delete($Clients);
                $redis = new \Redis();
                $redis->connect('127.0.0.1', 6379);
                $redis->del($user->username . "-" . $data['clientId']);
                $this->set([
                    'status' => STT_SUCCESS,
                    'message' => "Xóa thành công session",
                    'data' => [],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            } else {
                $this->set([
                    'status' => STT_ERROR,
                    'message' => "Không tìm thấy clientID",
                    'data' => [],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => "Bạn chưa đăng nhập",
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
    }

    public function test()
    {
        $this->loadModel('Users');
        $this->loadModel('Clients');
        $data = $this->Util->getRatePlans('5994c2db-cd76-401c-ba2e-e178ae118a8d');
        dd($data);
        return $data;
    }
}
