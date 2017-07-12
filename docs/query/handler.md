Query handler
===============

You can use any implementations of [callable type](http://php.net/manual/en/language.types.callable.php) as a query
handler.

The query handler can be a [anonymous function](http://php.net/manual/en/functions.anonymous.php):

```php
$handler = function (ContactByIdentityQuery $query) {
    // do something
};

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ContactByIdentityQuery::class, $handler);
```

It can be a some function:

```php
function ContactByIdentityHandler(ContactByIdentityQuery $query)
{
    // do something
}

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ContactByIdentityQuery::class, 'ContactByIdentityHandler');
```

It can be a [called object](http://php.net/manual/en/language.oop5.magic.php#object.invoke):

```php
class ContactByIdentityHandler
{
    public function __invoke(ContactByIdentityQuery $query)
    {
        // do something
    }
}

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ContactByIdentityQuery::class, new ContactByIdentityHandler());
```

It can be a static method of class:

```php
class ContactByIdentityHandler
{
    public static function handleContactByIdentity(ContactByIdentityQuery $query)
    {
        // do something
    }
}

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ContactByIdentityQuery::class, 'ContactByIdentityHandler::handleContactByIdentity');
```

It can be a public method of class:

```php
class ContactByIdentityHandler
{
    public function handleContactByIdentity(ContactByIdentityQuery $query)
    {
        // do something
    }
}

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ContactByIdentityQuery::class, [new ContactByIdentityHandler(), 'handleContactByIdentity']);
```

You can handle many querys in one handler.

```php
class ArticleHandler
{
    public function handleContactByIdentity(ContactByIdentityQuery $query)
    {
        // do something
    }

    public function handleContactByName(ContactByNameQuery $query)
    {
        // do something
    }
}

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ContactByIdentityQuery::class, [new ArticleHandler(), 'handleContactByIdentity']);
$locator->registerHandler(ContactByNameQuery::class, [new ArticleHandler(), 'handleContactByName']);
```

## Query handler locator

You can use exists locators of query handler:

 * [Direct binding locator](locator/direct_binding.md)
 * [PSR-11 container aware locator](locator/psr-11_container.md)
 * [Symfony container aware locator](locator/symfony_container.md)

Or you can create custom locator that implements `GpsLab\Component\Query\Handler\Locator\QueryHandlerLocator`
interface.
