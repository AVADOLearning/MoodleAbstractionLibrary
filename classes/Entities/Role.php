<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Role extends BaseModel
{
    protected $table = 'role';

    public function capabilities()
    {
        return $this->hasMany(RoleCapability::class, 'roleid', 'id');
    }

    public function enrol()
    {
        return $this->belongsTo(Enrol::class, 'roleid', 'id');
    }
}
