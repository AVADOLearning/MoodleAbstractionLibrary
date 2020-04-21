<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class BadgeIssued
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class BadgeIssued extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'badge_issued';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badgeid', 'id');
    }
}
