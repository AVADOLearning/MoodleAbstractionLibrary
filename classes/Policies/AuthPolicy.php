<?php

namespace Avado\MoodleAbstractionLibrary\Policies;

use Avado\MoodleAbstractionLibrary\Entities\User;

/**
 * Class AuthPolicy
 *
 * @package Avado\MoodleAbstractionLibrary\Policies
 */
class AuthPolicy
{
    /**
     * @param User $requestedUser
     * @param User $activeUser
     * @return bool
     */
    public function auth(User $requestedRecord, User $activeUser)
    {
        return true;
    }
}
