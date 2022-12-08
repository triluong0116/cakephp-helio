<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Facebook;
use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 *  @property \App\Model\Table\ChatsTable $Chats
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authError' => false,
            'authorize' => ['Controller']
        ]);

        $this->loadComponent('Paginator');
        $this->loadComponent('Upload');
        $this->loadComponent('Util');
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    public function beforeFilter(Event $event)
    {
        $this->loadModel('Configs');
        $this->loadModel('Users');
        $this->loadModel('Chats');

        if ($this->request->getParam('prefix')) {
            $this->viewBuilder()->layout('backend');
            $prefix = $this->request->getParam('prefix');
            $this->Auth->config('loginAction', ['controller' => 'Users', 'action' => 'login', 'prefix' => $prefix]);
            $this->Auth->config('loginRedirect', ['controller' => 'Dashboards', 'action' => 'profitReport', 'prefix' => $prefix]);
            if ($prefix == 'sale' || $prefix == 'editor' || $prefix == 'sale_landtour' || $prefix == 'manager') {
                $this->Auth->config('loginRedirect', ['controller' => 'Dashboards', 'action' => 'index', 'prefix' => $prefix]);
            }
            if ($prefix == 'accountant') {
                $this->Auth->config('loginRedirect', ['controller' => 'Dashboards', 'action' => 'profitReport', 'prefix' => $prefix]);
            }
            $this->Auth->config('logoutRedirect', ['controller' => 'Users', 'action' => 'login', 'prefix' => $prefix]);
        } else {
            $this->Auth->allow();
            $controller = $this->request->getParam('controller');
            $action = $this->request->getParam('action');
            if ($controller == "Hotels" && $action == "commit") {
                $this->viewBuilder()->layout('commit');
            }

        }

        $refAgencyCode = "";
        if ($this->request->getQuery('ref')) {
            $refAgencyCode = $this->request->getQuery('ref');
            $this->request->getSession()->write('refAgencyCode', $refAgencyCode);

        }
        $config = $this->Configs->find()->where(['type' => "anh-background"])->first();
        $isUserLogin = false;
        if ($this->Auth->user()) {
            $isUserLogin = true;
        }
        $fbGlobal = new Facebook\Facebook([
            'app_id' => FB_APP_ID, // Replace {app-id} with your app id
            'app_secret' => FB_APP_SECRET,
            'default_graph_version' => 'v600.9',
        ]);
        $captionIconLists = [
            'fas fa-check' => '&#xf00c; Tick',
            'fas fa-bus' => '&#xf207; Xe Bus',
            'fas fa-plane' => '&#xf072; Máy bay',
            'fas fa-tram' => '&#xf7da; Cáp treo',
            'fas fa-bed' => '&#xf236; Giường ngủ',
            'fas fa-swimmer' => '&#xf5c4; Bể bơi',
            'far fa-clock' => '&#xf017; Đồng hồ',
            'fas fa-glass-martini' => '&#xf000; Tiệc',
            'fas fa-car-alt' => '&#xf5de; Thuê Ô tô',
            'fas fa-motorcycle' => '&#xf21c; Thuê Xe máy',
            'fas fa-train' => '&#xf238; Tàu hỏa',
            'fas fa-user-friends' => '&#xf500; Bạn bè'
        ];
        $testUrl = 'https://premium-api.product.cloudhms.io';
        $onepayUrl = 'https://onepay.vn/paygate/vpcpay.op';

        $listMessage = [];
        $chatRoomId = null;
        $userId = null;
        $saleAdmin = null;
        if ($this->Auth->user() && $this->Auth->user('role_id') == 3) {
            $user = $this->Users->get($this->Auth->user('id'));
            if ($user) {
                $userId = $user->id;

                $saleAdmin = $this->Users->get($user->parent_id);
////                $firestore = new FirestoreClient([
////                    'projectId' => 'mustgoproj',
////                ]);
                $chatRoomId = $user->id . '-' . $saleAdmin->id;
                $listMessage = [];
                $listMessage = $this->Chats->find()->where(['chat_room_id' => $chatRoomId])->toArray();
//                try {
//                    if ($firestore->collection('chatroom')->document($user->id . '-' . $user->parent_id)->snapshot()->exists()) {
//                        $listDocuments 2= $firestore->collection('chatroom')->document($user->id . '-' . $user->parent_id)->collection('messages')->documents();
//                        foreach ($listDocuments as $document) {
//                            $listMessage[] = $document->data();
//                        }
//                    }
//                } catch (Exception $exception) {
//
//                } catch (ServiceException $serviceException) {
//
//                }
            }
        }

        $this->set(compact('isUserLogin', 'fbGlobal', 'refAgencyCode', 'captionIconLists', 'config', 'testUrl', 'onepayUrl', 'saleAdmin', 'listMessage', 'chatRoomId', 'userId'));
    }

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role_id']) && $user['role_id'] === 1) {
            return true;
        }

        // Default deny
        return false;
    }

}
