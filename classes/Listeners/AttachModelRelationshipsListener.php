<?php

namespace Avado\MoodleAbstractionLibrary\Listeners;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\Proxy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AttachModelRelationshipsListener implements EventSubscriberInterface
{
    /**
     * Modifies the Request object to apply inject models where applicable. Replace id's with
     * an eloquent model.
     */
    public function onKernelController(KernelEvent $event)
    {
        $controller = new \ReflectionClass($event->getController()[0]);

        $controllerModel = $controller->getConstant('MODEL');
        $method = $controller->getMethod($event->getController()[1]);
        $methodParameters = $method->getParameters();
        $requestArguments = $event->getArguments();

        $event->setArguments(
            $this->addRelationhipsToArgumentModels($methodParameters, $requestArguments, $controllerModel, $event->getRequest())
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelController',
        ];
    }

    /**
     * @param array $methodParameters
     * @param array $requestArguments
     * @param BaseModel $controllerModel
     * @return array
     */
    protected function addRelationhipsToArgumentModels($methodParameters, $requestArguments, $controllerModel, $request)
    {
        return array_map(function($parameter, $argument) use ($controllerModel, $request){
            $parameterType = $parameter->getType()->getName();

            if($parameterType == $controllerModel){
                if($relationships = $request->get('relationships')){
                    $argument->load(...explode(',', $relationships));
                }
            }
            return $argument;
        }, $methodParameters, $requestArguments);
    }
}
