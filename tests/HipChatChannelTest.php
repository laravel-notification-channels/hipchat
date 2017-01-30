<?php

namespace NotificationChannels\HipChat\Test;

use Mockery;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\HipChat\HipChat;
use Illuminate\Notifications\Notification;
use NotificationChannels\HipChat\HipChatFile;
use NotificationChannels\HipChat\HipChatChannel;
use NotificationChannels\HipChat\HipChatMessage;

class HipChatChannelTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_can_be_instantiated()
    {
        list($channel) = $this->prepare();

        $this->assertInstanceOf(HipChatChannel::class, $channel);
    }

    public function test_it_shares_message()
    {
        list($channel, $hipChat, $notifiable) = $this->prepare();

        $notification = new TestMessageNotification;

        $hipChat->shouldReceive('sendMessage')->once();

        $channel->send($notifiable, $notification);
    }

    public function test_it_shares_file()
    {
        list($channel, $hipChat, $notifiable) = $this->prepare();

        $notification = new TestFileNotification;

        $hipChat->shouldReceive('shareFile')->once();

        $channel->send($notifiable, $notification);
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

    public function routeNotificationForHipChat()
    {
        return 'room';
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
