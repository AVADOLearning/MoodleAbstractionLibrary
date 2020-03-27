<?php

namespace Avado\MoodleAbstractionLibrary\Entities\ACL;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;

/**
 * Class Role
 * @package Avado\MoodleAbstractionLibrary\Entities\ACL
 */
class Role extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'acl_roles';

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, RolePermission::class, 'role_id','id','id', 'permission_id');
    }

    public function getPermissionsAttribute()
    {
        return $this->permissions()->get()->pluck('name');
    }
}
