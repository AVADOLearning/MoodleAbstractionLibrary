<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class File
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class File extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'files';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function context()
    {
        return $this->belongsTo(Context::class, 'contextid', 'id');
    }
}