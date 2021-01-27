<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationSetting
 * @package Avado\AlpApi\Notifications\Entities
 */
class NotificationSetting extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'notification_settings';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventType()
    {
        return $this->belongsTo(NotificationEventType::class,'event_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class,'notification_type_id', 'id');
    }
}
