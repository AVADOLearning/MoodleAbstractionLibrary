<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use local\learner_relationships\Entities\LearnerRelationship;
use Avado\MoodleAbstractionLibrary\Entities\ACL\Role as ACLRole;
use Avado\MoodleAbstractionLibrary\Entities\ACL\UserRole;

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

    public function aclRoles()
    {
        return $this->hasManyThrough(ACLRole::class, UserRole::class, 'user_id','id','id', 'role_id');
    }

    /**
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->whereHas('roles.permissions', function($query) use ($permission){
            $query->where('name', $permission);
        })->exists();
    }
}
