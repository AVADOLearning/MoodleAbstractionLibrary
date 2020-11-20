<?php

namespace Avado\MoodleAbstractionLibrary\Globals;

/**
 * This is an empty class to allow the moodle config global to be injected as a dependency
 *
 * Class Config
 * @package Avado\MoodleAbstractionLibrary\Globals
 */

class Config
{
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        global $CFG;

        return $CFG->$name($arguments);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        global $CFG;

        return $CFG->$name;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        global $CFG;

        $CFG->$name = $value;
    }
}
