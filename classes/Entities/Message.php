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
     * @var array
     */
    protected $dates = ['timecreated'];

    /**
     * @var array
     */
    protected $notificationType = [
        'moodle' => 'Badges',
        'mod_forum' => 'Forum',
        'mod_assign' => 'Assignments',
        'local_eventcalendar' => 'Visits',
    ];

    /**
     * @param $attribute
     * @return string
     */
    public function getComponentAttribute($attribute): string
    {
        if (!$this->notificationType[$attribute]) {
            return $attribute;
        }

        return $this->notificationType[$attribute];
    }

    /**
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeUnreadNotifications(Builder $query, int $userId)
    {
        return $query->where(function ($query) {
            $query->where(['component' => 'moodle', 'eventtype' => 'badgerecipientnotice'])
                ->orWhere('component', '!=', 'moodle');
        })->where(['notification' => 1, 'useridto' => $userId]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'useridfrom', 'id');
    }
}
