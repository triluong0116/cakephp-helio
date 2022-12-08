<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 4/6/2019
 * Time: 3:39 PM
 */

namespace App\Middleware;

use Cake\Routing\Route\RedirectRoute;
use Cake\Routing\Route\Route;
use Firebase\JWT\JWT;

class CheckTokenMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $res = ['status' => 0, 'message' => ''];
        // turn off api
//        return $response->withType("application/json")
//            ->withStringBody(json_encode($res));

        if ($request->getUri()->getPath() == '/api/tokens/gen_token') {
            return $next($request, $response);
        } else {
            $access_token = $request->getHeaderLine('Application-Authorization');
            $version = $request->getHeaderLine('MG-Version');

            try {
                $decode = JWT::decode($access_token, \Cake\Utility\Security::getSalt(), array('HS256'));
                if ($decode->sub == API_GEN_CODE) {
                    $ver_prefix = $this->mappingRouteVersion($version);
                    $cur_prefix = $request->getParam('prefix');
                    if ($ver_prefix != $cur_prefix) {
                        $request = $request->withParam('prefix', $ver_prefix);
                    }
                    return $next($request, $response);
                } else {
                    $res['status'] = STT_INVALID;
                    $res['message'] = 'Token Invalid';
                }

                return $response->withType("application/json")
                    ->withStringBody(json_encode($res));

            } catch (\Exception $e) {
                $exClass = get_class($e);
                if (strpos($exClass, EXPIRE_CLASS) !== false) {
                    $res['status'] = STT_NOT_LOGIN;
                    $res['message'] = 'Token expired';
                }
                if (strpos($exClass, WRONG_SIGN_CLASS) !== false) {
                    $res['status'] = STT_INVALID;
                    $res['message'] = 'Token Invalid';
                }

                return $response->withType("application/json")
                    ->withStringBody(json_encode($res));
            }
        }

    }

    private function mappingRouteVersion($ver) {
        $prefix = 'api';
        switch ($ver) {
            case '6.0.0':
                $prefix = 'api/V600';
                break;
        }
        return $prefix;
    }
}
