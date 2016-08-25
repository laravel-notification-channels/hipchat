<?php

namespace NotificationChannels\HipChat\Test;

use NotificationChannels\HipChat\Card;
use NotificationChannels\HipChat\CardAttribute;
use NotificationChannels\HipChat\CardAttributeStyles;
use NotificationChannels\HipChat\CardFormats;
use NotificationChannels\HipChat\CardStyles;

class CardTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_be_instantiated()
    {
        $card = new Card();

        $this->assertInstanceOf(Card::class, $card);
    }

    public function test_it_supports_create_method()
    {
        $card = Card::create('foo');

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals('foo', $card->title);
        $this->assertNotEmpty($card->id);
    }

    public function test_it_sets_title()
    {
        $card = Card::create()
            ->title('foo');

        $this->assertEquals('foo', $card->title);
    }

    public function test_it_sets_id()
    {
        $card = Card::create()
            ->id($id = str_random());

        $this->assertEquals($id, $card->id);
    }

    public function test_it_sets_style()
    {
        $card = Card::create()
            ->style(CardStyles::APPLICATION);

        $this->assertEquals(CardStyles::APPLICATION, $card->style);
    }

    public function test_it_trims_and_sets_content()
    {
        $card = Card::create()
            ->content("\t  foo\n");

        $this->assertEquals('foo', $card->content);
    }

    public function test_it_can_set_text_format_without_affecting_content()
    {
        $card = Card::create()
            ->content('Foo')
            ->text();

        $this->assertEquals('text', $card->format);
        $this->assertEquals('Foo', $card->content);
    }

    public function test_it_can_set_text_format_along_with_content()
    {
        $card = Card::create()
            ->content('Foo')
            ->text('Bar');

        $this->assertEquals('text', $card->format);
        $this->assertEquals('Bar', $card->content);
    }

    public function test_it_can_set_html_format_without_affecting_content()
    {
        $card = Card::create()
            ->content('<strong>Foo</strong>')
            ->html();

        $this->assertEquals('html', $card->format);
        $this->assertEquals('<strong>Foo</strong>', $card->content);
    }

    public function test_it_can_set_html_format_along_with_content()
    {
        $card = Card::create()
            ->content('<strong>Foo</strong>')
            ->html('<strong>Bar</strong>');

        $this->assertEquals('html', $card->format);
        $this->assertEquals('<strong>Bar</strong>', $card->content);
    }

    public function test_it_sets_card_format()
    {
        $card = Card::create()
            ->cardFormat(CardFormats::COMPACT);

        $this->assertEquals(CardFormats::COMPACT, $card->cardFormat);
    }

    public function test_it_trims_and_sets_url()
    {
        $card = Card::create()
            ->url("\t  http://example.com/foo \n");

        $this->assertEquals('http://example.com/foo', $card->url);
    }

    public function test_it_sets_thumbnail()
    {
        $card = Card::create()
            ->thumbnail(
                ' http://example.com/foo ',
                ' http://example.com/bar ',
                120,
                120
            );

        $this->assertEquals('http://example.com/foo', $card->thumbnail);
        $this->assertEquals('http://example.com/bar', $card->thumbnail2);
        $this->assertEquals(120, $card->thumbnailWidth);
        $this->assertEquals(120, $card->thumbnailHeight);
    }

    public function test_it_sets_activity()
    {
        $card = Card::create()
            ->activity(
                ' <b>Foo</b> ',
                ' http://example.com/foo ',
                ' http://example.com/bar '
            );

        $this->assertEquals('<b>Foo</b>', $card->activity);
        $this->assertEquals('http://example.com/foo', $card->activityIcon);
        $this->assertEquals('http://example.com/bar', $card->activityIcon2);
    }

    public function test_it_can_add_attribute()
    {
        $card = Card::create()
            ->addAttribute($attribute = CardAttribute::create());

        $this->assertCount(1, $card->attributes);
        $this->assertEquals($attribute, $card->attributes[0]);
    }

    public function test_it_can_add_attribute_with_closure()
    {
        $card = Card::create()
            ->addAttribute(function (CardAttribute $attribute) {
                $attribute->value('foo')
                    ->label('bar');
            });

        $this->assertCount(1, $card->attributes);
        $this->assertEquals('foo', $card->attributes[0]->value);
        $this->assertEquals('bar', $card->attributes[0]->label);
    }

    public function test_it_transforms_to_array()
    {
        $card = Card::create()
            ->id($id = str_random())
            ->title('Card title')
            ->style(CardStyles::APPLICATION)
            ->text('Card content')
            ->cardFormat(CardFormats::COMPACT)
            ->url('http://example.com/card/url')
            ->thumbnail(
                ' http://example.com/thumbnail ',
                ' http://example.com/thumbnail2 ',
                120,
                120
            )
            ->activity(
                ' <b>Activity content</b> ',
                ' http://example.com/activity ',
                ' http://example.com/activity2 '
            )
            ->icon('http://example.com/icon', 'http://example.com/icon2')
            ->addAttribute($attribute = CardAttribute::create()
                ->value('Attribute value')
                ->label('Attribute label')
                ->style(CardAttributeStyles::GENERAL)
                ->url(' http://example.com/attribute ')
                ->icon(' http://example.com/attribute/icon ', 'http://example.com/attribute/icon2 ')
            );

        $this->assertEquals([
            'id' => $id,
            'title' => 'Card title',
            'style' => CardStyles::APPLICATION,
            'format' => CardFormats::COMPACT,
            'description' => [
                'value' => 'Card content',
                'format' => 'text',
            ],
            'url' => 'http://example.com/card/url',
            'thumbnail' => [
                'url' => 'http://example.com/thumbnail',
                'url@2x' => 'http://example.com/thumbnail2',
                'width' => 120,
                'height' => 120,
            ],
            'activity' => [
                'html' => '<b>Activity content</b>',
                'icon' => [
                    'url' => 'http://example.com/activity',
                    'url@2x' => 'http://example.com/activity2'
                ],
            ],
            'icon' => [
                'url' => 'http://example.com/icon',
                'url@2x' => 'http://example.com/icon2'
            ],
            'attributes' => [$attribute->toArray()],
        ], $card->toArray());
    }

    public function test_it_transforms_to_array_being_partially_populated()
    {
        $card = Card::create()
            ->id($id = str_random())
            ->title('Card title')
            ->style(CardStyles::APPLICATION)
            ->text('Card content');

        $this->assertEquals([
            'id' => $id,
            'title' => 'Card title',
            'style' => CardStyles::APPLICATION,
            'description' => [
                'value' => 'Card content',
                'format' => 'text',
            ],
        ], $card->toArray());
    }
}
