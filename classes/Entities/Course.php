<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Course extends BaseModel
{
    protected $table = 'course';

    public function courseModules()
    {
        return $this->hasMany(CourseModule::class, 'course', 'id');
    }
}
