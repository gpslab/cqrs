Direct binding locator
======================

Locator is needed for search the handler of handled command.

Direct binding locator is the simplest locator. You bind a specific handler to a specific command.

Example of binding:

```php
$handler = static function (RenameArticleCommand $command): void {
    // do something
};

// register command handler in handler locator
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, $handler);
```

More examples of binding in the section [Create handler](../handler.md).

## Subscriber

Example register a subscriber as a command handler:

```php
class ArticleCommandSubscriber implements CommandSubscriber
{
    public static function getSubscribedCommands(): array
    {
        return [
            RenameArticleCommand::class => 'handleRename',
        ];
    }

    public function handleRename(RenameArticleCommand $command): void
    {
        // do something
    }
}

// register command subscriber in handler locator
$subscriber = new ArticleCommandSubscriber();
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerSubscriber($subscriber);
```

## Problem

The main problem with this locator is that you do not have the ability to implement a lazy load of the dependencies for
this handler. The second problem is the need to explicitly register all handlers, even if you need to handle only one
command at a run time.

Solve these problems will help:

* [PSR-11 Container locator](psr-11_container.md)
* [Symfony container locator](symfony_container.md)
