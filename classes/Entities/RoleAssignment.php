<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class RoleAssignment extends BaseModel
{
    protected $table = 'role_assignments';

    public function roles()
    {
        return $this->belongsTo(Role::class, 'roleid', 'id');
    }
}
