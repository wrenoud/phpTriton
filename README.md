phpTriton
=========

A PHP client for the unpublished API for Neptune's Pride II: Triton (http://np.ironhelmet.com/)

Usage
-----

```php
require "phpTriton/client.php";

$client = new TritonClient($alias, $password);
if($client->authenticate()){
    $game = $client->GetGame($game_id);
    $universe = $game->GetFullUniverse();
}
```

Classes
-------

### `TritonClient($alias, $password)`

* `authenticate()` - authenticates the player credentials, return `true` on success
* `GetGame($game_id)` - returns `TritonGame` object
* `GetServer()` - returns `TritonServer` object

### `TritonServer($client)`

All methods return decoded JSON arrays.

* `GetPlayer()` - returns the player information
* `GetOpenGames()` - returns the open game information

### `TritonGame($client, $game_id)`

All methods return decoded JSON arrays.

* `GetFullUniverse()` - returns game universe information
* `GetIntel()` - returns intel statistics
* `GetUnreadCount()` - returns unread message counts
* `GetPlayerAchievements()` - returns game players achievement information
* `GetDiplomacyMessages($count, $offset = 0)` - returns diplomacy messages
* `GetEventMessages($count, $offset = 0)` - returns event messages
