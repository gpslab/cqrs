# Simple usage queries

Query, in the [CQRS](https://martinfowler.com/bliki/CQRS.html) approach, are designed to get the data in the
application.

For example, consider the procedure for get an article by identity.

Create a query:

```php
use GpsLab\Component\Query\Query;

class ArticleByIdentityQuery implements Query
{
    /**
     * @var int
     */
    public $article_id;
}
```

You can use private properties to better control the types of data and required properties:

```php
use GpsLab\Component\Query\Query;

class ArticleByIdentityQuery implements Query
{
    private $article_id;

    public function __construct(integer $article_id)
    {
        $this->article_id = $article_id;
    }

    public function articleId()
    {
        return $this->article_id;
    }
}
```

> **Note**
>
> To simplify the filling of the team, you can use [payload](https://github.com/gpslab/payload).

Now create a query handler. For example we use [Doctrine ORM](https://github.com/doctrine/doctrine2).

```php
use GpsLab\Component\Query\Query;
use GpsLab\Component\Query\Handler\QueryHandler;
use Doctrine\ORM\EntityManagerInterface;

class ArticleByIdentityHandler implements QueryHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handle(Query $query)
    {
        // you need to make sure that this is the team that we expect
        if ($query instanceof ArticleByIdentityQuery) {
            return null;
        }

        // get article by id
        return $this->em->getRepository(Article::class)->find($query->article_id);
    }
}
```

To not check the type of query, you can use the switch:

```php
use GpsLab\Component\Query\Query;
use GpsLab\Component\Query\Handler\SwitchQueryHandler;
use Doctrine\ORM\EntityManagerInterface;

class ArticleByIdentityHandler extends SwitchQueryHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function handleArticleByIdentity(ArticleByIdentityQuery $query)
    {
        return $this->em->getRepository(Article::class)->find($query->article_id);
    }
}
```

And now we register handler and handle query.

```php
use GpsLab\Component\Query\Bus\HandlerLocatedQueryBus;
use GpsLab\Component\Query\Handler\Locator\DirectBindingQueryHandlerLocator;

// register query handler in handler locator
$locator = new DirectBindingQueryHandlerLocator();
$locator->registerHandler(RenameArticleCommand::class, new ArticleByIdentityHandler($em));

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
