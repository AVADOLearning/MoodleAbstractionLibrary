<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class CourseModuleCompletion extends BaseModel
{
    protected $table = 'course_modules_completion';

    public function completions()
    {
        return $this->belongsTo(CourseModule::class, 'coursemoduleid', 'id');
    }
}