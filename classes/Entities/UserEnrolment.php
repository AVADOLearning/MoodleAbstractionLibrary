<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class UserEnrolment extends BaseModel
{
    protected $table = 'user_enrolments';

    public function enrol()
    {
        return $this->belongsTo(Enrol::class, 'enrolid', 'id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'userid', 'id');
    }
}
