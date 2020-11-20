<?php

namespace Avado\MoodleAbstractionLibrary\Mailer;

use Monolog\Logger;
use Swift_Mailer;

/**
 * Class Mailer
 *
 * @package Avado\MoodleAbstractionLibrary\Services
 */
class Mailer
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * EmailService constructor.
     *
     * @param Swift_Mailer $mailer
     * @param Logger       $logger
     */
    public function __construct(Swift_Mailer $mailer, Logger $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @param Message $message
     * @return int
     */
    public function send(Message $message): int
    {
        $sentCount = $this->mailer->send($message->getMessage());
        $this->logIfSendFailure($message, $sentCount);

        return $sentCount;
    }

    /**
     * @param Message $message
     * @param int     $sentCount
     */
    protected function logIfSendFailure(Message $message, int $sentCount)
    {
        if ($sentCount < $message->getToCount()) {
            $this->logger->debug(
                "Mail failed to send to all users ($sentCount of {$message->getToCount()}) 
                        for email: {$message->getSubject()}"
            );
        }
    }
}
