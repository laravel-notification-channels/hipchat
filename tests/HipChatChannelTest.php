<?php

namespace NotificationChannels\HipChat\Test;

use Illuminate\Notifications\Notifiable;
use Mockery;
use NotificationChannels\HipChat\Exceptions\CouldNotSendNotification;
use NotificationChannels\HipChat\HipChat;
use Illuminate\Notifications\Notification;
use NotificationChannels\HipChat\HipChatFile;
use NotificationChannels\HipChat\HipChatChannel;
use NotificationChannels\HipChat\HipChatMessage;
use stdClass;

class HipChatChannelTest extends TestCase
{
    public function test_it_can_be_instantiated()
    {
        $hipChat = Mockery::mock(HipChat::class);
        $this->assertInstanceOf(HipChatChannel::class, new HipChatChannel($hipChat));
    }

    public function test_it_can_send_message()
    {
        $hipChat = Mockery::mock(HipChat::class);
        $hipChat->shouldReceive('sendMessage')
            ->once()
            ->with(
                'room',
                ['message_format' => 'text', 'color' => 'gray', 'message' => 'Foo']
            );

        $channel = new HipChatChannel($hipChat);
        $channel->send(new TestNotifiable(), new TestMessageNotification());
    }

    public function test_it_can_share_file()
    {
        $hipChat = Mockery::mock(HipChat::class);
        $hipChat->shouldReceive('shareFile')
            ->once()
            ->with(
                'room',
                ['content' => 'foo', 'filename' => 'foo.txt', 'file_type' => '', 'message' => '']
            );

        $channel = new HipChatChannel($hipChat);
        $channel->send(new TestNotifiable(), new TestFileNotification());
    }

    public function test_it_throws_exception_if_invalid_message_given()
    {
        $hipChat = Mockery::mock(HipChat::class);
        $hipChat->shouldReceive('sendMessage')->once();

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage(
            CouldNotSendNotification::invalidMessageObject(new stdClass())->getMessage()
        );


        $channel = new HipChatChannel($hipChat);
        $channel->send(new TestNotifiable(), new TestInvalidNotification());
    }

    public function test_it_throws_exception_if_room_is_missing()
    {
        $hipChat = Mockery::mock(HipChat::class);
        $hipChat->shouldReceive('sendMessage')->once();
        $hipChat->shouldReceive('room')
            ->once()
            ->andReturn(null);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage(CouldNotSendNotification::missingTo()->getMessage());

        $channel = new HipChatChannel($hipChat);
        $channel->send(new TestNotifiable(null), new TestMessageNotification());
    }

    protected function prepare()
    {
        $hipChat = Mockery::mock(HipChat::class);
        $channel = new HipChatChannel($hipChat);
        $notifiable = new TestNotifiable;

        return [$channel, $hipChat, $notifiable];
    }
}

class TestNotifiable
{
    use Notifiable;

    public $room = 'room';

    public function __construct($room = 'room')
    {
        $this->room = $room;
    }

    public function routeNotificationForHipChat()
    {
        return $this->room;
    }
}

class TestMessageNotification extends Notification
{
    public function via($notifiable)
    {
        return [HipChatChannel::class];
    }

    public function toHipChat($notifiable)
    {
        return HipChatMessage::create('Foo');
    }
}

class TestFileNotification extends Notification
{
    public function via($notifiable)
    {
        return [HipChatChannel::class];
    }

    public function toHipChat($notifiable)
    {
        return HipChatFile::create()
            ->fileContent('foo')
            ->fileName('foo.txt');
    }
}

class TestInvalidNotification extends Notification
{
    public function via($notifiable)
    {
        return [HipChatChannel::class];
    }

    public function toHipChat($notifiable)
    {
        $object = new stdClass();
        $object->message = 'Foo';

        return $object;
    }
}
