Predis queue
============

Queues are designed to distribute the load and delay execution of commands or transfer their execution to separate
processes.

The queue stores events in [Redis](https://redis.io/), using the [Predis](https://github.com/nrk/predis) library to
access it.

Predis must be installed separately with [Composer](http://packagist.org):

```
composer require superbalist/php-pubsub-redis
```

This queue uses a [serializer](https://symfony.com/doc/current/components/serializer.html) to convert command objects
to strings and back while waiting for the transport of objects across the Redis. The serializer uses the `predis`
format as a default. You can change format if you need. You can make messages more optimal for a Redis than JSON.

If the message could not be deserialized, then a critical message is written to the log so that the administrator can
react quickly to the problem and the message is placed again at the end of the queue, so as not to lose it.

You can use any implementations of [callable type](http://php.net/manual/en/language.types.callable.php) as a queue
subscriber.

Example usage:

```php
use GpsLab\Component\Command\Queue\PubSub\PredisCommandQueue;
use Symfony\Component\Serializer\Serializer;
use Superbalist\PubSub\Redis\RedisPubSubAdapter;
use Predis\Client;

// configure queue
//$predis = new RedisPubSubAdapter(new Client('tcp://10.0.0.1:6379')); // Predis client
//$serializer = new Serializer(); // Symfony serializer
//$logger = new Logger(); // PSR-3 logger
$queue_name = 'article_queue';
$format = 'json'; // default: predis
$queue = new PredisCommandQueue($predis, $serializer, $logger, $queue_name, $format);


$command = new RenameArticleCommand();
$command->new_name = $new_name;

$queue->publish($command);
```

In latter

```php
use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;
use GpsLab\Component\Command\Queue\PubSub\PredisCommandQueue;
use Symfony\Component\Serializer\Serializer;
use Superbalist\PubSub\Redis\RedisPubSubAdapter;
use Predis\Client

$locator = new DirectBindingCommandHandlerLocator();
$bus = new HandlerLocatedCommandBus($locator);

// configure queue
//$predis = new RedisPubSubAdapter(new Client('tcp://10.0.0.1:6379')); // Predis client
//$serializer = new Serializer(); // Symfony serializer
//$logger = new Logger(); // PSR-3 logger
$queue_name = 'article_queue';
$format = 'json'; // default: predis
$queue = new PredisCommandQueue($predis, $serializer, $logger, $queue_name, $format);


$queue->subscribe(function(RenameArticleCommand $command) use ($bus) {
    $bus->handle($command);
});
```
