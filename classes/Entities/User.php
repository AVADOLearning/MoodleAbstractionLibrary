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
        return $this->hasMany(LearnerRelationship::class,'learnerid','id');
    }
}
