<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class MessageRead extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'message_read';

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
