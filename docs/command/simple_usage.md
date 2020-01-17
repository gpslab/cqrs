Simple usage commands
=====================

Commands, in the [CQRS](https://martinfowler.com/bliki/CQRS.html) approach, are designed to change the data in the
application.

For example, consider the procedure for renaming an article.

Create a command to rename:

```php
use GpsLab\Component\Command\Command;

class RenameArticleCommand implements Command
{
    /**
     * @var int
     */
    public $article_id;

    /**
     * @var string
     */
    public $new_name = '';
}
```

You can use private properties to better control the types of data and required properties:

```php
use GpsLab\Component\Command\Command;

class RenameArticleCommand implements Command
{
    private $article_id;

    private $new_name;

    public function __construct(int $article_id, string $new_name)
    {
        $this->article_id = $article_id;
        $this->new_name = $new_name;
    }

    public function articleId(): int
    {
        return $this->article_id;
    }

    public function newName(): string
    {
        return $this->new_name;
    }
}
```

> **Note**
>
> To simplify the filling of the team, you can use [payload](https://github.com/gpslab/payload).

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
$locator = new DirectBindingCommandHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, [new RenameArticleHandler($em), 'handleRenameArticle']);

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
