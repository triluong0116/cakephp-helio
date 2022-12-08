<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 4/10/2019
 * Time: 6:32 PM
 */

namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Tokens Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 *
 */
class TokensController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['gen_token', 'check_token']);
    }


    public function gen_token()
    {
        $this->loadModel('Clients');
        if ($this->request->is("post")) {
            $data = $this->getRequest()->getData();
            if ( empty($data['expo_push_token']) || !isset($data['expo_push_token']) ){
                $data['expo_push_token'] = '';
            }
            if ($data['code'] == md5(API_NAME . $data['clientId'] . API_NAME_V2)) {
                $client = $this->Clients->find()->contain(['Users'])->where(['clientId' => $data['clientId']])->first();

                if (!$client) {
                    $client = $this->Clients->newEntity();
                    $clientName = 'Khách-' . substr($data['clientId'], 0, 5) . substr($data['clientId'], -5);;
                } else {
                    $clientName = $client->name;
                }
                if (!$clientName) {
                    $clientName = 'Khách-' . substr($data['clientId'], 0, 5) . substr($data['clientId'], -5);;
                }

                $client = $this->Clients->patchEntity($client, ['clientId' => $data['clientId'], 'expo_push_token' => $data['expo_push_token'], 'name' => $clientName]);
                $this->Clients->save($client);
                if ($client->user_id) {
                    $name = $client->user->screen_name;
                    $isAgency = true;
                } else {
                    $name = $client->name;
                    $isAgency = false;
                }

                $this->set([
                    'status' => STT_SUCCESS,
                    'data' => [
                        'token' => JWT::encode(['sub' => API_GEN_CODE, 'exp' => time() + 31536000], Security::getSalt()),
                        'clientName' => $name,
                        'isAgency' => $isAgency,
                        'client_info' => [
                            'name' => $client->name,
                            'phone' => $client->phone,
                            'email' => $client->email
                        ]
                    ],
                    '_serialize' => ['status', 'data']
                ]);
            } else {
                $this->set([
                    'status' => STT_ERROR,
                    'message' => 'Data not valid',
                    'data' => [],
                    '_serialize' => ['status', 'message', 'data']
                ]);
            }

        } else {
            $this->set([
                'status' => STT_ERROR,
                'message' => 'Method not allowed',
                'data' => [],
                '_serialize' => ['status', 'message', 'data']
            ]);
        }

    }

    public function check_token()
    {
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Thành công',
            '_serialize' => ['status', 'message']
        ]);
    }
}
