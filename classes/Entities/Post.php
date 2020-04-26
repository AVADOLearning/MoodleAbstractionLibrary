<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class Post extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'post';

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
