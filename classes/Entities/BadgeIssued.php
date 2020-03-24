<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class BadgeIssued
 *
 * @package local\leaderboards\Entities
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
}
