<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationMigrationLog
 * @package Avado\AlpApi\Notifications\Entities
 */
class NotificationMigrationLog extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'notification_migration_log';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $guarded = ['id'];

}
