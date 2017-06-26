Predis queue
============

Queues are designed to distribute the load and delay execution of commands or transfer their execution to separate
processes.

The queue stores events in [Redis](https://redis.io/), using the [Predis](https://github.com/nrk/predis) library to
access it.

Predis must be installed separately with [Composer](http://packagist.org):

```
composer require predis/predis
```

This queue uses a [serializer](https://symfony.com/doc/current/components/serializer.html) to convert command objects
to strings and back while waiting for the transport of objects across the Redis. The serializer uses the `predis`
format as a default. You can change format if you need. You can make messages more optimal for a Redis than JSON.

If the message could not be deserialized, then a critical message is written to the log so that the administrator can
react quickly to the problem and the message is placed again at the end of the queue, so as not to lose it.

Configure queue:

```php
use GpsLab\Component\Command\Queue\Pull\PredisPullCommandQueue;
use Symfony\Component\Serializer\Serializer;
use Predis\Client;

//$predis = new Client('tcp://10.0.0.1:6379'); // Predis client
//$serializer = new Serializer(); // Symfony serializer
//$logger = new Logger(); // PSR-3 logger
$queue_name = 'article_queue';
$format = 'json'; // default: predis
$queue = new PredisPullCommandQueue($predis, $serializer, $logger, $queue_name, $format);
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
