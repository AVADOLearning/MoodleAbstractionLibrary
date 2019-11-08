<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use moodle_url;

/**
 * Class UserLastaccess
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class UserLastaccess extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user_lastaccess';

    /**
     * @var array
     */
    protected $appends = ['course_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'courseid', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    /**
     * @return moodle_url
     * @throws \moodle_exception
     */
    public function getCourseUrlAttribute()
    {
        $courseUrl = new moodle_url('/course/view.php', ['id' => $this->courseid]);
        return $courseUrl->raw_out();
    }
}


