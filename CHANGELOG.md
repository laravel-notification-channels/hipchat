# Changelog

## [2.2.0](https://github.com/laravel-notification-channels/hipchat/releases/tag/v2.2.0) - 2018-11-21

### Added

- Streamline dependency constraints and add support for Laravel 5.7+

## [2.1.0](https://github.com/laravel-notification-channels/hipchat/releases/tag/v2.1.0) - 2018-07-06

### Added

- Added more test coverage

### Fixed

- Fixed issues with handling falsey string input

## [2.0.0](https://github.com/laravel-notification-channels/hipchat/releases/tag/v2.0.0) - 2018-02-12

### Added

- Added Laravel 5.6 compatibility.

## [1.0.0](https://github.com/laravel-notification-channels/hipchat/releases/tag/1.0.0) - 2017-09-02

### Added

- Added Laravel 5.5 support including package auto discovery.

## [0.2.0](https://github.com/laravel-notification-channels/hipchat/releases/tag/0.2.0) - 2017-01-30

### Added

- Added Laravel 5.4 support.

## [0.1.0](https://github.com/laravel-notification-channels/hipchat/releases/tag/0.1.0) - 2016-08-24

### Added

- Added HipChat card support to `HipChatMessage`. See https://developer.atlassian.com/hipchat/guide/sending-messages#SendingMessages-UsingCards.
- Added tests for `HipChatChannel`.
- Added `attachTo` method to `HipChatMessage`
- Added `MessageColors` inteface that lists all supported colors.

### Changed

- Switched to the stable 5.3 version of Laravel.
- Allowed usage of the laravel-notification-channels/backport package, to use this notification channel with Laravel 5.1 and 5.2.
- Updated tests.

## [0.0.4](https://github.com/laravel-notification-channels/hipchat/releases/tag/0.0.4) - 2016-08-22

### Added

- Added file sharing capability.

### Changed

- Allow set the content when using `text()` or `html()` on `HipChatMessage`. 

## [0.0.3](https://github.com/laravel-notification-channels/hipchat/releases/tag/0.0.3) - 2016-08-15

#### Fixed

- Fixed config parameters casing.

## [0.0.2](https://github.com/laravel-notification-channels/hipchat/releases/tag/0.0.2) - 2016-08-15

#### Added

- Added `create` method for `HipChatMessage`.

## [0.0.1](https://github.com/laravel-notification-channels/hipchat/releases/tag/0.0.1) - 2016-08-15

- Experimental release
