<?php

namespace Avado\MoodleAbstractionLibrary\Middleware;

use Avado\AlpApi\Auth\Controllers\AuthController;
use Symfony\Component\HttpFoundation\Request;
use \Firebase\JWT\JWT;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class AuthMiddleware
 * @package Avado\MoodleAbstractionLibrary\Middleware
 */
class AuthMiddleware
{
    /**
     * @var string
     */
    const JWT_KEY = '';

    /**
     * @var string
     */
    const ALGORITHM = 'HS256';

    /**
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request)
    {
        if($this->isAuthRequest($request)){
            return true;
        }
        return $this->tokenIsValid($request->headers->get('accesstoken'), $request->server->get('SERVER_NAME'), 'accesstoken') ||
               $this->tokenIsValid($request->headers->get('refreshtoken'), $request->server->get('SERVER_NAME'), 'refreshtoken');
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isAuthRequest(Request $request)
    {
        $controller = explode('::', $request->attributes->get('_controller'))[0];

        return $controller == AuthController::class;
    }

    /**
     * @param string $token
     * @return void
     */
    protected function tokenIsValid($token, $host, $type)
    {
        try {
            $token = JWT::decode($token, self::JWT_KEY, [self::ALGORITHM]);
    
            if($token->expiry > time() && $token->host == $host && $token->type == $type){
                return true;
            } else {
                throw new AccessDeniedHttpException("You have provided an invalid token.");
            }
        } catch (\Exception $e) {
            throw new AccessDeniedHttpException("You have provided an invalid token.");
        }
    }
}
