Memory unique queue
===================

It works just like the [Memory queue](memory.md), but it only allows storing unique commands. All duplicates will
be ignored.

### What is it for

A queue of unique commands is needed when you need to perform an action once after a domain event. But since there can
be many domain events, and the action must be performed only once, you need to remove all duplicate actions from the
queue.

Examples:

* **Action:** Update a block of popular articles
* **Event:** View article

Views come regularly, and the block needs to be updated no more often than 10 minutes. If there are no views, then
there is no need to update the block.

### Usage

```php
use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;
use GpsLab\Component\Command\Queue\Pull\MemoryUniquePullCommandQueue;

$locator = new DirectBindingCommandHandlerLocator();
$bus = new HandlerLocatedCommandBus($locator);
$queue = new MemoryUniquePullCommandQueue();

$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->publish($command);
```

In latter

```php
while ($command = $queue->pull()) {
    $bus->handle($command);
}
```
