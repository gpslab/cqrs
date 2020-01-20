<?php
declare(strict_types=1);


namespace GpsLab\Component\Tests\Fixture\Command\Handler;

use GpsLab\Component\Command\Handler\CommandSubscriber;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;

class ContestCommandSubscriber implements CommandSubscriber
{
    /**
     * @var CreateContact|null
     */
    private $create_contact_command;

    /**
     * @var RenameContactCommand|null
     */
    private $rename_contact_command;

    /**
     * @return array
     */
    public static function getSubscribedCommands(): array
    {
        return [
            CreateContact::class => 'onCreate',
            RenameContactCommand::class => 'onRename',
        ];
    }

    /**
     * @param CreateContact $command
     */
    public function onCreate(CreateContact $command): void
    {
        $this->create_contact_command = $command;
    }

    /**
     * @param RenameContactCommand $command
     */
    public function onRename(RenameContactCommand $command): void
    {
        $this->rename_contact_command = $command;
    }

    /**
     * @return CreateContact|null
     */
    public function getCreateContactCommand(): ?CreateContact
    {
        return $this->create_contact_command;
    }

    /**
     * @return RenameContactCommand|null
     */
    public function getRenameContactCommand(): ?RenameContactCommand
    {
        return $this->rename_contact_command;
    }
}
