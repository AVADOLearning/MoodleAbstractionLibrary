<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class Message
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Message extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'message';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'useridfrom', 'id');
    }
}
