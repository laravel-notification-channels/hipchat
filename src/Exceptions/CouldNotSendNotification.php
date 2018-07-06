<?php

namespace NotificationChannels\HipChat\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\HipChat\HipChatMessage;

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
        return new static(sprintf(
            'HipChat responded with an error %s - %s',
            $exception->getResponse()->getStatusCode(),
            $exception->getResponse()->getBody()
        ));
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
        return new static(sprintf(
            'Notification was not sent. The message should be an instance of or extend %s. Given % is invalid.',
            HipChatMessage::class,
            get_class($message) ?: 'Unknown'
        ));
    }

    /**
     * Thrown when any other exception is caught while sending a notification message.
     *
     * @return static
     */
    public static function internalError($exception = null)
    {
        return new static('Error occurred while sending the message.', 0, $exception);
    }
}
