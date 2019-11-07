<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use local_cohortmanagement\Entities\CourseCohortSync;

class Cohort extends BaseModel
{
    protected $table = 'cohort';
  
    public function members()
    {
        return $this->hasManyThrough(User::class, CohortMember::class, 'cohortid', 'id', 'id', 'userid');
    }

    /**
     * @return mixed
     */
    public function courseCohortSyncs()
    {
        return $this->hasMany(CourseCohortSync::class,'cohortid','id');
    }
}
