<?php

namespace Avado\MoodleAbstractionLibrary\Routing;

use Avado\MoodleAbstractionLibrary\Middleware\ACLMiddleware;
use Avado\MoodleAbstractionLibrary\Middleware\AuthMiddleware;
use Avado\MoodleAbstractionLibrary\Middleware\ResourceCacheMiddleware;
use Avado\MoodleAbstractionLibrary\DependencyInjection\Container;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Config\FileLocator;
use Avado\MoodleAbstractionLibrary\Routing\Controller\MoodleControllerResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener;
use Doctrine\Common\Annotations\DocParser;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Avado\MoodleAbstractionLibrary\Listeners\MagicControllerArgumentsListener;
use Avado\MoodleAbstractionLibrary\Listeners\AttachModelRelationshipsListener;

/**
 * Class RoutingBootstrapService
 * @package Avado\MoodleAbstrationLibrary
 */
class RoutingBootstrapService
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
     * @var string
     */
    protected $componentDirectory;

    /**
     * @var bool
     */
    protected $useEventsAndMiddleware;

    /**
     * RoutingBootstrapService constructor.
     * @param string $controllersPath
     * @param string $cacheDir
     */
    public function __construct(string $componentDirectory, bool $useEventsAndMiddleware = false)
    {
        $this->componentDirectory = $componentDirectory;
        $this->useEventsAndMiddleware = $useEventsAndMiddleware;
    }
    /**
     *
     */
    public function handleRequest()
    {
        try {
            $router = $this->getRouter();
            $requestContext = $this->getRequestContext();
            $requestAttributes = $router->match($requestContext->getPathInfo());

            $request = new Request(
                $_GET,
                $_POST,
                $requestAttributes,
                $_COOKIE,
                $_FILES,
                $_SERVER,
                null
            );

            $httpKernel = new HttpKernel(
                $this->buildEventDispatcher(),
                new MoodleControllerResolver(null, $this->componentDirectory, $request)
            );

            if($this->useEventsAndMiddleware){
                try {
                    $this->passThroughMiddleware($request, $httpKernel);
    
                } catch (HttpException $e){
                    (new JsonResponse(['success'=>'false','message'=>$e->getMessage()]))->send();die;
                }
            }

            $response = $httpKernel->handle($request)->send();
            
        } catch (ResourceNotFoundException $e) {
            (new JsonResponse(['success'=>'false','message'=>$e->getMessage()]))->send();die;
        } catch (\Exception $e){
            (new JsonResponse(['success'=>'false','message'=>$e->getMessage()]))->send();die;
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
            $this->componentDirectory.'/classes/Controllers',
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
    /**
     * @param $request
     */
    protected function passThroughMiddleware($request, $httpKernel)
    {
        $middlewares = [
            AuthMiddleware::class,
            ACLMiddleware::class,
            ResourceCacheMiddleware::class
        ];
        foreach ($middlewares as $middleware){
            $middleware = (new Container($this->componentDirectory))->get($middleware);
            $middleware->handle($request, $httpKernel);
        }
    }

    /**
     *
     * @return EventDispatcher
     */
    protected function buildEventDispatcher()
    {
        if(!$this->useEventsAndMiddleware){
            return new EventDispatcher();
        }

        $controllerListener = new ControllerListener(new AnnotationReader(new DocParser()));
        $magicArgumentsListener = new MagicControllerArgumentsListener();
        $attachRelationshipsListener = new AttachModelRelationshipsListener();
        
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($controllerListener);
        $eventDispatcher->addSubscriber($magicArgumentsListener);
        $eventDispatcher->addSubscriber($attachRelationshipsListener);

        return $eventDispatcher;
    }
}
