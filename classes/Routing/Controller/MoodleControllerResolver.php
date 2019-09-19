<?php

namespace Avado\MoodleAbstractionLibrary\Routing\Controller;

use Psr\Log\LoggerInterface;
use Avado\MoodleAbstractionLibrary\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class MoodleControllerResolver extends ControllerResolver
{
    /**
     * @var string
     */
    protected $componentDirectory;

    /**
     * MoodleControllerResolver constructor.
     * @param LoggerInterface|null $logger
     * @param string $componentDirectory
     */
    public function __construct(LoggerInterface $logger = null, string $componentDirectory)
    {
        $this->componentDirectory = $componentDirectory;
        parent::__construct($logger);
    }

    /**
     * @param string $class
     * @return mixed|object
     * @throws \Exception
     */
    protected function instantiateController($class)
    {
        $container = new Container($this->componentDirectory);

        return $container->get($class);
    }
}
