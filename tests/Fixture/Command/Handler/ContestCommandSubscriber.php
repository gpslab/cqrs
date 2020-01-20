<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Command\Handler;

use GpsLab\Component\Command\Handler\CommandSubscriber;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;

class ContestCommandSubscriber implements CommandSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedCommands(): array
    {
        return [
            CreateContact::class => 'handleCreate',
            RenameContactCommand::class => 'handleRename',
        ];
    }

    /**
     * @param CreateContact $command
     */
    public function handleCreate(CreateContact $command): void
    {
        // do something
    }

    /**
     * @param RenameContactCommand $command
     */
    public function handleRename(RenameContactCommand $command): void
    {
        // do something
    }
}
