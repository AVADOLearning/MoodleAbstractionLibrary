<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class CourseModule extends BaseModel
{
    protected $table = 'course_modules';

    public function course()
    {
        return $this->belongsTo(Course::class, 'course', 'id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module', 'id');
    }

    public function completions()
    {
        return $this->hasMany(CourseModuleCompletion::class, 'coursemoduleid', 'id');
    }
}