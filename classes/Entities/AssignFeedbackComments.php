<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class AssignFeedbackComments extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'assignfeedback_comments';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade()
    {
        return $this->belongsTo(AssignGrades::class, 'grade', 'id');
    }
}
