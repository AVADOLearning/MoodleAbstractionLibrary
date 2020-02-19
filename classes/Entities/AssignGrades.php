<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class AssignGrades extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'assign_grades';

    /**
     * User who's been graded
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignGradesAssignFeedbackComments()
    {
       return $this->hasMany(AssignFeedbackComments::class, 'grade', 'id');
    }
}
