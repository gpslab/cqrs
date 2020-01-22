Symfony container locator
=========================

Locator is needed for search the handler of handled query.

It's a implementation of locator `QueryHandlerLocator` for
[Symfony container](https://github.com/symfony/dependency-injection).

> **Note**
>
> Symfony 3.3 [implements](http://symfony.com/blog/new-in-symfony-3-3-psr-11-containers) a [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md).
> If you are using version Symfony 3.3 or later, you must use a [PSR-11 Container locator](psr-11_container.md).

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
```

YAML configuration for this:

```yml
services:
    acme.demo.query.handler.contact.by_name:
        class: ContactByNameHandler

    acme.demo.query.locator:
        class: GpsLab\Component\Query\Handler\Locator\SymfonyContainerQueryHandlerLocator
        calls:
            - [ setContainer, [ '@service_container' ] ]
            - [ registerService, [ 'ContactByNameQuery', 'acme.demo.query.handler.contact.by_name' ] ]
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
```

YAML configuration for this:

```yml
services:
    acme.demo.query.handler.contact.by_name:
        class: ContactByNameHandler

    acme.demo.query.locator:
        class: GpsLab\Component\Query\Handler\Locator\SymfonyContainerQueryHandlerLocator
        calls:
            - [ setContainer, [ '@service_container' ] ]
            - [ registerService, [ 'ContactByNameQuery', 'acme.demo.query.handler.contact.by_name', 'handleContactByName' ] ]
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
```

YAML configuration for this:

```yml
services:
    ContactQuerySubscriber: ~

        class: GpsLab\Component\Query\Handler\Locator\SymfonyContainerQueryHandlerLocator
        calls:
            - [ setContainer, [ '@service_container' ] ]
            - [ registerSubscriberService, [ 'ContactQuerySubscriber', 'ContactQuerySubscriber' ] ]
```

## Tagging

You can [tagged](https://symfony.com/doc/current/service_container/tags.html) query handler services for optimize
register the services in query locator. You can autoconfigure your subscribers and automatically register it in
locator like that:

```php
// src/Kernel.php
class Kernel extends BaseKernel
{
    protected function build(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(QuerySubscriber::class)
            ->addTag('gpslab.query.subscriber')
        ;

        $locator = $container->findDefinition(SymfonyContainerQueryHandlerLocator::class);

        $tagged_subscribers = $container->findTaggedServiceIds('gpslab.query.subscriber');

        foreach ($tagged_subscribers as $id => $attributes) {
            $subscriber = $container->findDefinition($id);
            $locator->addMethodCall('registerSubscriberService', [$id, $subscriber->getClass()]);
        }
    }
}
```
