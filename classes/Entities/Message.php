<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Observers\MessageObserver;

/**
 * Class Message
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Message extends BaseModel
{
    const OBSERVER = MessageObserver::class;

    /**
     * @var string
     */
    protected $table = 'message';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'useridfrom', 'id');
    }
}
