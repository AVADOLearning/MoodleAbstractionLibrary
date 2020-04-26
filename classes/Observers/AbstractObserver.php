<?php

namespace Avado\MoodleAbstractionLibrary\Observers;

abstract class AbstractObserver
{
    /**
     * @param array $listeners
     * @return void
     */
    public function listen(array $events, array $arguments): bool
    {
        foreach ($events as $event) {
            if($event->check()){
                $event->handle($arguments);
            }
        }
        return true;
    }
}
