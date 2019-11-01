<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class ForumDiscussion
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class ForumDiscussion extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'forum_discussion';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'discussion', 'id');
    }
}
