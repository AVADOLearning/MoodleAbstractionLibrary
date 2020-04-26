<?php

namespace Avado\MoodleAbstractionLibrary\Events;

abstract class AbstractEvent
{    /**
     * @param array $arguments
     * @return boolean
     */
    abstract public function execute(array $arguments): bool;

    /**
     * @param array $arguments
     * @return boolean
     */
    public function addToQueue(array $arguments): bool
    {
        return QueueHandler::addJob(static::class, $arguments);
    }
}
