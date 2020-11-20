<?php

namespace Avado\MoodleAbstractionLibrary\Traits;

use Avado\MoodleAbstractionLibrary\Entities\ACL\User;

/**
 * Trait ChecksCourseEnrolmentPermission
 *
 * @package Avado\MoodleAbstractionLibrary\Policies\Traits
 */
trait ChecksCourseEnrolmentPermission
{
    /**
     * @param User $activeUser
     * @param int $courseId
     * @return bool
     */
    protected function isUserEnrolled(User $activeUser, int $courseId): bool
    {
        return $activeUser->select('id')
            ->whereHas('enrolments', function ($query) use ($activeUser, $courseId) {
                $query->select('id')->whereHas('enrol', function ($q) use ($courseId) {
                    $q->select('id')->where('courseid', $courseId);
                })->where('userid', $activeUser->id);
            })->exists();
    }
}
