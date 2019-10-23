<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Cohort extends BaseModel
{
    protected $table = 'cohorts';

    Public function members()
    {
        return $this->hasManyThrough(User::class, CohortMember::class, 'cohortid', 'id', 'id', 'userid');
    }
}
