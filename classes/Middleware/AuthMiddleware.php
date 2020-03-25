<?php

namespace Avado\MoodleAbstractionLibrary\Middleware;

use Avado\AlpApi\Controllers\AuthController;
use Symfony\Component\HttpFoundation\Request;
use \Firebase\JWT\JWT;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class AuthMiddleware
 * @package Avado\AlpApi\Middleware
 */
class AuthMiddleware
{
    /**
     * @var string
     */
    const JWT_KEY = '8thjv78w3478w34r873';

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

        $token = $request->headers->get('token');

        try {
            JWT::decode($token, self::JWT_KEY, [self::ALGORITHM]);

            return true;
        } catch (\Exception $e){
            throw new AccessDeniedHttpException("You have provided an invalid token.");
        }
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
}
