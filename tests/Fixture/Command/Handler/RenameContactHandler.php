<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Command\Handler;

use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;

class RenameContactHandler
{
    /**
     * @var RenameContactCommand|null
     */
    private $command;

    /**
     * @param RenameContactCommand $command
     */
    public function handleRenameContact(RenameContactCommand $command): void
    {
        $this->command = $command;
    }

    /**
     * @return RenameContactCommand|null
     */
    public function command(): ?RenameContactCommand
    {
        return $this->command;
    }
}
