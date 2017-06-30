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
use GpsLab\Component\Command\Queue\PubSub\ExecutingCommandQueue;

$locator = new DirectBindingCommandHandlerLocator();
$bus = new HandlerLocatedCommandBus($locator);
$queue = new ExecutingCommandQueue();
```

Subscribe to the queue:

```php
$queue->subscribe(function(RenameArticleCommand $command) use ($bus) {
    $bus->handle($command);
});
```

Make command and publish it into queue:

```php
$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->publish($command);
```
