# Command handler

Handler rename article command. For example we use [Doctrine ORM](https://github.com/doctrine/doctrine2).

```php
use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;

class RenameArticleHandler implements CommandHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handle(Command $command)
    {
        // you need to make sure that this is the team that we expect
        if ($command instanceof RenameArticleCommand) {
            // get article by id
            $article = $this->em->getRepository(Article::class)->find($command->article_id);
            $article->rename($command->new_name);
        }
    }
}
```

Handler register new user command.

```php
use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;

class RegisterUserHandler implements CommandHandler
{
    private $em;

    private $generator;

    private $hasher;

    public function __construct(
        EntityManagerInterface $em,
        UserIdentityGenerator $generator,
        PasswordHasher $hasher
    ) {
        $this->em = $em;
        $this->generator = $generator;
        $this->hasher = $hasher;
    }

    public function handle(Command $command)
    {
        // you need to make sure that this is the team that we expect
        if ($command instanceof RegisterUserCommand) {
            $user = new User(
                $this->generator->nextIdentity(),
                $command->email,
                $this->hasher->hash($command->password)
            );

            // save new user
            $this->em->persist($user);
        }
    }
}
```

You can use `SwitchCommandHandler` or `SwitchCommandHandlerTrait` for optimize handle commands. See how to use
[switch](switch_handler.md).
