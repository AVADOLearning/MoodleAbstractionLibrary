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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolments(){
        return $this->hasMany(Enrol::class,'courseid','id');
    }


    /**
     * Get all the enrol id for the user enrolled
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function userEnrolment(){
        return $this->hasManyThrough(HelpdeskProduct::class, HelpdeskProductCourse::class, 'coursecategoryid', 'id', 'id', 'id');
    }

}
