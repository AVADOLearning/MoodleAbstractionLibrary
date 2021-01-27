<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationType
 * @package Avado\AlpApi\Notifications\Entities
 */
class NotificationType extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'notification_types';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
