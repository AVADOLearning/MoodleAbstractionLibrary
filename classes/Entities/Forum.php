<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class Forum
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Forum extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'forum';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function posts()
    {
        return $this->hasManyThrough(ForumPost::class, ForumDiscussion::class, 'forum', 'id', 'id', 'discussion');
    }
}
