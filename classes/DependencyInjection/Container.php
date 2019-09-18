<?php

namespace Avado\MoodleAbstrationLibrary\DependencyInjection;

use DI\ContainerBuilder;

/**
 * Class Container
 * @package Avado\MoodleAbstrationLibrary\DependencyInjection
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
}
