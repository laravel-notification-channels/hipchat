<?php

namespace NotificationChannels\HipChat;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use NotificationChannels\HipChat\Exceptions\CouldNotSendNotification;

class HipChatChannel
{
    /**
     * The HipChat client instance.
     *
     * @var \NotificationChannels\HipChat\HipChat
     */
    protected $hipChat;

    /**
     * @param \NotificationChannels\HipChat\HipChat
     */
    public function __construct(HipChat $hipChat)
    {
        $this->hipChat = $hipChat;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\HipChat\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toHipChat($notifiable);

        if (is_string($message)) {
            $message = new HipChatMessage($message);
        }

        if (! in_array(get_class($message), [HipChatMessage::class, HipChatFile::class])) {
            throw CouldNotSendNotification::invalidMessageObject($message);
        }

        $to = $message->room ?: $notifiable->routeNotificationFor('HipChat');
        if (! $to = $to ?: $this->hipChat->room()) {
            throw CouldNotSendNotification::missingTo();
        }

        try {
            $this->sendMessage($to, $message);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::hipChatRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::internalError();
        }
    }

    /**
     * Send the HipChat notification message.
     *
     * @param $to
     * @param mixed $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendMessage($to, $message)
    {
        if ($message instanceof HipChatMessage) {
            return $this->hipChat->sendMessage($to, $message->toArray());
        }

        if ($message instanceof HipChatFile) {
            return $this->hipChat->shareFile($to, $message->toArray());
        }
    }
}
