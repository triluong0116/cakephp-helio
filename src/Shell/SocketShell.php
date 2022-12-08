<?php

namespace App\Shell;

use Cake\Console\Shell;

require_once(ROOT . DS . "vendor" . DS . "autoload.php");

use Ratchet;
use React;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;

/**
 * Simple console wrapper around Psy\Shell.
 * @property \App\Model\Table\QuestionsTable $Questions
 * @property \App\Model\Table\TopicsTable $Topics
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\BlogsTable $Blogs
 * @property \App\Model\Table\BlogVotesTable $BlogVotes
 * @property \App\Model\Table\BlogCommentsTable $BlogComments
 * @property \App\Model\Table\BlogCommentVotesTable $BlogCommentVotes
 * @property \App\Model\Table\AnswersTable $Answers
 * @property \App\Model\Table\AnswerVotesTable $AnswerVotes
 * @property \App\Model\Table\AnswerCommentsTable $AnswerComments
 * @property \App\Model\Table\AnswerCommentVotesTable $AnswerCommentVotes
 * @property \App\Model\Table\QuestionCommentsTable $QuestionComments
 * @property \App\Model\Table\QuestionCommentVotesTable $QuestionCommentVotes
 *
 */
class SocketShell extends Shell
{

    public function pushSocket()
    {
        $loop = React\EventLoop\Factory::create();
        $pusher = new \App\Utility\Pusher();

        // Listen for the web server to make a ZeroMQ push after an ajax request
        $context = new React\ZMQ\Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array($pusher, 'onBlogEntry'));

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new React\Socket\Server('0.0.0.0:8888', $loop); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new Ratchet\Server\IoServer(
            new Ratchet\Http\HttpServer(
                new Ratchet\WebSocket\WsServer(
                    new Ratchet\Wamp\WampServer(
                        $pusher
                    )
                )
            ), $webSock
        );

        $loop->run();
    }

    public function messageSocket()
    {
        try {
            $server = Ratchet\Server\IoServer::factory(new Ratchet\Http\HttpServer(new Ratchet\WebSocket\WsServer(new \App\Utility\Message())), 8080);

            $server->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function loopSocket()
    {
        $loop = Factory::create();
        $pusher = new \App\Utility\Message($loop);

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new Server('0.0.0.0:8080', $loop);
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    $pusher
                )
            ),
            $webSock
        );

        $loop->run();
    }

    public function sslSocket()
    {
        try {
            $loop = React\EventLoop\Factory::create();
            $webSock = new React\Socket\TcpServer('0.0.0.0:9443', $loop);
            $webSock = new React\Socket\SecureServer($webSock, $loop, [
                'local_cert' => '/etc/letsencrypt/live/mustgo.vn-0001/cert.pem', // path to your cert
                'local_pk' => '/etc/letsencrypt/live/mustgo.vn-0001/privkey.pem', // path to your server private key
                'allow_self_signed' => TRUE, // Allow self signed certs (should be false in production)
                'verify_peer' => FALSE
            ]);

            $webServer = new Ratchet\Server\IoServer(new Ratchet\Http\HttpServer(new Ratchet\WebSocket\WsServer(new \App\Utility\Message($loop))), $webSock, $loop);

            $webServer->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function chatSocket()
    {
        try {
            $server = Ratchet\Server\IoServer::factory(new Ratchet\Http\HttpServer(new Ratchet\WebSocket\WsServer(new \App\Utility\Chat())), 9696);

            $server->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
