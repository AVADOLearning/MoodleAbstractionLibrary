<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class CourseModule
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class CourseModule extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'course_modules';

    /**
     * Get the Course object the CourseModule belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course', 'id');
    }

    /**
     * Get the Module object the CourseModule belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module', 'id');
    }

    /**
     * Get the all CourseModuleCompletion objects that belong to this CourseModule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function completions()
    {
        return $this->hasMany(CourseModuleCompletion::class, 'coursemoduleid', 'id');
    }
}
