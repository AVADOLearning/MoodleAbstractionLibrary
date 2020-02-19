<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class AssignUserMapping extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'assign_user_mapping';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
