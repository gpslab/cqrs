Command handler
===============

You can use any implementations of [callable type](http://php.net/manual/en/language.types.callable.php) as a command
handler.

The command handler can be a [anonymous function](http://php.net/manual/en/functions.anonymous.php):

```php
$handler = function (RenameArticleCommand $command) {
    // do something
};

// register command handler in handler locator
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, $handler);
```

It can be a some function:

```php
function RenameArticleHandler(RenameArticleCommand $command)
{
    // do something
}

// register command handler in handler locator
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, 'RenameArticleHandler');
```

It can be a [called object](http://php.net/manual/en/language.oop5.magic.php#object.invoke):

```php
class RenameArticleHandler
{
    public function __invoke(RenameArticleCommand $command)
    {
        // do something
    }
}

// register command handler in handler locator
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, new RenameArticleHandler());
```

It can be a static method of class:

```php
class RenameArticleHandler
{
    public static function handleRenameArticle(RenameArticleCommand $command)
    {
        // do something
    }
}

// register command handler in handler locator
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, 'RenameArticleHandler::handleRenameArticle');
```

It can be a public method of class:

```php
class RenameArticleHandler
{
    public function handleRenameArticle(RenameArticleCommand $command)
    {
        // do something
    }
}

// register command handler in handler locator
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, [new RenameArticleHandler(), 'handleRenameArticle']);
```

You can handle many commands in one handler.

```php
class ArticleHandler
{
    public function handleRenameArticle(RenameArticleCommand $command)
    {
        // do something
    }

    public function handlePublishArticle(PublishArticleCommand $command)
    {
        // do something
    }
}

// register command handler in handler locator
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, [new ArticleHandler(), 'handleRenameArticle']);
$locator->registerHandler(PublishArticleCommand::class, [new ArticleHandler(), 'handlePublishArticle']);
```

## Command handler locator

You can use exists locators of command handler:

 * [Direct binding locator](locator/direct_binding.md)
 * [PSR-11 container aware locator](locator/psr-11_container.md)
 * [Symfony container aware locator](locator/symfony_container.md)

Or you can create custom locator that implements `GpsLab\Component\Command\Handler\Locator\CommandHandlerLocator`
interface.
