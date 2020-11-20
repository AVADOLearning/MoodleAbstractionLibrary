<?php

namespace Avado\MoodleAbstractionLibrary\Traits;

use Avado\MoodleAbstractionLibrary\Entities\ACL\User;
use \Firebase\JWT\JWT;

/**
 * Trait ChecksUserIsPrivileged
 *
 * @package Avado\MoodleAbstractionLibrary\Traits
 */
trait ChecksUserIsPrivileged
{
    /**
     * @var string
     */
    public static $JWT_KEY = '';
   
    /**
     * @var string
     */
    public static $ALGORITHM = 'HS256';

    /**
     * @param Request $request
     * @return bool
     */
    protected function isStaff($request) : bool
    {
        $accessToken = $request->headers->get('accesstoken', false);
        if ($accessToken) {
            $token = JWT::decode($accessToken, self::$JWT_KEY, [self::$ALGORITHM]);
            $activeUser = User::find($token->user->id);
            if ($activeUser) {
                return $activeUser->hasRole('staff');
            }
        }

        return false;
    }
}
