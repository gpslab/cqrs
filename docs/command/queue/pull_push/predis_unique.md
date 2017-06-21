Predis unique queue
===================

It works just like the [Predis queue](predis.md), but it only allows storing unique commands. All duplicates will
be ignored.

Configure queue:

```php
use GpsLab\Component\Command\Queue\PullPush\PredisUniqueCommandQueue;
use Symfony\Component\Serializer\Serializer;
use Predis\Client;

//$predis = new Client('tcp://10.0.0.1:6379'); // Predis client
//$serializer = new Serializer(); // Symfony serializer
//$logger = new Logger(); // PSR-3 logger
$queue_name = 'article_queue';
$format = 'json'; // default: predis
$queue = new PredisUniqueCommandQueue($predis, $serializer, $logger, $queue_name, $format);
```

Make command and push it into queue:

```php
$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->push($command);
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
