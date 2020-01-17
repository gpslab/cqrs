Executing queue
===============

Queues are designed to distribute the load and delay execution of commands or transfer their execution to separate
processes.

The handler signed to the command from the executing queue immediately receives the command as it published the queue.
Commands are not stored in the queue.

You can use any implementations of [callable type](http://php.net/manual/en/language.types.callable.php) as a queue
subscriber.

```php
use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;
use GpsLab\Component\Command\Queue\Subscribe\ExecutingSubscribeCommandQueue;

$locator = new DirectBindingCommandHandlerLocator();
$bus = new HandlerLocatedCommandBus($locator);
$queue = new ExecutingSubscribeCommandQueue();
```

Subscribe to the queue:

```php
$handler = static function(RenameArticleCommand $command) use ($bus): void {
    $bus->handle($command);
};

$queue->subscribe($handler);
```

You can unsubscribe of the queue:

```php
$queue->unsubscribe($handler);
```

Make command and publish it into queue:

```php
$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->publish($command);
```
