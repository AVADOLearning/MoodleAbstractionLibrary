<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Course extends BaseModel
{
    protected $table = 'course';

    public function category()
    {
        return $this->hasOne(CourseCategory::class, 'id', 'category');
    }
}
