<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class CourseModuleCompletion
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class CourseModuleCompletion extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'course_modules_completion';

    /**
     * Get the CourseModule object that this CourseModuleCompletion object belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function completions()
    {
        return $this->belongsTo(CourseModule::class, 'coursemoduleid', 'id');
    }
}
