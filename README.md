[![Latest Stable Version](https://img.shields.io/packagist/v/gpslab/cqrs.svg?maxAge=3600&label=stable)](https://packagist.org/packages/gpslab/cqrs)
[![Total Downloads](https://img.shields.io/packagist/dt/gpslab/cqrs.svg?maxAge=3600)](https://packagist.org/packages/gpslab/cqrs)
[![Build Status](https://img.shields.io/travis/gpslab/cqrs.svg?maxAge=3600)](https://travis-ci.org/gpslab/cqrs)
[![Coverage Status](https://img.shields.io/coveralls/gpslab/cqrs.svg?maxAge=3600)](https://coveralls.io/github/gpslab/cqrs?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/gpslab/cqrs.svg?maxAge=3600)](https://scrutinizer-ci.com/g/gpslab/cqrs/?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/a7885c13-685e-49bc-b1e7-635010540f21.svg?maxAge=3600&label=SLInsight)](https://insight.sensiolabs.com/projects/a7885c13-685e-49bc-b1e7-635010540f21)
[![StyleCI](https://styleci.io/repos/92310135/shield?branch=master)](https://styleci.io/repos/92310135)
[![License](https://img.shields.io/packagist/l/gpslab/cqrs.svg?maxAge=3600)](https://github.com/gpslab/cqrs)

# CQRS

Infrastructure for creating [CQRS](https://martinfowler.com/bliki/CQRS.html) applications.

<p align="center"><img src="cqrs_schema.png" alt="CQRS base scheme"></p>

## Installation

Pretty simple with [Composer](http://packagist.org), run:

```sh
composer require gpslab/cqrs
```

## Command

* **[Simple usage](docs/command/simple_usage.md)**
* [Bus](docs/command/command_bus.md)
* Handler
  * [Create handler](docs/command/handler.md)
  * Locator and Subscribers
    * [Direct binding locator](docs/command/locator/direct_binding.md)
    * [PSR-11 Container locator](docs/command/locator/psr-11_container.md) *([PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md))*
    * [Symfony container locator](docs/command/locator/symfony_container.md) *(Symfony 3.3 [implements](http://symfony.com/blog/new-in-symfony-3-3-psr-11-containers) a [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md))*
* [Queue](docs/command/queue/queue.md)
  * [Pull](docs/command/queue/pull/pull.md)
    * [Memory queue](docs/command/queue/pull/memory.md)
    * [Memory unique queue](docs/command/queue/pull/memory_unique.md)
    * [Predis queue](docs/command/queue/pull/predis.md)
    * [Predis unique queue](docs/command/queue/pull/predis_unique.md)
  * [Subscribe](docs/command/queue/subscribe/subscribe.md)
    * [Executing queue](docs/command/queue/subscribe/executing.md)
    * [Predis queue](docs/command/queue/subscribe/predis.md)
  * Serialize command
    * [Optimized serializer](docs/command/queue/serialize/optimized.md)
    * [Payload serializer](docs/command/queue/serialize/payload.md)
* [Middleware](https://github.com/gpslab/middleware)
* [Payload](https://github.com/gpslab/payload)

### Simple usage commands

Commands, in the [CQRS](https://martinfowler.com/bliki/CQRS.html) approach, are designed to change the data in the
application.

For example, consider the procedure for renaming an article.

Create a command to rename:

```php
use GpsLab\Component\Command\Command;

class RenameArticleCommand implements Command
{
    public $article_id;

    public $new_name = '';
}
```

> **Note**
>
> To simplify the filling of the command, you can use [payload](https://github.com/gpslab/payload).

You can use any implementations of [callable type](http://php.net/manual/en/language.types.callable.php) as a command
handler. We recommend using public methods of classes as handlers. For example we use [Doctrine ORM](https://github.com/doctrine/doctrine2).

```php
use GpsLab\Component\Command\Command;
use Doctrine\ORM\EntityManagerInterface;

class RenameArticleHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handleRenameArticle(RenameArticleCommand $command): void
    {
        // get article by id
        $article = $this->em->getRepository(Article::class)->find($command->article_id);
        $article->rename($command->new_name);
    }
}
```

And now we register handler and handle command.

```php
use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;

// register command handler in handler locator
$handler = new RenameArticleHandler($em);
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, [$handler, 'handleRenameArticle']);

// create bus with command handler locator
$bus = new HandlerLocatedCommandBus($locator);

// ...

// create rename article command
$command = new RenameArticleCommand();
$command->article_id = $article_id;
$command->new_name = $new_name;

// handle command
$bus->handle($command);
```

For the asynchronous handle a command you can use `CommandQueue`.

> **Note**
>
> To monitor the execution of commands, you can use [middleware](https://github.com/gpslab/middleware).


## Query

* **[Simple usage](docs/query/simple_usage.md)**
* [Bus](docs/query/query_bus.md)
* Handler
  * [Create handler](docs/query/handler.md)
  * Locator and Subscribers
    * [Direct binding locator](docs/query/locator/direct_binding.md)
    * [PSR-11 Container locator](docs/query/locator/psr-11_container.md) *([PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md))*
    * [Symfony container locator](docs/query/locator/symfony_container.md) *(Symfony 3.3 [implements](http://symfony.com/blog/new-in-symfony-3-3-psr-11-containers) a [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md))*
* [Middleware](https://github.com/gpslab/middleware)
* [Payload](https://github.com/gpslab/payload)
* [Doctrine specification query](https://github.com/gpslab/specification-query)

### Simple usage queries

Query, in the [CQRS](https://martinfowler.com/bliki/CQRS.html) approach, are designed to get the data in the
application.

For example, consider the procedure for get an article by identity.

Create a query:

```php
use GpsLab\Component\Query\Query;

class ArticleByIdentityQuery implements Query
{
    public $article_id;
}
```

> **Note**
>
> To simplify the filling of the query, you can use [payload](https://github.com/gpslab/payload).

You can use any implementations of [callable type](http://php.net/manual/en/language.types.callable.php) as a query
handler. We recommend using public methods of classes as handlers. For example we use [Doctrine ORM](https://github.com/doctrine/doctrine2).

```php
use GpsLab\Component\Query\Query;
use Doctrine\ORM\EntityManagerInterface;

class ArticleByIdentityHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handleArticleByIdentity(ArticleByIdentityQuery $query)
    {
        // get article by id
        return $this->em->getRepository(Article::class)->find($query->article_id);
    }
}
```

And now we register handler and handle query.

```php
use GpsLab\Component\Query\Bus\HandlerLocatedQueryBus;
use GpsLab\Component\Query\Handler\Locator\DirectBindingQueryHandlerLocator;

// register query handler in handler locator
$handler = new ArticleByIdentityHandler($em);
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(ArticleByIdentityQuery::class, [$handler, 'handleArticleByIdentity']);

// create bus with query handler locator
$bus = new HandlerLocatedQueryBus($locator);

// ...

// create find article query
$query = new ArticleByIdentityQuery();
$query->article_id = $article_id;

// handle query
$article = $bus->handle($query);
```

> **Note**
>
> To monitor the execution of commands, you can use [middleware](https://github.com/gpslab/middleware).


## License

This bundle is under the [MIT license](http://opensource.org/licenses/MIT). See the complete license in the file: LICENSE
