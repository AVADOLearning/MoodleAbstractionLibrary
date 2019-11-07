<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use local_cohortmanagement\Entities\CourseCohortSync;

/**
 * Class Grouping
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Grouping extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'groupings';

    /**
     * @return mixed
     */
    public function courseCohortSyncs()
    {
        return $this->hasMany(CourseCohortSync::class,'groupingid','id');
    }
}
