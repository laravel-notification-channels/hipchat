<?php

namespace NotificationChannels\HipChat;

use Closure;
use InvalidArgumentException;

class Card
{
    /**
     * An id that will help HipChat recognise the same card when it is sent multiple times.
     *
     * @var string
     */
    public $id;

    /**
     * The title of the card.
     * Valid length range: 1 - 500.
     *
     * @var string
     */
    public $title = '';

    /**
     * Style of the card.
     * Valid values: file, image, application, link, media.
     *
     * @var string
     */
    public $style = CardStyles::APPLICATION;

    /**
     * The description in the specific format.
     * Valid length range: 1 - 1000.
     *
     * @var string
     */
    public $content = '';

    /**
     * The format that can be html or text.
     *
     * @var string
     */
    public $format = 'text';

    /**
     * Application cards can be compact (1 to 2 lines) or medium (1 to 5 lines).
     *
     * @var string
     */
    public $cardFormat;

    /**
     * The url where the card will open.
     *
     * @var string
     */
    public $url = '';

    /**
     * The thumbnail url. Valid length range: 1 - 250.
     *
     * @var string
     */
    public $thumbnail;

    /**
     * The thumbnail url in retina. Valid length range: 1 - 250.
     *
     * @var string
     */
    public $thumbnail2;

    /**
     * The original width of the image.
     *
     * @var int
     */
    public $thumbnailWidth;

    /**
     * The original height of the image.
     *
     * @var int
     */
    public $thumbnailHeight;

    /**
     * Html for the activity to show in one line a summary of the action that happened.
     *
     * @var string
     */
    public $activity;

    /**
     * The activity icon url.
     *
     * @var string
     */
    public $activityIcon;

    /**
     * The activity icon url in retina.
     *
     * @var string
     */
    public $activityIcon2;

    /**
     * The icon url.
     *
     * @var string
     */
    public $icon;

    /**
     * The icon url in retina.
     *
     * @var string
     */
    public $icon2;

    /**
     * List of attributes to show below the card. Sample {label}:{value.icon} {value.label}.
     *
     * @var CardAttribute[]
     */
    public $attributes = [];

    /**
     * Create a new Card instance.
     *
     * @param string $title
     * @param string $id
     */
    public function __construct($title = '', $id = '')
    {
        $this->title($title);
        $this->id(str_empty($id) ? str_random() : $id);
    }

    /**
     * Create a new Card instance.
     *
     * @param string $title
     * @param string $id
     * @return static
     */
    public static function create($title = '', $id = '')
    {
        return new static($title, $id);
    }

    /**
     * Sets the title of the card.
     *
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = trim($title);

        return $this;
    }

    /**
     * Sets the id of the card.
     *
     * @param $id
     * @return $this
     */
    public function id($id)
    {
        $this->id = trim($id);

        return $this;
    }

    /**
     * Sets the style of the card.
     *
     * @param $style
     * @return $this
     */
    public function style($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Sets the content of the card.
     *
     * @param $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = trim($content);

        return $this;
    }

    /**
     * Sets the format to plain text and optionally the content.
     *
     * @param string $content
     * @return $this
     */
    public function text($content = '')
    {
        $this->format = 'text';

        if (! str_empty($content)) {
            $this->content($content);
        }

        return $this;
    }

    /**
     * Sets the format to html and optionally the content.
     *
     * @param string $content
     * @return $this
     */
    public function html($content = '')
    {
        $this->format = 'html';

        if (! str_empty($content)) {
            $this->content($content);
        }

        return $this;
    }

    /**
     * Sets the format of the card.
     *
     * @param $cardFormat
     * @return $this
     */
    public function cardFormat($cardFormat)
    {
        $this->cardFormat = trim($cardFormat);

        return $this;
    }

    /**
     * Sets the url of the card.
     *
     * @param $url
     * @return $this
     */
    public function url($url)
    {
        $this->url = trim($url);

        return $this;
    }

    /**
     * Sets the thumbnail of the card.
     *
     * @param string $icon
     * @param string|null $icon2
     * @param int|null $width
     * @param int|null $height
     * @return $this
     */
    public function thumbnail($icon, $icon2 = null, $width = null, $height = null)
    {
        $this->thumbnail = trim($icon);

        if (! str_empty($icon2)) {
            $this->thumbnail2 = trim($icon2);
        }

        if (! is_null($width)) {
            $this->thumbnailWidth = $width;
        }

        if (! is_null($height)) {
            $this->thumbnailHeight = $height;
        }

        return $this;
    }

    /**
     * Sets the activity of the card.
     *
     * @param string $html
     * @param string|null $icon
     * @param string|null $icon2
     * @return $this
     */
    public function activity($html, $icon = null, $icon2 = null)
    {
        $this->activity = trim($html);

        if (! str_empty($icon)) {
            $this->activityIcon = trim($icon);
        }

        if (! str_empty($icon2)) {
            $this->activityIcon2 = trim($icon2);
        }

        return $this;
    }

    /**
     * Sets the icon of the card.
     *
     * @param string $icon
     * @param string|null $icon2
     * @return $this
     */
    public function icon($icon, $icon2 = null)
    {
        $this->icon = trim($icon);

        if (! str_empty($icon2)) {
            $this->icon2 = trim($icon2);
        }

        return $this;
    }

    /**
     * Adds a CardAttribute to the card.
     *
     * @param CardAttribute|Closure $attribute
     * @return $this
     */
    public function addAttribute($attribute)
    {
        if ($attribute instanceof CardAttribute) {
            $this->attributes[] = $attribute;

            return $this;
        }

        if ($attribute instanceof Closure) {
            $attribute($new = new CardAttribute());
            $this->attributes[] = $new;

            return $this;
        }

        throw new InvalidArgumentException(
            'Invalid attribute type. Expected '.CardAttribute::class.' or '. Closure::class.'.'
        );
    }

    /**
     * Get an array representation of the Card.
     *
     * @return array
     */
    public function toArray()
    {
        $card = str_array_filter([
            'id' => $this->id,
            'style' => $this->style,
            'format' => $this->cardFormat,
            'title' => $this->title,
            'url' => $this->url,
        ]);

        if (! str_empty($this->content)) {
            $card['description'] = [
                'value' => $this->content,
                'format' => $this->format,
            ];
        }

        if (! str_empty($this->thumbnail)) {
            $card['thumbnail'] = str_array_filter([
                'url' => $this->thumbnail,
                'url@2x' => $this->thumbnail2,
                'width' => $this->thumbnailWidth,
                'height' => $this->thumbnailHeight,
            ]);
        }

        if (! str_empty($this->activity)) {
            $card['activity'] = str_array_filter([
                'html' => $this->activity,
                'icon' => str_array_filter([
                    'url' => $this->activityIcon,
                    'url@2x' => $this->activityIcon2,
                ]),
            ]);
        }

        if (! str_empty($this->icon)) {
            $card['icon'] = str_array_filter([
                'url' => $this->icon,
                'url@2x' => $this->icon2,
            ]);
        }

        if (! empty($this->attributes)) {
            $card['attributes'] = array_map(function (CardAttribute $attribute) {
                return $attribute->toArray();
            }, $this->attributes);
        }

        return $card;
    }
}
