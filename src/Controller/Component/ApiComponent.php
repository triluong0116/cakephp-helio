<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\ORM\TableRegistry;

/**
 * Upload component
 */
class ApiComponent extends Component
{

    public function genUserToken($user, $clientId)
    {
        $expire = 432000;
        $token = JWT::encode(['sub' => $clientId, 'exp' => time() + $expire, 'userId' => $user['id'], 'roleId' => $user['role_id'], 'username' => $user['username']], Security::getSalt());
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->setex($user['username'] . '-' . $clientId, $expire, $token);
        return $token;
    }

    public function checkLoginApi()
    {
        $expire = 432000;
        $res = ['status' => false, 'message' => '', 'user_id' => 0, 'role_id' => 0];
        $this->Clients = TableRegistry::get('Clients');
        $this->Users = TableRegistry::get('Users');
        $request = $this->request;
        $header = $request->getHeaderLine('Authorization');
        if ($header && stripos($header, 'bearer') === 0) {
            $access_token = str_ireplace('bearer' . ' ', '', $header);
        } else {
            $access_token = '';
        }
        try {
            if ($access_token) {
                $decode = JWT::decode($access_token, \Cake\Utility\Security::getSalt(), array('HS256'));
                $sub = $decode->sub;
                $username = $decode->username;
                $user_id = $decode->userId;
                $role_id = $decode->roleId;
                $redis = new \Redis();
                $redis->connect('127.0.0.1', 6379);
                $redisKey = $username.'-'.$sub;
                $redisToken = $redis->get($redisKey);
                if ($access_token == $redisToken) {
                    $redis->setex($redisKey, $expire, $redisToken);
                    $res['status'] = true;
                    $res['user_id'] = $user_id;
                    $res['role_id'] = $role_id;
                } else {
                    $res['message'] = 'Your Login session is expire';
                }
            } else {
                $res['message'] = 'You are not logged in';
            }

            return $res;
        } catch (\Exception $e) {
            $exClass = get_class($e);
            $res['message'] = $e->getMessage();
            if (strpos($exClass, EXPIRE_CLASS) !== false) {
                $res['message'] = 'Token expired';
            }
            if (strpos($exClass, WRONG_SIGN_CLASS) !== false) {
                $res['message'] = 'Token Invalid Signature';
            }
            return $res;
        }
    }

    public function getClientId()
    {
        $request = $this->request;
        $access_token = $request->getHeaderLine('Application-Authorization');
        $decode = JWT::decode($access_token, \Cake\Utility\Security::getSalt(), array('HS256'));

        var_dump($decode);
        die;
    }


}
