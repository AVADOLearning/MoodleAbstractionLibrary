<?php

namespace Avado\MoodleAbstractionLibrary\Middleware;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ResourceCacheMiddleware
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request, $httpKernel)
    {
        if($this->isAuthRequest($request)){
            return true;
        }

        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $response = $this->passRequestToCache($request, $httpKernel);
            $response->send();
        }
    }

    /**
     * @param Request $request
     * @param HttpKernel $httpKernel
     * @return Response
     */
    protected function passRequestToCache($request, $httpKernel)
    {
        $cache = new FilesystemTagAwareAdapter('', 1800, __DIR__);
    
        $cacheId = $this->buildCacheId($request);
    
        $resource = $this->getResourceFromRequest($request);
        $resourceId = $request->attributes->get('id');

        return $cache->get($cacheId, function (ItemInterface $item) use ($resource, $resourceId, $httpKernel, $request)  {
            $item->tag([$resource, $resource.'_'.$resourceId]);

            return $httpKernel->handle($request);
        });
    }

    /**
     *
     * @param Request $request
     * @return string
     */
    protected function buildCacheId($request)
    {
        return base64_encode(json_encode(array_merge($request->query->all(),$request->attributes->all())));
    }


    /**
     * @param $request
     * @return mixed
     */
    protected function getResourceFromRequest($request)
    {
        $controller = explode('::', $request->attributes->get('_controller'))[0];

        return $controller::RESOURCE;
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isAuthRequest(Request $request)
    {
        $controller = explode('::', $request->attributes->get('_controller'))[0];

        return $controller == AuthController::class;
    }
}
