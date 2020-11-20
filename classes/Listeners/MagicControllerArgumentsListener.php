<?php

namespace Avado\MoodleAbstractionLibrary\Listeners;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\Proxy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Avado\MoodleAbstractionLibrary\Traits\ChecksUserIsPrivileged;

class MagicControllerArgumentsListener implements EventSubscriberInterface
{
    use ChecksUserIsPrivileged;

    /**
     * Modifies the Request object to apply inject models where applicable. Replace id's with
     * an eloquent model.
     */
    public function onKernelController(KernelEvent $event)
    {
        $returnArguments = [];

        $controller = new \ReflectionClass($event->getController()[0]);
        $controllerModel = $controller->getConstant('MODEL');
        $controllerModel::setPrivileged($this->isStaff($event->getRequest()));
        $method = $controller->getMethod($event->getController()[1]);
        $methodParameters = $method->getParameters();
        $requestArguments = $event->getArguments();

        $event->setArguments(
            $this->replaceParametersWithModel($methodParameters, $requestArguments, $controllerModel)
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

    protected function getControllerModel()
    {
    }

    /**
     * @param array $methodParameters
     * @param array $requestArguments
     * @param BaseModel $controllerModel
     * @return array
     */
    protected function replaceParametersWithModel(
        $methodParameters,
        $requestArguments,
        $controllerModel
        ) {
        return array_map(function ($parameter, $argument) use ($controllerModel) {
            $parameterType = $parameter->getType()->getName();
            $argument = $argument;

            if ($parameterType == $controllerModel) {
                $argument = $controllerModel::find($argument);
            }

            return $argument;
        }, $methodParameters, $requestArguments);
    }
}
