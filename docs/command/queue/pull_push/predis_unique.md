Predis unique queue
===================

It works just like the [Predis queue](predis.md), but it only allows storing unique commands. All duplicates will
be ignored.

Example usage:

```php
use GpsLab\Component\Command\Queue\PullPush\PredisUniqueCommandQueue;
use Symfony\Component\Serializer\Serializer;
use Predis\Client;

// configure queue
//$predis = new Client('tcp://10.0.0.1:6379'); // Predis client
//$serializer = new Serializer(); // Symfony serializer
//$logger = new Logger(); // PSR-3 logger
$queue_name = 'article_queue';
$format = 'json'; // default: predis
$queue = new PredisUniqueCommandQueue($predis, $serializer, $logger, $queue_name, $format);

// make command
$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->push($command);
```

In latter

```php
use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;
use GpsLab\Component\Command\Queue\PullPush\PredisUniqueCommandQueue;
use Symfony\Component\Serializer\Serializer;
use Predis\Client;

$locator = new DirectBindingCommandHandlerLocator();
$bus = new HandlerLocatedCommandBus($locator);

// configure queue
//$predis = new Client('tcp://10.0.0.1:6379'); // Predis client
//$serializer = new Serializer(); // Symfony serializer
//$logger = new Logger(); // PSR-3 logger
$queue_name = 'article_queue';
$format = 'json'; // default: predis
$queue = new PredisUniqueCommandQueue($predis, $serializer, $logger, $queue_name, $format);

while ($command = $queue->pull()) {
    $bus->handle($command);
}
```
