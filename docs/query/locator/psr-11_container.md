PSR-11 Container locator
========================

Locator is needed for search the handler of handled query.

It's a implementation of locator `QueryHandlerLocator` for
[PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) container.

## Anonymous function

Example register the [anonymous function](http://php.net/manual/en/functions.anonymous.php) as a query handler:

```php
$handler = static function (ContactByNameQuery $query) {
    // do something
};

// example of register handler in PSR-11 container
// container on request $container->get('acme.demo.query.handler.contact.by_name') must return $handler
//$container = new Container();
//$container->set('acme.demo.query.handler.contact.by_name', $handler);

// register query handler service in handler locator
$locator = new ContainerQueryHandlerLocator($container);
$locator->registerService(ContactByNameQuery::class, 'acme.demo.query.handler.contact.by_name');
```

## Called object

Example register the [called object](http://php.net/manual/en/language.oop5.magic.php#object.invoke) as a query handler:

```php
class ContactByNameHandler
{
    public function __invoke(ContactByNameQuery $query)
    {
        // do something
    }
}

// example of register handler in PSR-11 container
// container on request $container->get('acme.demo.query.handler.contact.by_name') must return handler
//$container = new Container();
//$container->set('acme.demo.query.handler.contact.by_name', new ContactByNameHandler());

// register query handler service in handler locator
$locator = new ContainerQueryHandlerLocator($container);
$locator->registerService(ContactByNameQuery::class, 'acme.demo.query.handler.contact.by_name');
```

## Method of class

Example register the public method of class as a query handler:

```php
class ContactByNameHandler
{
    public function handleContactByName(ContactByNameQuery $query)
    {
        // do something
    }
}

// example of register handler in PSR-11 container
// container on request $container->get('acme.demo.query.handler.contact.by_name') must return handler
//$container = new Container();
//$container->set('acme.demo.query.handler.contact.by_name', new ContactByNameHandler());

// register query handler service in handler locator
$locator = new ContainerQueryHandlerLocator($container);
$locator->registerService(ContactByNameQuery::class, 'acme.demo.query.handler.contact.by_name', 'handleContactByName');
```

## Subscriber

Example register a subscriber as a command handler:

```php
class ContactQuerySubscriber implements QuerySubscriber
{
    public static function getSubscribedQueries(): array
    {
        return [
            ContactByNameQuery::class => 'getByNameQuery',
        ];
    }

    public function getByNameQuery(ContactByNameQuery $query)
    {
        // return some data
    }
}

// example of register subscriber in PSR-11 container
// container on request $container->get('acme.demo.query.subscriber.contact') must return subscriber
//$container = new Container();
//$container->set('acme.demo.query.subscriber.contact', new ContactQuerySubscriber());

// register query handler service in handler locator
$locator = new ContainerQueryHandlerLocator($container);
$locator->registerSubscriberService('acme.demo.query.subscriber.contact', ContactQuerySubscriber::class);
```
