<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use local\learner_relationships\Entities\LearnerRelationship;

class User extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @var array
     */
    protected $guarded = ['password'];

    /**
     * @var array
     */
    protected $hidden = ['password'];

    public function enrolments()
    {
        return $this->hasMany(UserEnrolment::class, 'userid', 'id');
    }

    public function relatedRoles()
    {
        return $this->hasMany(LearnerRelationship::class,'learner_id','id');
    }

    public function roles()
    {
        return $this->hasManyThrough(Role::class, RoleAssignment::class, 'userid', 'id', 'id', 'roleid');
    }

    public function cohortMemberships()
    {
        return $this->hasManyThrough(Cohort::class, CohortMember::class, 'userid', 'id', 'id', 'cohortid');
    }

    /**
     * Get the last access course of user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lastAccessCourse()
    {
        return $this->hasMany(UserLastaccess::class, 'userid', 'id')
            ->orderBy('timeaccess', 'desc')
            ->limit(1);
    }
}
