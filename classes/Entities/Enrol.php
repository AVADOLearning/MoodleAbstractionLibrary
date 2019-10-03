<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Enrol extends BaseModel
{
    protected $table = 'enrol';

    public function role()
    {
        return $this->belongsTo(Role::class, 'roleid', 'id');
    }
}
