<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class AssignUserFlag extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'assign_user_flags';

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
