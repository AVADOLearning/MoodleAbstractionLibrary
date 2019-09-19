<?php

namespace Avado\MoodleAbstractionLibrary\DependencyInjection;

use Avado\MoodleAbstractionLibrary\Database\CapsuleManager;
use DI\ContainerBuilder;

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
    protected function buildContainer(): \DI\Container
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
}
