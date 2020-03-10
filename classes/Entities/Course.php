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
     * Get all the CourseModule for Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseModules()
    {
        return $this->hasMany(CourseModule::class, 'course', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(CourseCategory::class, 'id', 'category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseCohortSyncs()
    {
        return $this->hasMany(CourseCohortSync::class,'courseid','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function enrolments()
    {
        return $this->hasManyThrough(UserEnrolment::class,Enrol::class, 'courseid', 'enrolid', 'id', 'id');
    }

    /**
     * Get the enrolment type for a course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolmentType()
    {
        return $this->hasMany(Enrol::class,'courseid','id');
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function formatOption()
    {
        return $this->hasMany(CourseFormatOption::class,'courseid','id');
    }
}
