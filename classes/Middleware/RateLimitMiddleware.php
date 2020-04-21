<?php

namespace Avado\MoodleAbstractionLibrary\Middleware;

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
        if(!$token = $request->headers->get('token')){
            throw new AccessDeniedHttpException("You have provided an invalid token.");
        }
        $rateLimiter = new RedisRateLimiter($this->client);

        $rateLimiter->limit($token, Rate::perMinute(300));

        return true;
    }
}
