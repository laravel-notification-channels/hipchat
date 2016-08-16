<?php

namespace NotificationChannels\HipChat;

class HipChatFile
{
    /**
     * The HipChat room identifier.
     *
     * @var int|string
     */
    public $room;

    /**
     * Message content that is sent along with the file.
     *
     * @var string
     */
    public $content = '';

    /**
     * File content.
     * Can be a resource, stream, string.
     *
     * @var mixed
     */
    public $fileContent;

    /**
     * A new file name for the file.
     *
     * @var string
     */
    public $fileName = '';

    /**
     * A valid mime type of the file content.
     *
     * @var string
     */
    public $fileType = '';

    /**
     * Create an instance of HipChatFile.
     *
     * @param string $path
     */
    public function __construct($path = '')
    {
        if (! empty($path)) {
            $this->path($path);
        }
    }

    /**
     * Create an instance of HipChatFile.
     *
     * @param string $path
     * @return static
     */
    public static function create($path = '')
    {
        return new static($path);
    }

    /**
     * Set the HipChat room to share the file in.
     *
     * @param string|int $room
     * @return $this
     */
    public function room($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Set the content of the message.
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
     * Alias for content().
     *
     * @param  string  $text
     * @return $this
     */
    public function text($text)
    {
        return $this->content($text);
    }

    /**
     * Set the file path.
     *
     * @param  string  $path
     * @return $this
     */
    public function path($path)
    {
        if (empty($this->fileName)) {
            $this->fileName(basename($path));
        }

        if (empty($this->fileType)) {
            $this->fileType(mime_content_type($path));
        }

        $this->fileContent(fopen($path, 'r'));

        return $this;
    }

    /**
     * Explicitly set the content of the file.
     *
     * @param $content
     * @return $this
     */
    public function fileContent($content)
    {
        if (is_resource($this->fileContent)) {
            fclose($this->fileContent);
        }

        $this->fileContent = $content;

        if (is_resource($content) && empty($this->fileType)) {
            $this->fileType($this->getTypeFromResource($content));
        }

        return $this;
    }

    /**
     * Set the new name of the file.
     *
     * @param  string  $fileName
     * @return $this
     */
    public function fileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Set the mime type of the file content.
     *
     * @param  string  $fileType
     * @return $this
     */
    public function fileType($fileType)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'content' => $this->fileContent,
            'filename' => $this->fileName,
            'file_type' => $this->fileType,
            'message' => $this->content,
        ];
    }

    /**
     * Get the media type from a resource.
     *
     * @param $resource
     * @return string
     */
    protected function getTypeFromResource($resource)
    {
        $meta = stream_get_meta_data($resource);

        return isset($meta['mediatype']) ? $meta['mediatype'] : null;
    }
}
