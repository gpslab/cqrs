PSR-11 Container locator
========================

Locator is needed for search the handler of handled command.

It's a implementation of locator `CommandHandlerLocator` for
[PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) container.

## Anonymous function

Example register the [anonymous function](http://php.net/manual/en/functions.anonymous.php) as a command handler:

```php
$handler = static function (RenameArticleCommand $command): void {
    // do something
};

// example of register handler in PSR-11 container
// container on request $container->get('acme.demo.command.handler.article.rename') must return handler
//$container = new Container();
//$container->set('acme.demo.command.handler.article.rename', $handler);

// register command handler service in handler locator
$locator = new ContainerCommandHandlerLocator($container);
$locator->registerService(RenameArticleCommand::class, 'acme.demo.command.handler.article.rename');
```

## Called object

Example register the [called object](http://php.net/manual/en/language.oop5.magic.php#object.invoke) as a command handler:

```php
class RenameArticleHandler
{
    public function __invoke(RenameArticleCommand $command): void
    {
        // do something
    }
}

// example of register handler in PSR-11 container
// container on request $container->get('acme.demo.command.handler.article.rename') must return handler
//$container = new Container();
//$container->set('acme.demo.command.handler.article.rename', new RenameArticleHandler());

// register command handler service in handler locator
$locator = new ContainerCommandHandlerLocator($container);
$locator->registerService(RenameArticleCommand::class, 'acme.demo.command.handler.article.rename');
```

## Method of class

Example register the public method of class as a command handler:

```php
class RenameArticleHandler
{
    public function handleRenameArticle(RenameArticleCommand $command): void
    {
        // do something
    }
}

// example of register handler in PSR-11 container
// container on request $container->get('acme.demo.command.handler.article.rename') must return handler
//$container = new Container();
//$container->set('acme.demo.command.handler.article.rename', new RenameArticleHandler());

// register command handler service in handler locator
$locator = new ContainerCommandHandlerLocator($container);
$locator->registerService(RenameArticleCommand::class, 'acme.demo.command.handler.article.rename', 'handleRenameArticle');
```

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

// example of register subscriber in PSR-11 container
// container on request $container->get('acme.demo.command.handler.article.rename') must return subscriber
//$container = new Container();
//$container->set('acme.demo.command.subscriber.article', new ArticleCommandSubscriber());

// register command handler service in handler locator
$locator = new ContainerCommandHandlerLocator($container);
$locator->registerSubscriberService('acme.demo.command.subscriber.article', ArticleCommandSubscriber::class);
```
