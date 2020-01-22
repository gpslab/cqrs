Symfony container locator
=========================

Locator is needed for search the handler of handled command.

It's a implementation of locator `CommandHandlerLocator` for
[Symfony container](https://github.com/symfony/dependency-injection).

> **Note**
>
> Symfony 3.3 [implements](http://symfony.com/blog/new-in-symfony-3-3-psr-11-containers) a
> [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md). If you are using version
> Symfony 3.3 or later, you must use a [PSR-11 Container locator](psr-11_container.md).

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
```

YAML configuration for this:

```yml
services:
    RenameArticleHandler: ~

    GpsLab\Component\Command\Handler\Locator\SymfonyContainerCommandHandlerLocator:
        calls:
            - [ setContainer, [ '@service_container' ] ]
            - [ registerService, [ 'RenameArticleCommand', 'RenameArticleHandler' ] ]
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
```

YAML configuration for this:

```yml
services:
    RenameArticleHandler: ~

    GpsLab\Component\Command\Handler\Locator\SymfonyContainerCommandHandlerLocator:
        calls:
            - [ setContainer, [ '@service_container' ] ]
            - [ registerService, [ 'RenameArticleCommand', 'RenameArticleHandler', 'handleRenameArticle' ] ]
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
```

YAML configuration for this:

```yml
services:
    ArticleCommandSubscriber: ~

    GpsLab\Component\Command\Handler\Locator\SymfonyContainerCommandHandlerLocator:
        calls:
            - [ setContainer, [ '@service_container' ] ]
            - [ registerSubscriberService, [ 'ArticleCommandSubscriber', 'ArticleCommandSubscriber' ] ]
```

## Tagging

You can [tagged](https://symfony.com/doc/current/service_container/tags.html) command handler services for optimize
register the services in command locator. You can autoconfigure your subscribers and automatically register it in
locator like that:

```php
// src/Kernel.php
class Kernel extends BaseKernel
{
    protected function build(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(CommandSubscriber::class)
            ->addTag('gpslab.command.subscriber')
        ;

        $locator = $container->findDefinition(SymfonyContainerCommandHandlerLocator::class);

        $tagged_subscribers = $container->findTaggedServiceIds('gpslab.command.subscriber');

        foreach ($tagged_subscribers as $id => $attributes) {
            $subscriber = $container->findDefinition($id);
            $locator->addMethodCall('registerSubscriberService', [$id, $subscriber->getClass()]);
        }
    }
}
```
