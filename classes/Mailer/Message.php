<?php

namespace Avado\MoodleAbstractionLibrary\Mailer;

use Swift_Message;

/**
 * Class Message
 *
 * Wrapper class for email content
 *
 * @package Avado\MoodleAbstractionLibrary\Mailer
 */
class Message
{
    /**
     * @var Swift_Message
     */
    protected $message;

    /**
     * Mail constructor.
     *
     * @param Swift_Message $message
     */
    public function __construct(Swift_Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return Swift_Message
     */
    public function getMessage(): Swift_Message
    {
        return $this->message;
    }

    /**
     * @return String
     */
    public function getSubject(): String
    {
        return $this->message->getSubject();
    }

    /**
     * @param array $addresses
     * @return $this
     */
    public function setTo(array $addresses): Message
    {
        $this->message->setTo($addresses);

        return $this;
    }

    /**
     * @param String $subject
     * @return $this
     */
    public function setSubject(String $subject): Message
    {
        $this->message->setSubject($subject);

        return $this;
    }

    /**
     * @param String      $body
     * @param String|null $contentType
     * @return $this
     */
    public function setBody(String $body, String $contentType = 'text/html'): Message
    {
        $this->message->setBody($body, $contentType);

        return $this;
    }

    /**
     * @param array $addresses
     * @return $this
     */
    public function setFrom(array $addresses): Message
    {
        $this->message->setFrom($addresses);

        return $this;
    }

    /**
     * Returns number of intended recipients
     *
     * @return int
     */
    public function getToCount(): int
    {
        return count($this->message->getTo());
    }
}
