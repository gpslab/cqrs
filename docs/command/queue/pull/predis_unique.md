Predis unique queue
===================

It works just like the [Predis queue](predis.md), but it only allows storing unique commands. All duplicates will
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

Configure queue:

```php
use GpsLab\Component\Command\Queue\Pull\PredisUniquePullCommandQueue;
use Symfony\Component\Serializer\Serializer;
use Predis\Client;

//$predis = new Client('tcp://10.0.0.1:6379'); // Predis client
//$serializer = new Serializer(); // Symfony serializer
//$logger = new Logger(); // PSR-3 logger
$queue_name = 'article_queue';
$format = 'json'; // default: predis
$queue = new PredisUniquePullCommandQueue($predis, $serializer, $logger, $queue_name, $format);
```

Make command and publish it into queue:

```php
$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->publish($command);
```

In latter pull commands from queue:

```php
use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;

$locator = new DirectBindingCommandHandlerLocator();
$bus = new HandlerLocatedCommandBus($locator);

while ($command = $queue->pull()) {
    $bus->handle($command);
}
```
