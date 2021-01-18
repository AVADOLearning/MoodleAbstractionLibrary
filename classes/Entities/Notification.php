<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Notification
 * @package Avado\AlpApi\Notifications\Entities
 */
class Notification extends BaseModel
{
    use SoftDeletes;

    /**
     * @var array
     */
    const SEARCH_FIELDS = [
        'id',
        'from_user_id',
        'to_user_id',
        'is_read',
        'component_id',
        'event_type_id',
        'contexturl',
        'contexturlname',
        'notification_type_id',
        'should_email'
    ];

    /**
     * @var array
     */
    public const CHILDREN = [
        'sender' => User::class
    ];

    /**
     * @var string
     */
    protected $table = 'notifications';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function component()
    {
        return $this->belongsTo(NotificationComponent::class,'component_id', 'id');
    }

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