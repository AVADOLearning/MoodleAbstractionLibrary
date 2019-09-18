<?php

namespace Avado\MoodleAbstrationLibrary\Routing;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Config\FileLocator;
use Avado\MoodleAbstrationLibrary\Routing\Controller\MoodleControllerResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class RoutingBoostrapService
 * @package Avado\MoodleAbstrationLibrary
 */
class RoutingBoostrapService
{
    /**
     * @var Router
     */
    protected $router = null;

    /**
     * @var string
     */
    protected $controllersPath;

    /**
     * @var RequestContext
     */
    protected $requestContext;

    /**
     * @var array
     */
    protected $dependecyDeclarations;

    /**
     * RoutingBoostrapService constructor.
     * @param string $controllersPath
     * @param string $cacheDir
     */
    public function __construct(string $controllersPath, array $dependecyDeclarations)
    {
        $this->controllersPath = $controllersPath;
        $this->dependecyDeclarations = $dependecyDeclarations;
    }

    /**
     *
     */
    public function handleRequest()
    {
        try {
            $router = $this->getRouter();
            $requestContext = $this->getRequestContext();

            $request = new Request(
                $_GET,
                $_POST,
                $router->match($requestContext->getPathInfo()),
                $_COOKIE,
                $_FILES,
                $_SERVER,
                null
            );

            $httpKernel = new HttpKernel(
                new EventDispatcher(),
                new MoodleControllerResolver(null, $this->dependecyDeclarations)
            );
            $httpKernel->handle($request)->send();
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return null
     */
    protected function getRouter(): Router
    {
        if (is_null($this->router)) {
            $this->router = $this->buildRouter();
        }
        return $this->router;
    }

    /**
     * @return AnnotationDirectoryLoader
     */
    protected function getLoader()
    {
        return new AnnotationDirectoryLoader(
            new FileLocator(),
            new AnnotatedRouteControllerLoader(new AnnotationReader())
        );
    }

    /**
     * @return RequestContext
     */
    protected function getRequestContext(): RequestContext
    {
        if (is_null($this->requestContext)) {
            $this->requestContext = $this->buildRequestContext();
        }
        return $this->requestContext;
    }

    /**
     * @return Router
     */
    protected function buildRouter(): Router
    {
        return new Router(
            $this->getLoader(),
            $this->controllersPath,
            [],
            $this->getRequestContext()
        );
    }

    /**
     * @return RequestContext
     */
    protected function buildRequestContext(): RequestContext
    {
        $requestContext = new RequestContext();
        $requestContext->fromRequest(Request::createFromGlobals());

        return $requestContext;
    }
}
