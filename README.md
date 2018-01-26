# maxmd-message

This library is an adaptation of the api provided by maxmd. The idea is to make it dead simple to build an inbox using php.

# Authentication

```php
use Endeavors\MaxMD\Message\User;

...

User::login("your@domain.direct.eval.md", "password");
```

# Viewing A Message

To view a message, simply create a folder instance and send the uid of the message to the View method:

```php
use Endeavors\MaxMD\Message\Folder;

$folder = Folder::create("Inbox");

$message = $folder->Messages()->View(1);
```

# Viewing All Messages

The All method returns a simple array of objects

```php
use Endeavors\MaxMD\Message\Folder;

$folder = Folder::create("Inbox");

$message = $folder->Messages()->All();
```
