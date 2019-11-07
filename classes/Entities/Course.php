<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use local_cohortmanagement\Entities\CourseCohortSync;

/**
 * Class Course
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Course extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'course';

    /**
     * Get all the CourseModule objects that belond to this Course object
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseModules()
    {
        return $this->hasMany(CourseModule::class, 'course', 'id');
    }

    public function category()
    {
        return $this->hasOne(CourseCategory::class, 'id', 'category');
    }

    /**
     * @return mixed
     */
    public function courseCohortSyncs()
    {
        return $this->hasMany(CourseCohortSync::class,'courseid','id');
    }
}
