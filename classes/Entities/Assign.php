<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Assign extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'assign';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignGrades()
    {
        return $this->hasMany(AssignGrades::class, 'assignment', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignOverrides()
    {
        return $this->hasMany(AssignOverrides::class, 'assignment', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignPluginConfig()
    {
        return $this->hasMany(AssignPluginConfig::class, 'assignment', 'io');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignSubmission()
    {
        return $this->hasMany(AssignSubmission::class, 'assignment', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignUserFlags()
    {
        return $this->hasMany(AssignUserFlag::class, 'assignment', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignUserMapping()
    {
        return $this->hasMany(AssignUserMapping::class, 'assignment', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignFeedbackComments()
    {
        return $this->hasMany(AssignFeedbackComments::class, 'assignment', 'id');
    }
}
