<?php

namespace NotificationChannels\HipChat;

class CardAttribute
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $style;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $icon2;

    /**
     * Sets the textual value of the attribute.
     *
     * @param string $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = trim($value);

        return $this;
    }

    /**
     * Sets the label of the attribute.
     *
     * @param string $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = trim($label);

        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function url($url)
    {
        $this->url = trim($url);

        return $this;
    }

    /**
     * Sets the style for the attribute.
     *
     * @param string $style
     * @return $this
     */
    public function style($style)
    {
        $this->style = trim($style);

        return $this;
    }

    /**
     * Sets the icon for the atttribute.
     *
     * @param string $icon
     * @param string|null $icon2
     * @return $this
     */
    public function icon($icon, $icon2 = null)
    {
        $this->icon = trim($icon);

        if (! empty($icon2)) {
            $this->icon2 = trim($icon2);
        }

        return $this;
    }

    /**
     * Create a new instance of the attribute.
     *
     * @param string $label
     * @param string $value
     */
    public function __construct($value = null, $label = null)
    {
        if (trim($value) !== '') {
            $this->value($value);
        }

        if (trim($label) !== '') {
            $this->label($label);
        }
    }

    /**
     * Create a new instance of the attribute.
     *
     * @param string $value
     * @param string $label
     * @return static
     */
    public static function create($value = null, $label = null)
    {
        return new static($value, $label);
    }

    public function toArray()
    {
        $attribute = [
            'value' => array_filter([
                'label' => $this->value,
                'url' => $this->url,
                'style' => $this->style,
            ], function ($value) {
                return trim($value) !== '';
            }),
        ];

        if (! empty($this->icon)) {
            $attribute['value']['icon'] = array_filter([
                'url' => $this->icon,
                'url@2x' => $this->icon2,
            ], function ($value) {
                return trim($value) !== '';
            });
        }

        if (! empty($this->label)) {
            $attribute['label'] = $this->label;
        }

        return $attribute;
    }
}
