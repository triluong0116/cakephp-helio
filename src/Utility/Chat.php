<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 3/27/2019
 * Time: 10:41 AM
 */

namespace App\Utility;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Cake\ORM\TableRegistry;

class Chat implements MessageComponentInterface
{
    protected $clients;
    private $activeUsers;
    private $activeConnections;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->activeUsers = [];
        $this->activeConnections = [];
        $this->clientIds = [];
        $this->userIds = [];
        $this->chatSessions = [];

        $this->Sockets = TableRegistry::get('Sockets');
        $this->UserSessions = TableRegistry::get('UserSessions');
        $this->Users = TableRegistry::get('Users');
        $this->Chats = TableRegistry::get('Chats');
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);
        $valid_functions = ['connect', 'message', 'join'];
        if (in_array($data->event, $valid_functions)) {
            $functionName = 'event' . $data->event;
            $this->$functionName($from, $data);
        } else {
            $from->send('INVALID REQUEST');
        }
    }

    private function eventconnect(ConnectionInterface $from, $data)
    {
        $from->send(json_encode(['event' => 'connected']));
    }

    public function sendMessageToOthers($from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {

                $client->send($msg);
            }
        }
    }

    public function sendMessageToAll($msg)
    {
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        unset($this->activeUsers[$conn->resourceId]);
        $onlineUsers = [];
        $onlineUsers['type'] = "onlineUsers";
        $onlineUsers['onlineUsers'] = $this->activeUsers;
        $this->sendMessageToOthers($conn, json_encode($onlineUsers));
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
