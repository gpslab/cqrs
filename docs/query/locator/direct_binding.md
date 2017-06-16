Direct binding locator
======================

Locator is needed for search the handler of handled query.

Direct binding locator is the simplest locator. You bind a specific handler to a specific query.

Example of binding:

```php
$handler = function (ContactByNameQuery $query) {
    // do something
};

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ContactByNameQuery::class, $handler);
```

More examples of binding in the section [Create handler](../handler.md).

The main problem with this locator is that you do not have the ability to implement a lazy load of the dependencies for
this handler. The second problem is the need to explicitly register all handlers, even if you need to handle only one
query at a run time.

Solve these problems will help:

* [PSR-11 Container locator](psr-11_container.md)
* [Symfony container locator](symfony_container.md)
