<?php

namespace NotificationChannels\HipChat;

class HipChatMessage
{
    /**
     * HipChat room.
     *
     * @var string
     */
    public $room = '';

    /**
     * A label to be shown in addition to the sender's name.
     *
     * @var string
     */
    public $from = '';

    /**
     * The format of the notification (text, html).
     *
     * @var string
     */
    public $format = 'text';

    /**
     * Should a message trigger a user notification in a HipChat client.
     *
     * @var bool
     */
    public $notify = false;

    /**
     * The "level" of the notification (info, success, error).
     *
     * @var string
     */
    public $level = 'info';

    /**
     * The color of the notification (yellow, green, red, purple, gray, random).
     *
     * @var string
     */
    public $color = 'gray';

    /**
     * The text content of the message.
     *
     * @var string
     */
    public $content = '';

    /**
     * Create a new instance of HipChatMessage.
     *
     * @param string $content
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Create a new instance of HipChatMessage.
     *
     * @param $content
     */
    public function __construct($content = '')
    {
        $this->content($content);
    }

    /**
     * Set the HipChat room to send message to.
     *
     * @param int|string $room
     * @return $this
     */
    public function room($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Indicate that the notification gives general information.
     *
     * @return $this
     */
    public function info()
    {
        $this->level = 'info';
        $this->color = 'gray';

        return $this;
    }

    /**
     * Indicate that the notification gives information about a successful operation.
     *
     * @return $this
     */
    public function success()
    {
        $this->level = 'success';
        $this->color = 'green';

        return $this;
    }

    /**
     * Indicate that the notification gives information about an error.
     *
     * @return $this
     */
    public function error()
    {
        $this->level = 'error';
        $this->color = 'red';

        return $this;
    }

    /**
     * Set the from label of the HipChat message.
     *
     * @param  string  $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = trim($from);

        return $this;
    }

    /**
     * Set HTML format and optionally the content.
     *
     * @param string $content
     * @return $this
     */
    public function html($content = '')
    {
        $this->format = 'html';

        if (! empty($content)) {
            $this->content($content);
        }

        return $this;
    }

    /**
     * Set text format and optionally the content.
     *
     * @param string $content
     * @return $this
     */
    public function text($content = '')
    {
        $this->format = 'text';

        if (! empty($content)) {
            $this->content($content);
        }

        return $this;
    }

    /**
     * Should a message trigger a user notification in a HipChat client.
     *
     * @param  bool  $notify
     * @return $this
     */
    public function notify($notify = true)
    {
        $this->notify = $notify;

        return $this;
    }

    /**
     * Set the content of the message.
     * Allowed HTML tags: a, b, i, strong, em, br, img, pre, code, lists, tables.
     *
     * @param  string  $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = trim($content);

        return $this;
    }

    /**
     * Set the color for the message.
     * Allowed values: yellow, green, red, purple, gray, random
     *
     * @param $color
     * @return string
     */
    public function color($color)
    {
        $this->color = $color;

        return $this;
    }

    public function toArray()
    {
        return [
            'from' => $this->from,
            'message_format' => $this->format,
            'color' => $this->color,
            'notify' => $this->notify,
            'message' => $this->content,
        ];
    }
}
