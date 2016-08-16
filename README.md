# HipChat Notifications Channel for Laravel 5.3 [WIP]

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/hipchat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/hipchat)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/hipchat/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/hipchat)
[![StyleCI](https://styleci.io/repos/65714660/shield)](https://styleci.io/repos/65714660)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/1af9cfed-e62d-405a-b06d-9071d2f8bee8.svg?style=flat-square)](https://insight.sensiolabs.com/projects/1af9cfed-e62d-405a-b06d-9071d2f8bee8)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/hipchat.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/hipchat)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/hipchat/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/hipchat/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/hipchat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/hipchat)

This package makes it easy to send [HipChat notifications](https://www.hipchat.com) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the HipChat Service](#setting-up-the-hipchat-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/hipchat
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\HipChat\HipChatProvider::class,
],
```
### Setting up the HipChat service

Add your HipChat Account Token and optionally the default room and Hipchat API server's base url to your `config/services.php`:

```php
// config/services.php
...
'hipchat' => [
    'token' => env('HIPCHAT_TOKEN'),
    // Default room (optional)
    'room' => 'Notifications',
    // Base URL for Hipchat API server (optional)
    'url' => 'https://api.your.hipchat.server.com',
],
...
```

## Usage

### Sending a HipChat Message Notification
``` php
use NotificationChannels\HipChat\HipChatChannel;
use NotificationChannels\HipChat\HipChatMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification
{
    public function via($notifiable)
    {
        return [HipChatChannel::class];
    }

    public function toHipChat($notifiable)
    {
        return new HipChatMessage::create("<strong>A new user has registered!</strong>")
            ->room('New Registrations')
            ->sucess()
            ->notify()
            ->html();
    }
}
```

### Sharing a file in a HipChat room

In a lot of cases all you need just a path to an exisiting file you want to share
``` php
public function toHipChat($notifiable)
{
    return new HipChatFile::create($this->user->photo);
}
```

You can optionally send a text message along the way

``` php
public function toHipChat($notifiable)
{
    return new HipChatFile::create($this->user->photo);
        ->text('Look we've got a new user!');
}
```

If you need more control and/or you're creating the content of the file on the fly

``` php
public function toHipChat($notifiable)
{
    return new HipChatFile::create()
        ->fileName('user_photo.png')
        ->fileType('image/png')
        ->fileContent(fopen('http://example.com/user/photo/johndoe', 'r'))
        ->text('Look we've got a new user!');
}
```

### Available methods

#### HipChatMessage

- `room()`: Specifies the room (id or name) to send the notification to.
- `from()`: Sets the optional label to be shown in addition to the sender's name.
- `content()`: Sets a content of the notification message.
- `text()`: Sets the content format to plain text.
- `html()`: Sets the content format to html. Allowed HTML tags: a, b, i, strong, em, br, img, pre, code, lists, tables.
- `color()`: Sets the color for the message. Allowed values: yellow, green, red, purple, gray, random.
- `notify()`: Specifies if a message should trigger a user notification in a Hipchat client.
- `info()`: Sets notification level to `info` and color to `gray`.
- `success()`: Sets notification level to `success` and color to `green`.
- `error()`: Sets notification level to `info` and color to `red`.

### HipChatFile

- `room()`: Specifies the room (id or name) to share the file in.
- `path()`: Sets the `fileContent` to the resource of the existing file and tries to detect and set the `fileName` and `fileType` if they weren't explicitely set.
- `fileName`: Sets the name of the file.
- `fileContent`: Explicitely sets the content of the file. It can be a string, stream or a file resource. If a resource was passed it tries to detect and set the `fileType` if it wasn't explicitely set.
- `fileType`: Explicitely sets the content (mime) type of the file.
- `text()`: Sets a text message to be sent along with the file.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email pmatseykanets@gmail.com instead of using the issue tracker.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Peter Matseykanets](https://github.com/pmatseykanets)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
