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

> **Note**
>
> You can [tagged](https://symfony.com/doc/current/service_container/tags.html) query handler services for optimize
> register the services in query locator.
