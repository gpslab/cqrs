Memory queue
============

Queues are designed to distribute the load and delay execution of commands or transfer their execution to separate
processes.

Memory queue stores commands in an internal variable, which allows you to delay execution of commands at the end of the
script execution.

```php
use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;
use GpsLab\Component\Command\Queue\MemoryCommandQueue;

$locator = new DirectBindingCommandHandlerLocator();
$bus = new HandlerLocatedCommandBus($locator);
$queue = new MemoryCommandQueue();

$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->push($command);


// in latter
whele ($command = $queue->pop()) {
    $bus->handle($command);
}
```
