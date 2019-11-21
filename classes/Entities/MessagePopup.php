<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class MessagePopup extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'message_popup';

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
