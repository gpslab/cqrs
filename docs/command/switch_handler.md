# Switch command handler

Handler rename article command. For example we use [Doctrine ORM](https://github.com/doctrine/doctrine2).

```php
use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\SwitchCommandHandler;
use Doctrine\ORM\EntityManagerInterface;

class RenameArticleHandler extends SwitchCommandHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function handleRenameArticle(RenameArticleCommand $command)
    {
        // get article by id
        $article = $this->em->getRepository(Article::class)->find($command->article_id);
        $article->rename($command->new_name);
    }
}
```

Handler register new user command.

```php
use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\SwitchCommandHandler;
use Doctrine\ORM\EntityManagerInterface;

class RegisterUserHandler extends SwitchCommandHandler
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

    protected function handleRegisterUser(RegisterUserCommand $command)
    {
        $user = new User(
            $this->generator->nextIdentity(),
            $command->email,
            $this->hasher->hash($command->password)
        );

        // save new user
        $this->em->persist($user);
    }
}
```
