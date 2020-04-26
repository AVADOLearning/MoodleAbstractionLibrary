<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class Context
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Context extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'context';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
