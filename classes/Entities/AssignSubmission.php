<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class AssignSubmission extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'assign_submission';

    /**
     * Return User model who owns the submission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
