<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class AssignOverrides extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'assign_overrides';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'groupid', 'id');
    }
}
