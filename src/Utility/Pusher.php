<?php
namespace App\Utility;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Ratchet\Wamp\Topic;
use Cake\ORM\TableRegistry;
 
class Pusher implements WampServerInterface {
     
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();
    protected $clients;
    private $subscriptions;
    private $users;
    
    public function __construct() {
        $this->clients = [];
    }
     
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }
     
    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry) {
        var_dump($entry);
        $entryData = json_decode($entry, true);
        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['category'], $this->subscribedTopics)) {
            return;
        }
     
        $topic = $this->subscribedTopics[$entryData['category']];
     
        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }
     
    /* The rest of our methods were as they were, omitted from docs to save space */
     
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
     
    }
     
    public function onOpen(ConnectionInterface $conn) {    
        echo "New connection! ({$conn->resourceId})\n";
        $this->clients[$conn->resourceId] = [
            'connection' => $conn,
        ];
        var_dump(count($this->clients));
    }
     
    public function onClose(ConnectionInterface $conn) {        
        echo "Connection Closed ({$conn->resourceId})\n";
        unset($this->clients[$conn->resourceId]);
        var_dump(count($this->clients));
    }
     
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
     
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
     
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}