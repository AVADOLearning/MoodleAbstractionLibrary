<?php

namespace Avado\MoodleAbstractionLibrary\Globals;

/**
 * This is an empty class to allow the moodle user global to be injected as a dependency
 *
 * Class User
 * @package Avado\MoodleAbstractionLibrary\Globals
 */
class MoodleUser
{
    /**
     * @var \moodle_database
     */
    protected $db;

    /**
     * User constructor.
     *
     * @param \moodle_database $db
     */
    public function __construct(\moodle_database $db)
    {
        $this->db = $db;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        global $USER;

        return $USER->$name($arguments);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        global $USER;

        return $USER->$name;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        global $USER;

        $USER->$name = $value;
    }

    /**
     * @param array $userIds
     * @return array
     */
    public function getUsers(array $userIds): array
    {
        return $this->db->get_records('user', ['id' => $userIds]);
    }

    /**
     * @param int $userId
     * @return \stdClass
     */
    public function getUser(int $userId): \stdClass
    {
        return $this->db->get_record('user', ['id' => $userId]);
    }

    /**
     * @return mixed|object
     */
    public function getActiveUser()
    {
        global $USER;

        return $USER;
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isLoggedIn(int $userId): bool
    {
        return $userId === $this->getActiveUser()->id;
    }
}
