Query bus
=========

The query bus is designed to handle querys. It's a
[publish–subscribe](https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern) pattern.

You can use `HandlerLocatedQueryBus`. This bus use `QueryHandlerLocator` for search the handler for specific
query.

```php
use GpsLab\Component\Query\Bus\HandlerLocatedQueryBus;
use GpsLab\Component\Query\Handler\Locator\DirectBindingQueryHandlerLocator;

$locator = new DirectBindingQueryHandlerLocator();
$bus = new HandlerLocatedQueryBus($locator);

$query = new ContactByIdentity();
$query->id = $contact_id;

$data = $bus->handle($query);
```

You can create custom bus implementing the interface `QueryBus`.
