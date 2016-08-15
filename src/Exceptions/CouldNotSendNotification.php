<?php

namespace NotificationChannels\HipChat\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends Exception
{
    public static function hipChatRespondedWithAnError(ClientException $exception)
    {
        $code = $exception->getResponse()->getStatusCode();
        $message = $exception->getResponse()->getBody();

        return new static("HipChat responded with an error `{$code} - {$message}`");
    }

    public static function missingTo()
    {
        return new static('Notification was not sent. Room identifier is missing.');
    }

    public static function invalidMessageObject($message)
    {
        $class = get_class($message) ?: 'Unknown';

        return new static('Notification was not sent. The message should be an instance of or extend '.HipChatMessage::class.". `Given {$class}` is invalid.");
    }

    public static function internalError()
    {
        return new static("Couldn't connect to HipChat API.");
    }
}
