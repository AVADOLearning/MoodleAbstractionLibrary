<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Cohort extends BaseModel
{
    protected $table = 'cohort';
  
    public function members()
    {
        return $this->hasManyThrough(User::class, CohortMember::class, 'cohortid', 'id', 'id', 'userid');
    }
}
