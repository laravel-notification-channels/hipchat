<?php

namespace NotificationChannels\HipChat\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when there's a bad request from the HttpClient.
     *
     * @param \GuzzleHttp\Exception\ClientException $exception
     * @return static
     */
    public static function hipChatRespondedWithAnError(ClientException $exception)
    {
        $code = $exception->getResponse()->getStatusCode();
        $message = $exception->getResponse()->getBody();

        return new static("HipChat responded with an error `{$code} - {$message}`");
    }

    /**
     * Thrown when to (room identifier) is missing.
     *
     * @return static
     */
    public static function missingTo()
    {
        return new static('Notification was not sent. Room identifier is missing.');
    }

    /**
     * Thrown when an invalid message object was passed.
     *
     * @param mixed $message
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $class = get_class($message) ?: 'Unknown';

        return new static('Notification was not sent. The message should be an instance of or extend '.HipChatMessage::class.". `Given {$class}` is invalid.");
    }

    /**
     * Thrown when any other exception is caught while sending a notification message.
     *
     * @return static
     */
    public static function internalError()
    {
        return new static("Couldn't connect to HipChat API.");
    }
}
