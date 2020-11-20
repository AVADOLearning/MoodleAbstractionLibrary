<?php

namespace Avado\MoodleAbstractionLibrary\Middleware;

use Avado\AlpApi\Auth\Controllers\AuthController;
use Firebase\JWT\JWT;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;
use RateLimit\Exception\LimitExceeded;
use RateLimit\Rate;
use RateLimit\RedisRateLimiter;
use Redis;

class RateLimitMiddleware
{
    const RATE_LIMIT = [
      'guest' => 15,
      'user' => 300
    ];

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
        if ($this->isAuthRequest($request)) {
            return true;
        }

        if (!$token = $request->headers->get('accesstoken')) {
            throw new AccessDeniedHttpException("You have provided an invalid token.");
        }
        $rateLimiter = new RedisRateLimiter($this->client);

        $rateLimiter->limit($token, Rate::perMinute($this->getAccessRateLimit($token)));

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

    /**
     * @param string $token
     * @return int|mixed
     */
    protected function getAccessRateLimit(string $token)
    {
        $decodedToken = JWT::decode($token, AuthMiddleware::JWT_KEY, [AuthMiddleware::ALGORITHM]);
        return self::RATE_LIMIT[$decodedToken->visibility];
    }
}
