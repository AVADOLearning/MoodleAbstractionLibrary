<?php

namespace Avado\MoodleAbstractionLibrary\Events;

use Avado\MoodleAbstractionLibrary\Entities\Notification;
use Avado\MoodleAbstractionLibrary\Entities\NotificationComponent;
use Avado\MoodleAbstractionLibrary\Entities\NotificationEmailQueue;
use Avado\MoodleAbstractionLibrary\Entities\NotificationEventType;
use Avado\MoodleAbstractionLibrary\Entities\NotificationSetting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

/**
 * Class SaveNotificationOnMessageSaveEvent
 * @package Avado\MoodleAbstractionLibrary\Events\AbstractEvent
 */
class SaveNotificationOnMessageSaveEvent extends AbstractEvent
{
    /**
     * @var bool
     */
    public const QUEUED = false;

    /**
     * @param array $arguments
     * @return bool
     */
    public function check(array $arguments): bool
    {
        return true;
    }

    /**
     * @param array $arguments
     * @return bool
     */
    public function handle(array $arguments): bool
    {
        return static::QUEUED ? $this->addToQueue($arguments) : $this->execute($arguments);
    }
    
    /**
     * @param array $arguments
     * @return bool
     */
    public function execute(array $arguments): bool
    {
        $message = $arguments['message'];

        $eventTypeId          = $this->getEventTypeId($message->name);
        $notificationSettings = $this->notificationSettings($message->useridto, $eventTypeId);

        if($notificationSettings->isNotEmpty()) {
            $shouldEmail = $this->shouldEmail($notificationSettings);
            try {
                $notification = Notification::create([
                    'from_user_id'         => $message->useridfrom,
                    'to_user_id'           => $message->useridto,
                    'subject'              => $message->subject,
                    'content'              => $message->fullmessage,
                    'html_content'         => $message->fullmessagehtml,
                    'is_read'              => 0,
                    'contexturl'           => $message->contexturl ?? null,
                    'contexturlname'       => $message->contexturlname ?? null,
                    'component_id'         => $this->getComponentId($message->component),
                    'event_type_id'        => $eventTypeId,
                    'notification_type_id' => 1,
                    'should_email'         => (int) $shouldEmail
                ]);
            } catch (QueryException $e) {
                return false;
            }

            if ($shouldEmail) {
                $this->addToMailQueue($notification->id);
            }
        }
        return true;
    }

    /**
     * Get event type id from string type. If it doesnt exist add it.
     *
     * @param mixed $eventType
     * @return int
     */
    protected function getEventTypeId($eventType): int
    {
        $eventType = $eventType == null ? 'nullEventType' : $eventType;

        $eventType = NotificationEventType::where(['name' => $eventType])->first();

        if (!$eventType) {
            $eventType = $this->createEventType($eventType);
        }

        return $eventType->id;
    }

    /**
     * Get any enabled settings for specified event type against a user
     *
     * @param int $userId
     * @param int $eventTypeId
     * @return Collection
     */
    protected function notificationSettings(int $userId, int $eventTypeId): Collection
    {
        return NotificationSetting::where([
            'user_id'       => $userId,
            'event_type_id' => $eventTypeId,
            'is_enabled'    => 1
        ])->get();
    }

    /**
     * Of enabled settings returned for event type, see if any are for email.
     *
     * @param Collection $settings
     * @return bool
     */
    protected function shouldEmail(Collection $settings): bool
    {
        foreach ($settings as $setting) {
            if ($setting->notification_type_id == 2) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed $componentName
     * @return int
     */
    protected function getComponentId($componentName): int
    {
        $component = NotificationComponent::where(['name' => $componentName])->first();

        if (!$component) {
            $component = $this->createComponentId($componentName);
        }

        return $component->id;
    }

    /**
     * @param int $notificationId
     * @return bool
     */
    protected function addToMailQueue(int $notificationId): bool
    {
        try {
            NotificationEmailQueue::create([
                'notification_id' => $notificationId,
                'status' => 0
            ]);
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * @param string $eventType
     * @return mixed
     */
    protected function createEventType(string $eventType)
    {
        try {
            return NotificationEventType::create([
                'name' => $eventType,
                'status' => 1
            ]);
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * @param string $componentName
     * @return mixed
     */
    protected function createComponentId(string $componentName)
    {
        try {
            return NotificationComponent::create([
                'name' => $componentName,
                'status' => 1
            ]);
        } catch (QueryException $e) {
            return false;
        }
    }
}
