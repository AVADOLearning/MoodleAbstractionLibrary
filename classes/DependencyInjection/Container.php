<?php

namespace Avado\MoodleAbstractionLibrary\DependencyInjection;

use Avado\MoodleAbstractionLibrary\Database\CapsuleManager;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Redis;

/**
 * Class Container
 * @package Avado\MoodleAbstractionLibrary\DependencyInjection
 */
class Container
{
    /**
     * @var \DI\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $componentDirectory;

    /**
     * Container constructor.
     * @param string $componentDirectory
     */
    public function __construct(string $componentDirectory)
    {
        $this->componentDirectory = $componentDirectory;
    }

    /**
     * @param $classname
     * @return mixed
     * @throws \Exception
     */
    public function get($classname)
    {
        if (!$this->container) {
            $this->container = $this->buildContainer();
        }
        return $this->container->get($classname);
    }

    /**
     * @return \DI\Container
     * @throws \Exception
     */
    public function buildContainer(): \DI\Container
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($this->setup());

        $this->buildCapsuleManager();

        return $builder->build();
    }

    /**
     * @return array
     */
    protected function setup()
    {
        global $DB;

        return array_merge([
            \moodle_database::class => $DB,
            Logger::class => $this->buildLogger(),
            Redis::class => $this->buildRedisClient()
        ], $this->getAdditionalDependencies());
    }

    /**
     * @return array
     */
    protected function getAdditionalDependencies(): array
    {
        return include $this->componentDirectory.'/dependencyInjection.php';
    }

    /**
     * In order for eloquent to work, we need to create a global capsule manager
     * This probably doesn't belong here but will work in the short term
     */
    protected function buildCapsuleManager()
    {
        global $CFG;

        $capsuleManager = new CapsuleManager(
            $CFG->dbtype,
            $CFG->dbhost,
            $CFG->dbname,
            $CFG->dbuser,
            $CFG->dbpass,
            $CFG->prefix
        );
        $capsuleManager->boot();
    }

    protected function buildLogger()
    {
        $logger = new Logger('name');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        return $logger;
    }

    protected function buildRedisClient()
    {
        global $CFG;

        $redis = new Redis();
        $redis->connect(...$CFG->redis_host);

        return $redis;
    }
}
