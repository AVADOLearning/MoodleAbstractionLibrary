<?php

namespace Avado\MoodleAbstrationLibrary\Routing\Controller;

use Psr\Log\LoggerInterface;
use Avado\MoodleAbstrationLibrary\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class MoodleControllerResolver extends ControllerResolver
{
    /**
     * @var array
     */
    protected $classDeclarations;

    public function __construct(LoggerInterface $logger = null, array $classDeclarations = [])
    {
        $this->classDeclarations = $classDeclarations;
        parent::__construct($logger);
    }

    protected function instantiateController($class)
    {
        try {
            $container = new Container($this->classDeclarations);
            return $container->get($class);
        } catch (\Exception $e) {

        }
    }
}
