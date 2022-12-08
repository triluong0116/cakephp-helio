<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Google\Cloud\Firestore\FirestoreClient;
/**
 * Chat Controller
 *
 *
 * @method \App\Model\Entity\Chat[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ChatsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['getUser']);
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
}
