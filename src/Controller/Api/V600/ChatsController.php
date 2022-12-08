<?php

namespace App\Controller\Api\V600;

use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Chat Controller
 * @property \App\Model\Table\ChatsTable $Chats
 *
 * @method \App\Model\Entity\Chat[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ChatsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['getUser', 'sendChat', 'listChatRoom', 'isRead', 'readMessage']);
    }

    public function getUser()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(WWW_ROOT . '/firebase/projecttestchat-firebase-adminsdk-d9km6-d3e53dc71b.json');
//        try {
//            $firebase = (new Factory)
//                ->withServiceAccount($serviceAccount)
//                ->create();
//        } catch (\Exception $e) {
//
//        }

//        $database = $firebase->getDatabase()->getReference('chat')->getChildKeys();
//        dd($database);

        $firestore = new FirestoreClient([
            'projectId' => 'mustgoproj'
        ]);
        $collectionReference = $firestore->collection('chat')->document('uTGokJA0m00bBWgKu5vr')->snapshot()->data();
    }

    public function sendChat()
    {
        Log::write('debug', 'start call function sendChat: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
        $this->loadModel('Chats');
        $this->loadModel('UserExpotokens');
        $this->loadModel('Users');
        $check = $this->Api->checkLoginApi();
        if ($check['status']) {
            $dataRequest = $this->request->getData();
            $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên', 'data' => []];
            $error = false;
            if (isset($dataRequest['chat_room_id']) && $dataRequest['chat_room_id']) {
            } else {
                $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên chat_room_id'];
                $error = true;
            }
            if ($error) {
            } else {
                $data = [
                    'sessionId' => time(),
                    'user_id' => isset($dataRequest['user_id']) ? $dataRequest['user_id'] : $check['user_id'],
                    'chat_room_id' => $dataRequest['chat_room_id'],
                    'msg' => isset($dataRequest['msg']) ? $dataRequest['msg'] : '',
                    'is_read' => 0,
                    'img' => isset($dataRequest['images']) ? json_encode($dataRequest['images']) : '',
                ];
                if (isset($dataRequest['msg']) && $dataRequest['msg']) {
                    $content = $dataRequest['msg'];
                } else if (isset($dataRequest['images']) && $dataRequest['images']){
                    $content = 'Đã gửi hình ảnh';
                } else{
                    $content = 'Đã gửi một tin nhắn';
                }
                $user = $this->Users->find()->where(['id' => $check['user_id']])->first();
                $userArray = explode('-', $dataRequest['chat_room_id']);
                if ($check['role_id'] == 3) {
                    $data['type'] = CHAT_AGENCY_TO_CLIENT;
                    $receiver = $userArray[1];

                } else {
                    $data['type'] = CHAT_CLIENT_TO_AGENCY;
                    $receiver = $userArray[0];
                }
                $data['receiver_id'] = $receiver;
                $messege = $this->Chats->newEntity();
                $messege = $this->Chats->patchEntity($messege, $data);
                if ($this->Chats->save($messege)) {
                    $response = ['success' => STT_SUCCESS, 'message' => 'Thành công', 'data' => ['messege_id' => $messege->id]];
                } else {
                    $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Lỗi khi lưu dữ liệu'];
                }
                //start repo
                // You can quickly bootup an expo instance
                $countUnread = $this->Chats->find()->where(['receiver_id' => $receiver, 'is_read' => 0])->count();
                $expo = \ExponentPhpSDK\Expo::normalSetup();
                $expo_tokens = $this->UserExpotokens->find()->where(['user_id' => $receiver])->toArray();
                if ($expo_tokens) {
                    $notification = ['body' => $content ,'title' => $user->screen_name,'badge'=> $countUnread];
                    $arrayId = [];
                    foreach ($expo_tokens as $expo_token) {
                        $arrayId[] = $expo_token['expo_push_token'];
                    }
                    $notification['to'] = $arrayId;
                   $this->Util->sendNotifical($notification);
                }
                //end repo
            }
            $this->set([
                'status' => $response['success'],
                'message' => $response['message'],
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
        Log::write('debug', 'end call function sendChat: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
    }

    public function readMessage()
    {
        Log::write('debug', 'start call function readMessage: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
        $this->loadModel('Chats');
        $this->loadModel('UserExpotokens');
        $check = $this->Api->checkLoginApi();
        $status = STT_ERROR;
        $message = '';
        if ($check['status']) {
            $chatRoomId =  $this->request->getData('chat_room_id');
            $countUnread = $this->Chats->find()->where(['chat_room_id' => $chatRoomId, 'receiver_id' => $check['user_id'] , 'is_read' => 0])->count();
            if ($countUnread > 0) {
                if ($this->Chats->updateAll(['is_read' => 1], ['chat_room_id' => $chatRoomId,'receiver_id' => $check['user_id'] , 'is_read' => 0])) {
                    $status = STT_SUCCESS;
                    $message = 'Thành công';
                } else {
                    $status = STT_NOT_SAVE;
                    $message = 'Đọc tin nhắn thất bại, vui lòng thử lại';
                }
            } else {
                $status = STT_SUCCESS;
                $message = 'Thành công';
            }
            //start repo
            // You can quickly bootup an expo instance
            $expo = \ExponentPhpSDK\Expo::normalSetup();
            $expo_tokens = $this->UserExpotokens->find()->where(['user_id' => $check['user_id']])->toArray();
            $countUnread = $this->Chats->find()->where(['receiver_id' => $check['user_id'], 'is_read' => 0])->count();
            if ($expo_tokens) {
                $notification = ['badge'=> $countUnread];
                $arrayId = [];
                foreach ($expo_tokens as $expo_token) {
                    $arrayId[] = $expo_token['expo_push_token'];
                }
                $notification['to'] = $arrayId;
                $this->Util->sendNotifical($notification);
            }
            //end repo
        } else {
            $status = STT_NOT_LOGIN;
            $message = 'Bạn chưa đăng nhập';
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            'data' => [],
            '_serialize' => ['status', 'message', 'data']
        ]);
        Log::write('debug', 'end call function readMessage: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
    }

    public function listChatRoom()
    {
        Log::write('debug', 'start call function listChatRoom: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
        $this->loadModel('Chats');
        $this->loadModel('UserExpotokens');
        $check = $this->Api->checkLoginApi();
        $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên', 'data' => []];
        if ($check['status']) {
            $data = $this->getRequest()->getQuery();
            $page = isset($data['page']) && $data['page'] ? $data['page'] : 0;
            $limit = isset($data['limit']) && $data['limit'] ? $data['limit'] : 20;
            $error = false;
            if (isset($data['chat_room_id']) && $data['chat_room_id']) {
            } else {
                $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên chat_room_id'];
                $error = true;
            }
            if (isset($data['user_id']) && $data['user_id']) {
            } else {
                $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên user_id'];
                $error = true;
            }
            if (!$error) {
                $chat = $this->Chats->find()->where(['chat_room_id' => $data['chat_room_id']])->last();
                if ($chat && $chat->is_read == 0 && $chat->user_id != $check['user_id']) {
                    $this->Chats->query()->update('chats')->set(['is_read' => 1])->where(['chat_room_id' => $data['room_id'], 'user_id' => $chat->user_id, 'is_read' => 0])->execute();
                }
                $chats = $this->Chats->find()->where(['chat_room_id' => $data['chat_room_id']])->orderDesc('created')->toArray();
                $response = ['success' => STT_SUCCESS, 'message' => 'Thành công'];
                $response['data'] = [
                    'page' => $page,
                    'limit' => $limit,
                    'dataMessage' => $chats
                ];
                $chats = array_slice($chats, $limit * ($page - 1), 10);
                //start repo
                // You can quickly bootup an expo instance
                $expo = \ExponentPhpSDK\Expo::normalSetup();
                $expo_tokens = $this->UserExpotokens->find()->where(['user_id' => $check['user_id']])->toArray();
                $countUnread = $this->Chats->find()->where(['receiver_id' => $check['user_id'], 'is_read' => 0])->count();
                if ($expo_tokens) {
                    $notification = ['badge'=> $countUnread];
                    $arrayId = [];
                    foreach ($expo_tokens as $expo_token) {
                        $arrayId[] = $expo_token['expo_push_token'];
                    }
                    $notification['to'] = $arrayId;
                    $this->Util->sendNotifical($notification);
                }
                //end repo
            }
            $this->set([
                'status' => $response['success'],
                'message' => $response['message'],
                'data' => $response['data'],
                '_serialize' => ['status', 'message', 'data']
            ]);
        } else {
            $this->set([
                'status' => STT_NOT_LOGIN,
                'message' => 'Bạn chưa đăng nhập',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }
        Log::write('debug', 'start call function listChatRoom: ' . date('H:i:s') . ' milisec: ' . round(microtime(true) * 1000));
    }
//    public function isRead(){
//        $this->loadModel('Chats');
//        $check = $this->Api->checkLoginApi();
////        dd($check);
//        $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên','data' => []];
//        if ($check['status']){
//            $data = $this->getRequest()->getQuery();
//            $error = false;
//            if (isset($data['room_id']) && $data['room_id']){
//            } else{
//                $response = ['success' => STT_NOT_VALIDATION, 'message' => 'Kiểm tra lại dữ liệu gửi lên room_id'];
//                $error = true;
//            }
//            if (!$error){
//                $chat = $this->Chats->find()->where(['chat_room_id' => $data['room_id']])->last();
//                if ($chat && $chat->is_read == 1 && $chat->user_id != $check['user_id']){
////                $timeNow = time();
////                $firestore = new FirestoreClient([
////                    'projectId' => 'mustgoproj',
////                ]);
////                if ($firestore->collection('chatroom')->document($room_id)->snapshot()->exists()) {
////                    $document = $firestore->collection('chatroom')->document($room_id);
////                    $document->set([
////                        'latestMessage' => [
////                            'createdAt' => $chat['sessionId'],
////                            'createdBy' => $chat->user_id,
////                            'text' => $chat['msg'],
////                            'img' => $chat['img'],
////                        ],
////                        'is_read' => 1,
////                        'is_read_number' => 0,
////                        'sale_id' => $sale['id'],
////                        'updatedAt' => $timeNow
////                    ]);
////                }
//                    $this->Chats->query()->update('chats')->set(['is_read' => 0])->where(['chat_room_id' => $data['room_id'], 'user_id'=>$chat->user_id,'is_read'=> 1])->execute();
//                    $response = ['success' => STT_SUCCESS, 'message' => 'Thành công'];
//                }
//                else{
//                    $response = ['success' => STT_SUCCESS, 'message' => 'Tin nhắn đã đọc.'];
//                }
//            }
//            $this->set([
//                'status' => $response['success'],
//                'message' => $response['message'],
//                'data' => '',
//                '_serialize' => ['status', 'message', 'data']
//            ]);
//        }
//        else {
//            $this->set([
//                'status' => STT_NOT_LOGIN,
//                'message' => 'Bạn chưa đăng nhập',
//                'data' => [],
//                '_serialize' => ['status', 'message', 'data']
//            ]);
//        }
//    }
}
