<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Chat Controller
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\Chat[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ChatController extends AppController
{
    public function sendFirebaseMessage()
    {
        $this->loadModel('Users');
        $this->loadModel('Chats');
        $this->loadModel('UserExpotokens');
        $data = $this->request->getData();
        $firestore = new FirestoreClient([
            'projectId' => 'mustgoproj',
        ]);
        $chatData = [];
        $response = ['success' => false, 'message' => '', 'message_id' => ''];
        $is_read = intval($data['is_read']);
        $user = $this->Users->get($this->Auth->user('id'));
        $roomId = '';
        $images = [];
        if (!empty($data['images'])){
            $images[] = $this->Upload->uploadSingle($data['images']);
        }
        if ($user->role_id == 3) {
            $roomId = $user->id . '-' . $user->parent_id;
            $sale_id = $user->parent_id;
            $type = CHAT_AGENCY_TO_CLIENT;
            $receiver = $user->parent_id;
        } else {
            $agencyId = $data['agency_id'];
            $roomId = $agencyId . '-' . $user->id;
            $sale_id = $user->id;
            $type = CHAT_CLIENT_TO_AGENCY;
            $receiver = $agencyId;
        }
        $timeNow = time();
        $connection = ConnectionManager::get('default');
        $count_read = $connection->execute('SELECT user_id, chat_room_id, COUNT(*) AS CountOf FROM chats WHERE  is_read = 0 && chat_room_id ="' . $roomId . '" && user_id = ' . $user->id . ' GROUP BY chat_room_id,user_id')->fetch('assoc');
        try {
            if ($firestore->collection('chatroom')->document($roomId)->snapshot()->exists()) {
                $document = $firestore->collection('chatroom')->document($roomId);
                $document->set([
                    'latestMessage' => [
                        'createdAt' => $timeNow,
                        'createdBy' => $user->id,
                        'text' => $data['message'],
                        'img' => $images,
                    ],
                    'is_read' => 0,
                    'is_read_number' => $count_read ? $count_read['CountOf'] + 1 : 1,
                    'sale_id' => $sale_id,
                    'updatedAt' => $timeNow
                ]);
                $document->collection('messages')->document($timeNow)->create([
                    'createdAt' => $timeNow,
                    'createdBy' => $user->id,
                    'id' => $timeNow,
                    'text' => $data['message'],
                    'img' => $images,
                    'is_read' => 0,
                    'type' => 1
                ]);
            } else {
                $firestore->collection('chatroom')->document($roomId)->create([
                    'createdAt' => $timeNow,
                    'id' => $user->id . '-' . $user->parent_id,
                    'sale_id' => $sale_id,
                    'latestMessage' => [
                        'createdAt' => $timeNow,
                        'createdBy' => $user->id,
                        'text' => $data['message'],
                        'img' => $images,
                    ],
                    'is_read' => 0,
                    'is_read_number' => $count_read ? $count_read['CountOf'] + 1 : 1,
                    'updatedAt' => $timeNow
                ]);
                $document = $firestore->collection('chatroom')->document($roomId);
                $document->collection('messages')->document($timeNow)->create([
                    'createdAt' => $timeNow,
                    'createdBy' => $user->id,
                    'id' => $timeNow,
                    'text' => $timeNow,
                    'img' => $images,
                    'is_read' => 0,
                    'type' => 1
                ]);
            }
            $chatData = [
                'sessionId' => time(),
                'clientId' => '',
                'user_id' => $user->id,
                'receiver_id' => $receiver,
                'type' => $type,
                'chat_room_id' => $roomId,
                'msg' => $data['message'],
                'is_read' => 0,
                'img' => !empty($images) ? json_encode($images) : '',
            ];
            $chat = $this->Chats->newEntity();
            $chat = $this->Chats->patchEntity($chat, $chatData);
            $this->Chats->save($chat);
            //start repo
            // You can quickly bootup an expo instance
            $countUnread = $this->Chats->find()->where(['receiver_id' => $receiver, 'is_read' => 0])->count();
            $expo = \ExponentPhpSDK\Expo::normalSetup();
            $expo_tokens = $this->UserExpotokens->find()->where(['user_id' => $receiver])->toArray();
            if ($expo_tokens) {
                $notification = ['body' => $data['message'], 'title' => $user->screen_name, 'badge' => $countUnread];
                $arrayId = [];
                foreach ($expo_tokens as $expo_token) {
                    $arrayId[] = $expo_token['expo_push_token'];
                }
                $notification['to'] = $arrayId;
                $this->Util->sendNotifical($notification);
            }
            //end repo
            $response['success'] = true;
            $response['message'] = $data['message'];
            $response['message_id'] = $timeNow;
        }
        catch (Exception $exception) {
            $response['success'] = false;
            $response['message'] = $exception->getMessage();
        }
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }

    public function changeStatusMess()
    {
        $data = $this->request->getData();
        $this->loadModel('Chats');
        $this->loadModel('UserExpotokens');
        $room_id = $data['room_id'];
        $id = explode('-', $room_id);
        $userId = $id[0];
        $sale_id = $id[1];
        if (!empty($room_id) && $userId) {
            $timeNow = time();
            $chat = $this->Chats->find()->where(['chat_room_id' => $room_id, 'receiver_id' => $this->Auth->user('id')])->last();
            if ($chat->is_read == 0) {
//                $firestore = new FirestoreClient([
//                    'projectId' => 'mustgoproj',
//                ]);
//                if ($firestore->collection('chatroom')->document($room_id)->snapshot()->exists()) {
//                    $document = $firestore->collection('chatroom')->document($room_id);
//                    $document->set([
//                        'latestMessage' => [
//                            'createdAt' => $chat['sessionId'],
//                            'createdBy' => $chat->user_id,
//                            'text' => $chat['msg'],
//                            'img' => $chat['img'],
//                        ],
//                        'is_read' => 1,
//                        'is_read_number' => 0,
//                        'sale_id' => $sale_id,
//                        'updatedAt' => $timeNow
//                    ]);
//                }
                $this->Chats->query()->update('chats')->set(['is_read' => 1])->where(['chat_room_id' => $room_id, 'receiver_id' => $this->Auth->user('id'), 'is_read' => 0])->execute();
                //start repo
                // You can quickly bootup an expo instance
                $expo = \ExponentPhpSDK\Expo::normalSetup();
                $expo_tokens = $this->UserExpotokens->find()->where(['user_id' => $this->Auth->user('id')])->toArray();
                $countUnread = $this->Chats->find()->where(['receiver_id' => $this->Auth->user('id'), 'is_read' => 0])->count();
                if ($expo_tokens) {
                    $notification = ['badge' => $countUnread];
                    $arrayId = [];
                    foreach ($expo_tokens as $expo_token) {
                        $arrayId[] = $expo_token['expo_push_token'];
                    }
                    $notification['to'] = $arrayId;
                    $this->Util->sendNotifical($notification);
                }
            }
        }
        $response['success'] = true;
        $response['message'] = 'Success';
        $output = $this->response;
        $output = $output->withType('json');
        $output = $output->withStringBody(json_encode($response));
        return $output;
    }
}
