# SteamID Converter
A simple SteamID converter library for using in any project.

## Author
- [CrazyHackGUT aka Kruzya](https://kruzya.me) - Developer/Maintainer

## Install
`composer require kruzya/steam-id-converter`

## Example
```php
<?php

// we're think you're already loaded
// autoloader from Composer.
$steam = new \Kruzya\SteamIdConverter\SteamID("STEAM_0:0:55665612");

// How you can just call object methods
// for get SteamID in any another formats.
```
|          Method         |        Result        |
|-------------------------|----------------------|
| `$steam->v3()`          | `[U:1:111331224]`    |
| `$steam->accountId()`   | `111331224`          |
| `$steam->communityId()` | `76561198071596952`  |
| `$steam->v2(0)`         | `STEAM_0:0:55665612` |
| `$steam->v2(1)`         | `STEAM_1:0:55665612` |
| `$steam->v2WithoutX()`  | `0:55665612`         |

### License
MIT
