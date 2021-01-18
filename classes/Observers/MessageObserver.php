<?php

namespace Avado\MoodleAbstractionLibrary\Observers;

use Avado\MoodleAbstractionLibrary\Entities\Message;
use Avado\MoodleAbstractionLibrary\Events\SaveNotificationOnMessageSaveEvent;
use Avado\MoodleAbstractionLibrary\Observers\AbstractObserver;

/**
 * Class MessageObserver
 * @package Avado\AlpApi\Messages\Observers
 */
class MessageObserver extends AbstractObserver
{
    /**
     * @param Message $message
     */
    public function saved(Message $message): void
    {
        $events = [
            new SaveNotificationOnMessageSaveEvent()
        ];

        $this->listen($events, ['message' => $message]);
    }
}
