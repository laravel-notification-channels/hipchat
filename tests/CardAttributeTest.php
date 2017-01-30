<?php

namespace NotificationChannels\HipChat\Test;

use NotificationChannels\HipChat\CardAttribute;
use NotificationChannels\HipChat\CardAttributeStyles;

class CardAttributeTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_be_instatiated()
    {
        $attribute = new CardAttribute();

        $this->assertInstanceOf(CardAttribute::class, $attribute);
    }

    public function test_it_supports_create_method()
    {
        $attribute = CardAttribute::create();

        $this->assertInstanceOf(CardAttribute::class, $attribute);
    }

    public function test_it_transforms_to_array()
    {
        $attribute = CardAttribute::create()
            ->label('foo')
            ->value('bar')
            ->style(CardAttributeStyles::GENERAL)
            ->url('http://example.com/baz')
            ->icon('http://example.com/qux', 'http://example.com/quux');

        $this->assertEquals([
            'value' => [
                'label' => 'bar',
                'style' => CardAttributeStyles::GENERAL,
                'url' => 'http://example.com/baz',
                'icon' => [
                    'url' => 'http://example.com/qux',
                    'url@2x' => 'http://example.com/quux',
                ],
            ],
            'label' => 'foo',
        ], $attribute->toArray());
    }

    public function test_it_transforms_to_array_being_partially_populated()
    {
        $attribute = CardAttribute::create()
            ->value('bar')
            ->icon('http://example.com/qux');

        $this->assertEquals([
            'value' => [
                'label' => 'bar',
                'icon' => [
                    'url' => 'http://example.com/qux',
                ],
            ],
        ], $attribute->toArray());
    }
}
