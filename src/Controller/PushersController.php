<?php

namespace App\Controller;

use App\Controller\AppController;
require_once(ROOT . DS . "vendor" . DS  . "autoload.php");
use Ratchet;
use React;
use App\Utility\Pusher;

/**
 * Blogs Controller
 *
 * @property \App\Model\Table\BlogsTable $Blogs
 *
 * @method \App\Model\Entity\Blog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PushersController extends AppController {
    
    public function pushToAgency() {
        $this->viewBuilder()->enableAutoLayout(false);
        $res = ['success' => false, 'message' => 'Đây là Message', 'category' => 'kittensCategory'];
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://127.0.0.1:5555");

        $socket->send(json_encode($res));
    }
    
    public function findAgency() {
        $this->viewBuilder()->enableAutoLayout(false);
        $res = ['success' => false, 'message' => 'Đây là Message', 'category' => 'kittensCategory'];
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://127.0.0.1:5555");

        $socket->send(json_encode($res));
    }
    
}
