<?php

namespace Avado\MoodleAbstractionLibrary\Entities\ACL;

use Avado\AlpApi\LearnerRelationships\Entities\LearnerRelationship;
use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Avado\MoodleAbstractionLibrary\Entities\UserEnrolment;
use Avado\AlpApi\Roles\Entities\Role as MdlRole;
use Avado\AlpApi\Roles\Entities\RoleAssignment as MdlRoleAssignment;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolments()
    {
        return $this->hasMany(UserEnrolment::class, 'userid', 'id');
    }

    public function roles()
    {
        return $this->hasManyThrough(Role::class, UserRole::class, 'user_id', 'id', 'id', 'role_id');
    }

    public function hasRole($role)
    {
        return $this->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->where('id', $this->id)->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function mdlRoles()
    {
        return $this->hasManyThrough(MdlRole::class, MdlRoleAssignment::class, 'userid', 'id', 'id', 'roleid');
    }

    /**
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->whereHas('roles.permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->where('id', $this->id)->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lrLearnerRelationships()
    {
        return $this->hasMany(LearnerRelationship::class, 'relationship_id', 'id');
    }
}
