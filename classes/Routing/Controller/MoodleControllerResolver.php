<?php

namespace Avado\MoodleAbstractionLibrary\Routing\Controller;

use Psr\Log\LoggerInterface;
use Avado\MoodleAbstractionLibrary\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Monolog\Logger;

class MoodleControllerResolver extends ControllerResolver
{
    /**
     * @var string
     */
    protected $componentDirectory;

    /**
     * @var Request
     */
    protected $request;

    /**
     * MoodleControllerResolver constructor.
     * @param LoggerInterface|null $logger
     * @param string $componentDirectory
     * @param Request $request
     */
    public function __construct(LoggerInterface $logger = null, string $componentDirectory, Request $request)
    {
        $this->componentDirectory = $componentDirectory;
        $this->request = $request;

        parent::__construct($logger);
    }

    /**
     * @param string $class
     * @return mixed|object
     * @throws \Exception
     */
    protected function instantiateController($class)
    {
        $controller = (new Container($this->componentDirectory))->get($class);
        $controller->setRequest($this->request);
        $controller->setCacheAdapter(new FilesystemTagAwareAdapter(),[]);
        $controller->setLogger((new Container($this->componentDirectory))->get(Logger::class));
        $controller->boot();

        return $controller;
    }
}
