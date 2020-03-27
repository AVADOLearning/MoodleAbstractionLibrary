<?php

namespace Avado\MoodleAbstractionLibrary\Middleware;

use Avado\MoodleAbstractionLibrary\Controllers\AuthController;
use Avado\MoodleAbstractionLibrary\Entities\ACL\User;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ACLMiddleware
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request)
    {
        if($this->isAuthRequest($request)){
            return true;
        }

        $token = $this->validateAndDecodeToken($request->headers->get('token'));

        $controllerMethod = $this->getControllerMethod($request);
        $controllerModel = $this->getControllerModel($request);
        $resourceAttribute = $this->getClassBaseName($controllerModel);

        $controllerModel = $controllerModel::find($request->attributes->get($resourceAttribute));

        $activeUser = User::find($token->user->id);

        if(!$controllerModel && $request->attributes->get($resourceAttribute)){
            throw new NotFoundHttpException("That resource doesn't exist");
        }

        $controllerPolicy = $this->getControllerPolicy($request);
        $controllerPolicy = new $controllerPolicy;

        $resource = $this->getResourceFromRequest($request);

        if($activeUser->hasPermission("{$resource}-admin")){
            return true;
        }

        if(!method_exists($controllerPolicy, $controllerMethod)){
            throw new AccessDeniedHttpException("A policy doesn't exist for that operation");
        }

        if(!$controllerPolicy->$controllerMethod($activeUser, $request, $controllerModel)){
            throw new AccessDeniedHttpException('You not permitted to carry out that operation');
        }
        return true;
    }

    /**
     * @param $request
     * @return mixed
     */
    protected function getControllerPolicy($request)
    {
        $controller = explode('::', $request->attributes->get('_controller'))[0];

        return $controller::POLICY;
    }

    /**
     * @param $request
     * @return mixed
     */
    protected function getControllerModel($request)
    {
        $controller = explode('::', $request->attributes->get('_controller'))[0];

        return $controller::MODEL;
    }

    /**
     * @param $request
     * @return mixed
     */
    protected function getControllerMethod($request)
    {
        return explode('::', $request->attributes->get('_controller'))[1];
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

    /**
     * @param string $token
     * @return object
     */
    protected function validateAndDecodeToken(string $token)
    {
        try {
            return JWT::decode($token, AuthMiddleware::JWT_KEY, [AuthMiddleware::ALGORITHM]);
        } catch (\Exception $e){
            throw new AccessDeniedHttpException("Invalid token provided");
        }
    }

    /**
     *
     * @param string $className
     * @return string
     */
    protected function getClassBaseName($className)
    {
        return lcfirst((new \ReflectionClass($className))->getShortName());
    }
}
