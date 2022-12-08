<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 3/28/2019
 * Time: 11:54 AM
 */

namespace App\Controller\Api\V600;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'scope' => ['Users.is_active' => 1]
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'userModel' => 'Users',
                    'scope' => ['Users.is_active' => 1],
                    'fields' => [
                        'username' => 'id'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);
        $this->loadComponent('Api');
        $this->loadComponent('Util');

        // process version
//        $request = $this->getRequest();
//        $ver = $request->getHeaderLine('version');
//        if ($ver) {
//            $new_prefix = 'api/v' . str_replace('.', '', $ver);
//            $cur_params = $request->getAttribute('params');
//            if ($new_prefix != $cur_params['prefix']) {
//                if ($cur_params['controller'] != 'Tokens' && $cur_params['action'] != 'gen_token') {
//                    $this->redirect(['controller' => $cur_params['controller'], 'action' => $cur_params['action'], 'prefix' => $new_prefix]);
//                }
//            }
//        }

    }

    public function beforeFilter(Event $event) {
        $testUrl = 'https://premium-api.product.cloudhms.io';
        $onepayUrl = 'https://onepay.vn/paygate/vpcpay.op';
        $this->set(compact('testUrl', 'onepayUrl'));
    }
}
