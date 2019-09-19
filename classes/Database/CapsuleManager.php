<?php

namespace Avado\MoodleAbstractionLibrary\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class CapsuleManager
 *
 * @package Avado\MoodleAbstractionLibrary\Database
 */
class CapsuleManager
{
    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $prefix;

    /**
     * CapsuleManager constructor.
     * @param $driver
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     */
    public function __construct(string $driver, string $host, string $database, string $username, string $password, string $prefix)
    {
        $this->driver = $driver;
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->prefix = $prefix;
    }

    /**
     *
     */
    public function boot()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            "driver" => $this->driver,
            "host" => $this->host,
            "database" => $this->database,
            "username" => $this->username,
            "password" => $this->password,
            'prefix' => $this->prefix
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
