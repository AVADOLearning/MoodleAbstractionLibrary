<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class RoleCapability extends BaseModel
{
    protected $table = 'role_capabilities';

    public function role()
    {
        return $this->hasMany(Role::class, 'roleid', 'id');
    }
}
