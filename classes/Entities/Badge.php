<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class Badge
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Badge extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'badge';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
