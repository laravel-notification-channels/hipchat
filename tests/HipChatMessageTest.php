<?php

namespace NotificationChannels\HipChat\Test;

use NotificationChannels\HipChat\Card;
use NotificationChannels\HipChat\CardStyles;
use NotificationChannels\HipChat\HipChatMessage;
use NotificationChannels\HipChat\MessageColors;

class HipChatMessageTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_be_instantiated()
    {
        $message = new HipChatMessage;

        $this->assertInstanceOf(HipChatMessage::class, $message);
    }

    public function test_it_can_accept_content_when_created()
    {
        $message = new HipChatMessage('Foo');

        $this->assertEquals('Foo', $message->content);
    }

    public function test_it_supports_create_method()
    {
        $message = HipChatMessage::create('Foo');

        $this->assertInstanceOf(HipChatMessage::class, $message);
        $this->assertEquals('Foo', $message->content);
    }

    public function test_it_sets_proper_defaults_when_instantiated()
    {
        $message = new HipChatMessage;

        $this->assertEquals('text', $message->format);
        $this->assertEquals('info', $message->level);
        $this->assertEquals('gray', $message->color);

        $this->assertFalse($message->notify);
    }

    public function test_it_can_set_room()
    {
        $message = HipChatMessage::create()
            ->room('Room');

        $this->assertEquals('Room', $message->room);
    }

    public function test_it_can_set_from()
    {
        $message = HipChatMessage::create()
            ->from('Bar');

        $this->assertEquals('Bar', $message->from);
    }

    public function test_it_can_set_text_content()
    {
        $message = HipChatMessage::create()
            ->content('Foo Bar');

        $this->assertEquals('Foo Bar', $message->content);
    }

    public function test_it_can_set_html_content()
    {
        $message = HipChatMessage::create()
            ->content('<strong>Foo</strong> Bar');

        $this->assertEquals('<strong>Foo</strong> Bar', $message->content);
    }

    public function test_it_can_set_color()
    {
        $message = HipChatMessage::create()
            ->color(MessageColors::YELLOW);

        $this->assertEquals(MessageColors::YELLOW, $message->color);
    }

    public function test_it_can_set_text_format_without_affecting_content()
    {
        $message = HipChatMessage::create()
            ->content('Foo')
            ->text();

        $this->assertEquals('text', $message->format);
        $this->assertEquals('Foo', $message->content);
    }

    public function test_it_can_set_text_format_along_with_content()
    {
        $message = HipChatMessage::create()
            ->content('Foo')
            ->text('Bar');

        $this->assertEquals('text', $message->format);
        $this->assertEquals('Bar', $message->content);
    }

    public function test_it_can_set_html_format_without_affecting_content()
    {
        $message = HipChatMessage::create()
            ->content('<strong>Foo</strong>')
            ->html();

        $this->assertEquals('html', $message->format);
        $this->assertEquals('<strong>Foo</strong>', $message->content);
    }

    public function test_it_can_set_html_format_along_with_content()
    {
        $message = HipChatMessage::create()
            ->content('<strong>Foo</strong>')
            ->html('<strong>Bar</strong>');

        $this->assertEquals('html', $message->format);
        $this->assertEquals('<strong>Bar</strong>', $message->content);
    }

    public function test_it_can_set_notify_flag()
    {
        $message = HipChatMessage::create()
            ->notify();

        $this->assertTrue($message->notify);

        $message->notify(false);

        $this->assertFalse($message->notify);
    }

    public function test_it_can_set_info_level()
    {
        $message = HipChatMessage::create()
            ->info();

        $this->assertEquals('info', $message->level);
        $this->assertEquals(MessageColors::GRAY, $message->color);
    }

    public function test_it_can_set_success_level()
    {
        $message = HipChatMessage::create()
            ->success();

        $this->assertEquals('success', $message->level);
        $this->assertEquals(MessageColors::GREEN, $message->color);
    }

    public function test_it_can_set_error_level()
    {
        $message = HipChatMessage::create()
            ->error();

        $this->assertEquals('error', $message->level);
        $this->assertEquals(MessageColors::RED, $message->color);
    }

    public function test_it_can_set_card()
    {
        $message = HipChatMessage::create()
            ->card($card = Card::create());

        $this->assertEquals($card, $message->card);
    }

    public function test_it_can_set_card_with_closure()
    {
        $message = HipChatMessage::create()
            ->card(function (Card $card) {
                $card->title('foo');
            });

        $this->assertEquals('foo', $message->card->title);
    }

    public function test_it_sets_attach_to()
    {
        $message = HipChatMessage::create()
            ->attachTo($id = str_random());

        $this->assertEquals($id, $message->attachTo);
    }

    public function test_it_transforms_to_array_with_card()
    {
        $message = HipChatMessage::create()
            ->from('Bar')
            ->error()
            ->html('<strong>Foo</strong>')
            ->notify()
            ->card($card = Card::create()
                ->id(str_random())
                ->title('Card title')
                ->style(CardStyles::APPLICATION)
                ->text('Card content')
            )
            ->attachTo($id = str_random());

        $this->assertEquals([
            'from' => 'Bar',
            'message_format' => 'html',
            'color' => MessageColors::RED,
            'notify' => true,
            'message' => '<strong>Foo</strong>',
            'attach_to' => $id,
            'card' => $card->toArray(),
        ], $message->toArray());
    }
}
