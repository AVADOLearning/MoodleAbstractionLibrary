<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use local_cohortmanagement\Entities\CourseCohortSync;

/**
 * Class Group
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Group extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'groups';

    /**
     * @return mixed
     */
    public function courseCohortSyncs()
    {
        return $this->hasMany(CourseCohortSync::class,'groupid','id');
    }
}
