<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationEventType
 * @package Avado\AlpApi\Notifications\Entities
 */
class NotificationEventType extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'notification_event_types';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
