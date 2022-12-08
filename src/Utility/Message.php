<?php

namespace App\Utility;

require_once(ROOT . DS . "vendor" . DS . "autoload.php");

use Ratchet;
use React;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Cake\ORM\TableRegistry;

class Message implements MessageComponentInterface
{

    protected $deviceConnected;
    protected $clients;
    protected $agencies;
    protected $findingClients;
    protected $acceptedLists;
    protected $pendingPushNoti;

    public function __construct(React\EventLoop\LoopInterface $loop)
    {
        $this->deviceConnected = [];
        $this->clients = [];
        $this->agencies = [];
        $this->findingClients = [];
        $this->acceptedLists = [];
        $this->pendingPushNoti = [];

        $this->Sockets = TableRegistry::get('Sockets');
        $this->UserSessions = TableRegistry::get('UserSessions');
        $this->Users = TableRegistry::get('Users');
        $this->Chats = TableRegistry::get('Chats');
        $this->Clients = TableRegistry::get('Clients');

        $this->Hotels = TableRegistry::getTableLocator()->get('Hotels');
        $this->HomeStays = TableRegistry::getTableLocator()->get('HomeStays');
        $this->Vouchers = TableRegistry::getTableLocator()->get('Vouchers');
        $this->LandTours = TableRegistry::getTableLocator()->get('LandTours');
        $this->Connections = TableRegistry::getTableLocator()->get('Connections');
        $this->Rooms = TableRegistry::getTableLocator()->get('Rooms');
        $this->Bookings = TableRegistry::getTableLocator()->get('Bookings');

        $loop->addPeriodicTimer(10, function () {
            $this->_pushPendingNotification();
        });
        $loop->addPeriodicTimer(30, function () {
            $this->sendPing();
        });

    }

    public function onOpen(ConnectionInterface $conn)
    {

        $this->deviceConnected[$conn->resourceId]['connection'] = $conn;
        $this->deviceConnected[$conn->resourceId]['ping'] = 0;
        var_dump("On Open: " . $conn->resourceId);
// Store the new connection to send messages to later
//        $this->clients[$conn->resourceId] = [
//            'connection' => $conn,
//        ];
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        var_dump('ResourceId: ' . $from->resourceId . ' - Message: ' . $msg);
        $data = json_decode($msg);
        $valid_functions = ['connect', 'find', 'found', 'notfound', 'stop', 'accept', 'decline', 'sendagency', 'findv2', 'findv3', 'message', 'join', 'getmessage', 'read', 'getlistchat', 'getunread', 'pong'];
        if (in_array($data->event, $valid_functions)) {
            $functionName = 'event' . $data->event;
            $this->$functionName($from, $data);
        } else {
            $from->send('INVALID REQUEST');
        }
    }

    private function eventconnect(ConnectionInterface $from, $data)
    {

        $newSocket = $this->Sockets->find()->where(['user_id' => $data->user_id, 'fingerPrint' => $data->fingerprint])->first();
        if ($data->user_id) {
            $this->agencies[$data->fingerprint]['user_id'] = $data->user_id;
            $this->agencies[$data->fingerprint]['connection'] = $from;
            $this->agencies[$data->fingerprint]['fingerprint'] = $data->fingerprint;
            $this->agencies[$data->fingerprint]['ping'] = 0;
        } else {
            $this->clients[$data->fingerprint]['user_id'] = $data->user_id;
            $this->clients[$data->fingerprint]['connection'] = $from;
            $this->clients[$data->fingerprint]['fingerprint'] = $data->fingerprint;
            $this->clients[$data->fingerprint]['ping'] = 0;
        }
        if (!$newSocket) {
            $newSocket = $this->Sockets->newEntity();
        }
        $newSocket = $this->Sockets->patchEntity($newSocket, ['user_id' => $data->user_id, 'resourceId' => $from->resourceId, 'fingerPrint' => $data->fingerprint]);
        $this->Sockets->save($newSocket);
        $send_data = [
            'event' => 'connect',
            'clients' => $this->clients,
        ];
        $from->send(json_encode(['event' => 'connected']));

        if ($this->findingClients) {
            if ($data->user_id) {
                foreach ($this->findingClients as $key => $finding) {
                    $data_send = [
                        'event' => 'sendagency',
                        'fingerPrint' => $key,
                        'priority' => true,
                        'timestamp' => $finding['timestamp'],
                        'haveRef' => $finding['hasRef']
                    ];
                    $from->send(json_encode($data_send));
                }
            } else {
                if (isset($this->findingClients[$data->fingerprint])) {
                    $this->findingClients[$data->fingerprint]['connection'] = $from;
                    if ($this->findingClients[$data->fingerprint]['ref']) {
                        $ref = $this->findingClients[$data->fingerprint]['ref'];
                        $user = $this->Users->find()->where(['ref_code' => trim($ref)])->first();
                        $send_data = [
                            'event' => 'accept',
                            'screen_name' => $user->screen_name,
                            'avatar' => $user->avatar,
                            'fbid' => $user->fbid,
                            'zalo' => $user->zalo
                        ];
                        $from->send(json_encode($send_data));
                        unset($this->findingClients[$data->fingerprint]);
                    }
                }
            }
        }

//        $this->sendMessageToAll($send_data);
    }

    private function eventaccept(ConnectionInterface $from, $data)
    {
        $timestamp = $data->timestamp;
        if (empty($this->acceptedLists[$timestamp])) {
            $this->acceptedLists[$timestamp] = $data->fingerPrint;
//            $this->acceptedLists[$data->fingerPrint] = $from->resourceId;
//            $this->acceptedLists[$data->fingerPrint]['resourceId'] = $from->resourceId;
//            $this->acceptedLists[$data->fingerPrint]['user_id'] = $data->user_id;
            $user = $this->Users->get($data->user_id);
            $send_data = [
                'event' => 'accept',
                'user_id' => $user->id,
                'screen_name' => $user->screen_name,
                'avatar' => $user->avatar,
                'fbid' => $user->fbid,
                'zalo' => $user->zalo,
                'agencyFingerprint' => $data->fingerPrint
            ];
            if (isset($this->findingClients[$data->fingerPrint]['connection'])) {
                $client = $this->findingClients[$data->fingerPrint]['connection'];
                $client->send(json_encode($send_data));
                unset($this->findingClients[$data->fingerPrint]);
            }

        } else {
            echo 'Already accepted';
        }
    }

    private function eventfind(ConnectionInterface $from, $data)
    {
        if ($data->ref_code) {
            $ref = $data->ref_code;
        } else {
            $ref = '';
        }
        $timestamp = time();

        $haveRef = false;
        $this->findingClients[$data->fingerprint]['connection'] = $from;
        $this->findingClients[$data->fingerprint]['ref'] = $data->ref_code;
        $this->findingClients[$data->fingerprint]['timestamp'] = $timestamp;
        if ($ref) {
            $mainUserId = $this->Users->find()->where(['ref_code' => trim($ref)])->extract('id')->first();
            $mainFingerPrintId = $this->Sockets->find()->where(['user_id' => $mainUserId])->extract('fingerPrint')->first();
            $haveRef = true;
        } else {
            $mainFingerPrintId = 0;
        }
        $this->findingClients[$data->fingerprint]['hasRef'] = $haveRef;

        $users = $this->UserSessions->find()->where(['user_id !=' => 0])->extract('user_id')->toArray();
        if ($users) {
            $fingerPrintIds = $this->Sockets->find()->where(['user_id IN' => $users])->group('user_id')->extract('fingerPrint')->toArray();
        } else {
            $fingerPrintIds = [];
        }

        if ($fingerPrintIds) {
            foreach ($this->agencies as $key => $client) {
                if (in_array($key, $fingerPrintIds)) {
                    $client['connection']->send(json_encode([
                        'event' => 'sendagency',
                        'fingerPrint' => $data->fingerprint,
                        'timestamp' => $timestamp,
                        'priority' => ($key == $mainFingerPrintId) ? true : false,
                        'haveRef' => $haveRef
                    ]));
                }
            }
        }

//        $this->sendMessageToAll(['event' => 'reset']);
    }

    private function eventfindv2(ConnectionInterface $from, $data)
    {
        if ($data->ref_code) {
            $ref = $data->ref_code;
        } else {
            $ref = '';
        }
        $timestamp = time();

        $this->findingClients[$data->fingerprint]['connection'] = $from;
        $this->findingClients[$data->fingerprint]['ref'] = $data->ref_code;
        $this->findingClients[$data->fingerprint]['hasRef'] = ($data->ref_code) ? true : false;
        $this->findingClients[$data->fingerprint]['timestamp'] = $timestamp;

        $haveRef = false;
        if ($ref) {
            $mainUserId = $this->Users->find()->where(['ref_code' => trim($ref)])->extract('id')->first();
            $mainFingerPrintId = $this->Sockets->find()->where(['user_id' => $mainUserId])->extract('fingerPrint')->first();
            $haveRef = true;
        } else {
            $mainFingerPrintId = 0;
        }

        $users = $this->UserSessions->find()->where(['user_id !=' => 0])->extract('user_id')->toArray();
        if ($users) {
            $fingerPrintIds = $this->Sockets->find()->where(['user_id IN' => $users])->group('user_id')->extract('fingerPrint')->toArray();
        } else {
            $fingerPrintIds = [];
        }

        if ($fingerPrintIds) {
            foreach ($this->agencies as $key => $client) {
                if (in_array($key, $fingerPrintIds)) {
                    $client['connection']->send(json_encode([
                        'event' => 'sendagency',
                        'fingerPrint' => $data->fingerprint,
                        'timestamp' => $timestamp,
                        'priority' => ($key == $mainFingerPrintId) ? true : false,
                        'haveRef' => $haveRef
                    ]));
                }
            }
        }
    }

    private function eventfindv3(ConnectionInterface $from, $data)
    {
        // find agency
        if (isset($data->ref_code) && !empty($data->ref_code)) {
            $phoneNumnber = $data->ref_code;
            $agency = $this->Users->find()->where(['phone' => $phoneNumnber, 'role_id' => 3])->first();
            if ($agency) {
                if(isset($data->booking_id)){
                    $booking = $this->Bookings->get($data->booking_id, ['contain' => ['BookingRooms', 'BookingLandtours']]);
                    if($booking){
                        $booking = $this->Bookings->patchEntity($booking, ['user_id' => $agency->id, 'sale_id' => $agency->parent_id]);
                        $this->Bookings->save($booking);
                    }
                }

                $clientIds = $this->Clients->find()->where(['user_id' => $agency->id]);
                $listClientId = [];
                foreach ($clientIds as $id) {
                    $listClientId[] = $id->clientId;
                }

                $send_data = [
                    'user_id' => $agency->id,
                    'screen_name' => $agency->screen_name,
                    'avatar' => $agency->avatar,
                    'phone' => $agency->phone,
                    'fbid' => $agency->fbid,
                    'zalo' => $agency->zalo,
                    'agencyFingerprint' => $listClientId,
                    'clientFingerprint' => $data->fingerprint,
                ];
                $from->send(json_encode($send_data, JSON_UNESCAPED_UNICODE));
            } else {
                $from->send(json_encode(['event' => 'notfound'], JSON_UNESCAPED_UNICODE));
            }
        } else {
            if (isset($data->isChange) && $data->isChange == true) {
                $isChange = true;
            } else {
                $isChange = false;
            }
            $userIds = $this->Connections->find()->where(['clientId' => $data->fingerprint])->group(['user_id'])->extract('user_id')->toArray();

            $condition = [
                'user_id !=' => 0,
            ];
            if ($userIds) {
                if ($isChange) {
                    $condition['user_id NOT IN'] = $userIds;
                    $agency = $this->Clients->find()->contain(['Users'])->where($condition)->order('rand()')->first();
                } else {
                    $lastChat = $this->Chats->find()->where(['clientId' => $data->fingerprint])->orderDesc('created')->first();
                    if ($lastChat) {
                        $agency = $this->Clients->find()->contain(['Users'])->where(['user_id' => $lastChat->user_id])->first();
                    }
                }
            } else {
                $condition['Clients.login_expire > '] = date('Y-m-d H:i:s');
                $agency = $this->Clients->find()->contain(['Users'])->where($condition)->order('rand()')->first();
            }

            // find booking
            if(isset($data->booking_id)){
                $booking = $this->Bookings->get($data->booking_id, ['contain' => ['BookingRooms', 'BookingLandtours']]);
            } else {
                $booking = null;
            }


            if ($booking) {
                switch ($booking->type) {
                    case HOTEL:
                        $object = $this->Hotels->get($booking->item_id);
                        $object_type_name = 'Khách sạn';
                        $appenMsg = $object_type_name . ' ' . $object->name . ', mã booking: ' . $booking->code . ', check in ' . date_format($booking->start_date, 'd-m-Y');
                        $appenMsg .= ', check out ' . date_format($booking->end_date, 'd-m-Y');
                        $appenMsg .= PHP_EOL . "Đại lý vào phần Quản lý Booking xử lý đơn hàng cho khách lẻ.";
                        break;
                    case HOMESTAY:
                        $object = $this->HomeStays->get($booking->item_id);
                        $object_type_name = 'Homestay';
                        $appenMsg = $object_type_name . ' ' . $object->name . ', mã booking: ' . $booking->code . ', check in ' . date_format($booking->start_date, 'd-m-Y');
                        $appenMsg .= ', check out ' . date_format($booking->end_date, 'd-m-Y');
                        $appenMsg .= PHP_EOL . "Đại lý vào phần Quản lý Booking xử lý đơn hàng cho khách lẻ.";
                        break;
                    case LANDTOUR:
                        $object = $this->LandTours->get($booking->item_id);
                        $object_type_name = 'Land Tour';
                        $appenMsg = $object_type_name . ' ' . $object->name . ', mã booking: ' . $booking->code . ', đi ngày ' . date_format($booking->start_date, 'd-m-Y');
                        $appenMsg .= PHP_EOL . "Đại lý vào phần Quản lý Booking xử lý đơn hàng cho khách lẻ.";
                        break;
                    case VOUCHER:
                        $object = $this->Vouchers->get($booking->item_id);
                        $hotel = $this->Hotels->get($object->hotel_id);
                        $object_type_name = 'Voucher';
                        $appenMsg = $object_type_name . ' ' . $object->name . ' tại khách sạn ' . $hotel->name . ', mã booking: ' . $booking->code . ', check in ' . date_format($booking->start_date, 'd-m-Y');
                        $appenMsg .= ', check out ' . date_format($booking->end_date, 'd-m-Y');
                        $appenMsg .= PHP_EOL . "Đại lý vào phần Quản lý Booking xử lý đơn hàng cho khách lẻ.";
                        break;
                }
            }

            // send response
            $client = $this->Clients->find()->where(['clientId' => $data->fingerprint])->first();

            if ($agency) {
                // create new connection
                $connection = $this->Connections->find()->where(['clientId' => $data->fingerprint, 'user_id' => $agency->user_id])->first();
                if (!$connection) {
                    $connection = $this->Connections->newEntity();
                }
                $connection = $this->Connections->patchEntity($connection, ['clientId' => $data->fingerprint, 'user_id' => $agency->user_id]);
                $this->Connections->save($connection);

                // delete old Agency chat
                $oldAgencyIds = $this->Chats->find()->where(['clientId' => $data->fingerprint])->group(['user_id'])->extract('user_id')->toArray();
                if ($oldAgencyIds && $isChange) {
                    $this->Chats->deleteAll(['clientId' => $data->fingerprint, 'user_id IN' => $oldAgencyIds]);
                }

                // send chat to new agency
                if (isset($object) && isset($object_type_name)) {
                    $msg = 'Chào bạn HDV ' . $agency->user->screen_name . '. Tôi muốn đặt booking: ' . $appenMsg;
                } else {
                    $msg = 'Chào bạn HDV ' . $agency->user->screen_name;
                }
                $chatData = [
                    'sessionId' => time(),
                    'clientId' => $data->fingerprint,
                    'user_id' => $agency->user_id,
                    'type' => CHAT_CLIENT_TO_AGENCY,
                    'msg' => $msg,
                    'img' => '',
                ];
                $chat = $this->Chats->newEntity();
                $chat = $this->Chats->patchEntity($chat, $chatData);
                $this->Chats->save($chat);

                // Save booking to Agency if have booking
                if($booking){
                    $booking = $this->Bookings->patchEntity($booking, ['user_id' => $agency->user->id, 'sale_id' => $agency->user->parent_id]);
                    $this->Bookings->save($booking);
                }

                $agencies = $this->Clients->find()->where(['user_id' => $agency->user_id])->toArray();
                foreach ($agencies as $mAgency) {
                    if (isset($this->agencies[$mAgency->clientId])) {
                        $sendTo = $this->agencies[$mAgency->clientId]['connection'];
                        if (isset($sendTo)) {
                            $data_received = [
                                'event' => 'received',
                                'messageId' => $chat->id,
                                'msg' => $chat->msg,
                                'sentClientId' => $data->fingerprint,
                                'clientFingerprint' => $data->fingerprint,
                                'agencyFingerprint' => $mAgency->clientId,
                                'sentName' => $client->name,
                                'user_id' => $chat->user_id
                            ];
                            $sendTo->send(json_encode($data_received, JSON_UNESCAPED_UNICODE));
                        }
                    } else {
                        $postData['to'] = $mAgency->expo_push_token;
                        $postData['title'] = $client->name;
                        $postData['body'] = $chat->msg;
                        $postData['data'] = $chat;
                        $this->pendingPushNoti[] = $postData;
                    }
                }

                $send_data = [
                    'event' => 'found',
                    'user_id' => $agency->user->id,
                    'screen_name' => $agency->user->screen_name,
                    'avatar' => $agency->user->avatar,
                    'fbid' => $agency->user->fbid,
                    'zalo' => $agency->user->zalo,
                    'agencyFingerprint' => $agency->clientId,
                    'clientFingerprint' => $data->fingerprint,
                ];
                $from->send(json_encode($send_data, JSON_UNESCAPED_UNICODE));

            } else {
                $from->send(json_encode(['event' => 'notfound'], JSON_UNESCAPED_UNICODE));
            }
        }

    }


    private function eventcancelfind(ConnectionInterface $from, $data)
    {
        unset($this->findingClients[$data->fingerprint]);
    }

    private function eventjoin(ConnectionInterface $from, $data)
    {
        $exist = $this->Chats->find()->contain(['Users'])->where(['user_id' => $data->user_id, 'clientId' => $data->clientFingerprint])->first();
        $hasHistory = false;
        if ($exist) {
            $hasHistory = true;
            $avatar = $exist->user->avatar;
        } else {
            $avatar = '';
        }
        if (isset($data->type)) {
            $type = $data->type;
        } else {
            $type = '';
        }
        switch ($type) {
            case CHAT_AGENCY_TO_CLIENT:
                $client = $this->Clients->find()->where(['clientId' => $data->clientFingerprint])->first();
                $mobile = $client->phone;
                break;
            case CHAT_CLIENT_TO_AGENCY:
                $user = $this->Users->get($data->user_id);
                $mobile = $user->phone;
                break;
            default:
                $mobile = '';
                break;
        }
        $from->send(json_encode(['event' => 'joined', 'hasHistory' => $hasHistory, 'agencyAvatar' => $avatar, 'mobile' => $mobile]));
    }

    private function eventmessage(ConnectionInterface $from, $data)
    {
        $exist = $this->Chats->find()->where(['clientId' => $data->clientFingerprint, 'user_id' => $data->user_id])->first();
        if ($exist) {
            $sessionId = $exist->sessionId;
        } else {
            $sessionId = time();
        }
        $chatData = [
            'sessionId' => $sessionId,
            'clientId' => $data->clientFingerprint,
            'user_id' => $data->user_id,
            'type' => $data->type,
            'msg' => $data->msg,
            'img' => $data->img,
        ];
        $chat = $this->Chats->newEntity();
        $chat = $this->Chats->patchEntity($chat, $chatData);
        $this->Chats->save($chat);
        $postData = [];
        $listSendTo = [];
        switch ($data->type) {
            case CHAT_AGENCY_TO_CLIENT:
                $sentClient = $this->Clients->find()->contain(['Users'])->where(['clientId' => $data->agencyFingerprint])->first();
                if (isset($this->clients[$data->clientFingerprint]['connection'])) {
                    $sendToItem = [
                        'from' => $this->clients[$data->clientFingerprint]['connection'],
                        'clientFingerprint' => $data->clientFingerprint,
                        'agencyFingerprint' => $data->agencyFingerprint
                    ];
                    $listSendTo[] = $sendToItem;
                    $sentClientId = $data->agencyFingerprint;

                    $sentName = $sentClient->user->screen_name;
                } else {
                    //prepare for push notification
                    $receiveClient = $this->Clients->find()->where(['clientId' => $data->clientFingerprint])->first();
                    $postData['to'] = $receiveClient->expo_push_token;
                    $postData['title'] = $sentClient->user->screen_name;;
                    $postData['body'] = $chat->msg;
                    $postData['data'] = $chat;
                    $this->pendingPushNoti[] = $postData;
                }
                break;
            case CHAT_CLIENT_TO_AGENCY:
                $agencies = $this->Clients->find()->contain(['Users'])->where(['user_id' => $data->user_id]);
                $sentClientId = $data->clientFingerprint;
                $sentClient = $this->Clients->find()->where(['clientId' => $data->clientFingerprint])->first();
                $sentName = $sentClient->name;
                foreach ($agencies as $agency) {
                    if (isset($this->agencies[$agency->clientId]['connection'])) {
                        $sendToItem = [
                            'from' => $this->agencies[$agency->clientId]['connection'],
                            'clientFingerprint' => $data->clientFingerprint,
                            'agencyFingerprint' => $agency->clientId
                        ];
                        $listSendTo[] = $sendToItem;
                    } else {
                        $tmpPost = [
                            'to' => $agency->expo_push_token,
                            'title' => $sentName,
                            'body' => $chat->msg,
                            'data' => $chat
                        ];
                        $this->pendingPushNoti[] = $tmpPost;
                    }
                }
                break;
        }

        $from->send(json_encode(['event' => 'sent']));
        if (isset($listSendTo)) {
            foreach ($listSendTo as $sendTo) {
                $data_received = [
                    'event' => 'received',
                    'messageId' => $chat->id,
                    'msg' => $data->msg,
                    'img' => $data->img,
                    'sentClientId' => $sentClientId,
                    'clientFingerprint' => $sendTo['clientFingerprint'],
                    'agencyFingerprint' => $sendTo['agencyFingerprint'],
                    'sentName' => $sentName,
                    'user_id' => $data->user_id
                ];
                var_dump(json_encode($data_received, JSON_UNESCAPED_UNICODE));
                $sendTo['from']->send(json_encode($data_received, JSON_UNESCAPED_UNICODE));
            }

        } else {
//            $this->_pushNotification($postData);
        }
    }

    private function eventgetmessage(ConnectionInterface $from, $data)
    {
        $clientId = $data->clientFingerprint;
        $user_id = $data->user_id;
        $limit = $data->limit;
        $offset = ($data->page - 1) * $limit;

        $chats = $this->Chats->find()->where(['clientId' => $clientId, 'user_id' => $user_id])
            ->offset($offset)->limit($limit)->order(['created' => 'DESC'])->toArray();
        $data = array_reverse($chats);

        $from->send(json_encode(['event' => 'history', 'data' => $data]));
    }

    private function eventread(ConnectionInterface $from, $data)
    {
        $chat_id = $data->messageId;
        $chat = $this->Chats->get($chat_id);
        $chat = $this->Chats->patchEntity($chat, ['is_read' => 1]);
        $res = ['event' => 'read', 'isRead' => false];
        if ($this->Chats->save($chat)) {
            $res['isRead'] = true;
        }

        $this->Chats->updateAll(['is_read' => 1], ['clientId' => $chat->clientId, 'user_id' => $chat->user_id, 'created <=' => $chat->created]);

        switch ($chat->type) {
            case CHAT_AGENCY_TO_CLIENT:
                if (isset($this->agencies[$data->agencyFingerprint]['connection'])) {
                    $sendTo = $this->agencies[$data->agencyFingerprint]['connection'];
                }
                break;
            case CHAT_CLIENT_TO_AGENCY:
                if (isset($this->clients[$data->clientFingerprint]['connection'])) {
                    $sendTo = $this->clients[$data->clientFingerprint]['connection'];
                }
                break;
        }

        if (isset($sendTo)) {
            $sendTo->send(json_encode(['event' => 'read', 'messageId' => $chat->id]));
        }
    }

    private function eventgetlistchat(ConnectionInterface $from, $data)
    {
        if ($data->user_id) {
            $condition = ['Chats.user_id' => $data->user_id, 'type' => CHAT_CLIENT_TO_AGENCY];
            $chatOrderIds = $this->Chats->find()->select(['id' => 'MAX(id)'])->where($condition)
                ->group(['clientId'])->extract('id')->toArray();

            if ($chatOrderIds) {
                $chats = $this->Chats->find()->select(['clientId', 'name' => 'Clients.name', 'is_read'])->contain(['Clients'])->where(['Chats.id IN' => $chatOrderIds])->orderAsc('is_read')->toArray();
            } else {
                $chats = [];
            }

            foreach ($chats as $key => $chat) {
                $lastest = $this->Chats->find()->where(['user_id' => $data->user_id, 'clientId' => $chat->clientId])->orderDesc('created')->first();
                $counUnread = $this->Chats->find()->where(['user_id' => $data->user_id, 'clientId' => $chat->clientId, 'is_read' => 0, 'type' => CHAT_CLIENT_TO_AGENCY])->count();
                $chats[$key]->lastest = $lastest;
                $chats[$key]->total_unread = $counUnread;
                $chats[$key]->agencyFingerprint = $data->clientId;
                $chats[$key]->clientFingerprint = $chat->clientId;
            }
        } else {
            $condition = ['Chats.clientId' => $data->clientId];
            $chatOrderIds = $this->Chats->find()->select(['id' => 'MAX(id)'])->where($condition)->group(['user_id'])->extract('id')->toArray();
            if ($chatOrderIds) {
                $chats = $this->Chats->find()->select(['user_id', 'is_read'])
                    ->contain([
                        'Users' => function ($q) {
                            return $q->select(['id', 'screen_name']);
                        },
                        'Users.Clients' => function ($q) {
                            return $q->select(['clientId', 'user_id']);
                        }
                    ])->contain(['Clients'])->where(['Chats.id IN' => $chatOrderIds])->orderAsc('is_read')->toArray();
            } else {
                $chats = [];
            }


            foreach ($chats as $key => $chat) {
                $chats[$key]->clientFingerprint = $data->clientId;
                $agencies = [];
                foreach ($chat->user->clients as $cl) {
                    $agencies[] = $cl->clientId;
                }
                $chats[$key]->agencyFingerprint = $agencies;
                $chats[$key]->name = $chat->user->screen_name;
                unset($chats[$key]->user);
                $lastest = $this->Chats->find()->where(['clientId' => $data->clientId, 'user_id' => $chat->user_id])->orderDesc('created')->first();
                $counUnread = $this->Chats->find()->where(['clientId' => $data->clientId, 'user_id' => $chat->user_id, 'is_read' => 0, 'type' => CHAT_AGENCY_TO_CLIENT])->count();
                $chats[$key]->lastest = $lastest;
                $chats[$key]->total_unread = $counUnread;
            }
        }

        $from->send(json_encode(['event' => 'getlistchat', 'chats' => $chats], JSON_UNESCAPED_UNICODE));
    }

    private function eventgetunread(ConnectionInterface $from, $data)
    {
        if ($data->user_id) {
            $condition = ['Chats.user_id' => $data->user_id, 'is_read' => 0, 'type' => CHAT_CLIENT_TO_AGENCY];
            $chats = $this->Chats->find()->select(['clientId', 'total' => 'count(*)', 'name' => 'Clients.name'])
                ->contain(['Clients'])->where($condition)->group(['Chats.clientId']);
        } else {
            $condition = ['Chats.clientId' => $data->clientId, 'is_read' => 0, 'type' => CHAT_AGENCY_TO_CLIENT];
            $chats = $this->Chats->find()->select(['user_id', 'total' => 'count(*)', 'name' => 'Users.screen_name'])
                ->contain(['Users'])->where($condition)->group(['Chats.user_id']);
        }

        $from->send(json_encode(['event' => 'getunread', 'chats' => $chats->toArray()], JSON_UNESCAPED_UNICODE));
    }

    private function eventpong(ConnectionInterface $from, $data)
    {
        var_dump("Received Pong From: " . $from->resourceId);
        $resourceId = $from->resourceId;
        if (isset($this->deviceConnected[$resourceId])) {
            $this->deviceConnected[$resourceId]['ping'] = 0;
        }

    }

    private function _pushNotification($postData)
    {
        $ch = curl_init();
// Set cURL opts
        curl_setopt($ch, CURLOPT_URL, EXPO_API_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_exec($ch);
        curl_close($ch);
    }

    private function _pushPendingNotification()
    {
//        var_dump("Start Push Notification: " . date("H:i:s"));
        foreach ($this->pendingPushNoti as $key => $postData) {
            $this->_pushNotification($postData);
            unset($this->pendingPushNoti[$key]);
        }
//        var_dump("End Push Notification: " . date("H:i:s"));
    }

    private function eventreset(ConnectionInterface $from, $data)
    {
        $this->sendMessageToAll(['event' => 'reset']);
    }

    private function eventpick(ConnectionInterface $from, $data)
    {
        $users = [];
        foreach ($this->clients as $key => $client) {
            if (!$client['is_admin'])
                $users[] = $key;
        }
        $winning_id = $users[rand(0, (count($users) - 1))];
        $winning_avatar = $this->clients[$winning_id]['avatar'];
        foreach ($this->clients as $key => $client) {
            $client['connection']->send(json_encode([
                'event' => 'pick',
                'winner' => ($winning_id == $key ? true : false),
                'winning_avatar' => $winning_avatar,
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $socket = $this->Sockets->find()->where(['resourceId' => $conn->resourceId])->first();
        if ($socket) {
            if (isset($this->clients[$socket->fingerPrint])) {
                unset($this->clients[$socket->fingerPrint]);
            }
            if (isset($this->agencies[$socket->fingerPrint])) {
                unset($this->agencies[$socket->fingerPrint]);
            }
            var_dump(date('Y-m-d H:i:s') . ": " . $socket->fingerPrint . " disconnected");
            $this->Sockets->deleteAll(['resourceId' => $conn->resourceId]);
        }

//        $send_data = [
//            'event' => 'connect',
//            'clients' => $this->clients,
//        ];
//        $this->sendMessageToAll($send_data);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function sendMessageToAll($msg)
    {
        if (is_object($msg) || is_array($msg)) {
            $msg = json_encode($msg);
        }
        foreach ($this->clients as $client) {
            $client['connection']->send($msg);
        }
    }

    private function sendPing()
    {
        foreach ($this->deviceConnected as $key => $client) {
            $pingCount = $this->deviceConnected[$key]['ping'] + 1;
            if ($pingCount >= 4) {
                var_dump($pingCount);
                $client['connection']->close();
                unset($this->deviceConnected[$key]);
//                var_dump('Close Socket ID: ' . $client['connection']->resourceId);
            } else {
                $ping = ['event' => 'ping'];
                $client['connection']->send(json_encode($ping));
                $this->deviceConnected[$key]['ping'] = $pingCount;
//                var_dump('Send Ping to Socket ID: ' . $client['connection']->resourceId);
            }
        }

        // Send to Client
//        foreach ($this->clients as $key => $client) {
//            $pingCount = $this->clients[$key]['ping'] + 1;
//            if ($pingCount >= 4) {
//                $client['connection']->close();
//                unset($this->clients[$key]);
//                var_dump('Close Client Socket ID: ' . $client['connection']->resourceId);
//            } else {
//                $ping = ['event' => 'ping', 'clientId' => $key];
//                $client['connection']->send(json_encode($ping));
//                $this->clients[$key] = $pingCount;
//                var_dump('Send Ping to Client Socket ID: ' . $client['connection']->resourceId);
//            }
//        }

        // Send to Agency
//        foreach ($this->agencies as $key => $agency) {
//            $pingCount = $this->agencies[$key]['ping'] + 1;
//            if ($pingCount >= 4) {
//                $agency->close();
//                unset($this->agencies[$key]);
//                var_dump('Close Agency Socket ID: ' . $client['connection']->resourceId);
//            } else {
//                $ping = ['event' => 'ping', 'clientId' => $key];
//                $agency['connection']->send(json_encode($ping));
//                $this->agencies[$key] = $pingCount;
//                var_dump('Send Ping to Agency Socket ID: ' . $client['connection']->resourceId);
//            }
//        }
    }

}
