<?php

namespace NotificationChannels\HipChat\Test;

use NotificationChannels\HipChat\HipChatMessage;

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
        $message = (new HipChatMessage)
            ->room('Room');

        $this->assertEquals('Room', $message->room);
    }

    public function test_it_can_set_from()
    {
        $message = (new HipChatMessage)
            ->from('Bar');

        $this->assertEquals('Bar', $message->from);
    }

    public function test_it_can_set_text_content()
    {
        $message = (new HipChatMessage)
            ->content('Foo Bar');

        $this->assertEquals('Foo Bar', $message->content);
    }

    public function test_it_can_set_html_content()
    {
        $message = (new HipChatMessage)
            ->content('<strong>Foo</strong> Bar');

        $this->assertEquals('<strong>Foo</strong> Bar', $message->content);
    }

    public function test_it_can_set_color()
    {
        $message = (new HipChatMessage)
            ->color('yellow');

        $this->assertEquals('yellow', $message->color);
    }

    public function test_it_can_set_text_format()
    {
        $message = (new HipChatMessage)
            ->text();

        $this->assertEquals('text', $message->format);
    }

    public function test_it_can_set_html_format()
    {
        $message = (new HipChatMessage)
            ->html();

        $this->assertEquals('html', $message->format);
    }

    public function test_it_can_set_notify_flag()
    {
        $message = (new HipChatMessage)
            ->notify();

        $this->assertTrue($message->notify);

        $message->notify(false);

        $this->assertFalse($message->notify);
    }

    public function test_it_can_set_info_level()
    {
        $message = (new HipChatMessage)
            ->info();

        $this->assertEquals('info', $message->level);
        $this->assertEquals('gray', $message->color);
    }

    public function test_it_can_set_success_level()
    {
        $message = (new HipChatMessage)
            ->success();

        $this->assertEquals('success', $message->level);
        $this->assertEquals('green', $message->color);
    }

    public function test_it_can_set_error_level()
    {
        $message = (new HipChatMessage)
            ->error();

        $this->assertEquals('error', $message->level);
        $this->assertEquals('red', $message->color);
    }

    public function test_it_transforms_to_array()
    {
        $message = (new HipChatMessage)
            ->from('Bar')
            ->error()
            ->html()
            ->content('<strong>Foo</strong>')
            ->notify();

        $this->assertEquals([
            'from' => 'Bar',
            'message_format' => 'html',
            'color' => 'red',
            'notify' => true,
            'message' => '<strong>Foo</strong>',
        ], $message->toArray());
    }
}
