<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationComponent
 * @package Avado\AlpApi\Notifications\Entities
 */
class NotificationComponent extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'notification_components';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $guarded = ['id'];
}