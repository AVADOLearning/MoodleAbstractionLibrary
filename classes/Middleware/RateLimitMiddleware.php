<?php

namespace Avado\MoodleAbstractionLibrary\Middleware;

use Avado\AlpApi\Auth\Controllers\AuthController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;
use RateLimit\Exception\LimitExceeded;
use RateLimit\Rate;
use RateLimit\RedisRateLimiter;
use Redis;

class RateLimitMiddleware
{
    /**
     * @param Redis $client
     */
    public function __construct(Redis $client)
    {
        $this->client = $client;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function handle(Request $request, $httpKernel)
    {
        if($this->isAuthRequest($request)){
            return true;
        }

        if(!$token = $request->headers->get('accesstoken')){
            throw new AccessDeniedHttpException("You have provided an invalid token.");
        }
        $rateLimiter = new RedisRateLimiter($this->client);

        $rateLimiter->limit($token, Rate::perMinute(300));

        return true;
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
