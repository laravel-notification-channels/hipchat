# Changelog

All notable changes to `laravel-notification-channels/hipchat` will be documented in this file

## [Unreleased]
## Added
- Added HipChat card support to `HipChatMessage`. See https://developer.atlassian.com/hipchat/guide/sending-messages#SendingMessages-UsingCards.
- Added tests for `HipChatChannel`.
- Added `attachTo` method to `HipChatMessage`
- Added `MessageColors` inteface that lists all supported by HipChat colors.

## Changed
- Switched to the stable 5.3 version of Laravel.
- Allowed usage of the laravel-notification-channels/backport package, to use this notification channel with Laravel 5.1 and 5.2.
- Updated tests.

## [0.0.4] - 2016-08-22
### Added
- Added file sharing capability.

### Changed
- Allow set the content when using `text()` or `html()` on `HipChatMessage`. 

## [0.0.3] - 2016-08-15
#### Fixed
- Fixed config parameters casing.

## [0.0.2] - 2016-08-15
#### Added
- Added `create` method for `HipChatMessage`.

## [0.0.1] - 2016-08-15
- Experimental release
