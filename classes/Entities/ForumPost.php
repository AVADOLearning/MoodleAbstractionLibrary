<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class ForumPost
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class ForumPost extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'forum_posts';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discussion()
    {
        return $this->belongsTo(ForumDiscussion::class, 'discussion', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'userid', 'id');
    }


}