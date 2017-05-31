<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Command\Handler;

use GpsLab\Component\Command\Handler\SwitchCommandHandler;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;

class RenameContactHandler extends SwitchCommandHandler
{
    /**
     * @var RenameContactCommand|null
     */
    private $command;

    /**
     * @param RenameContactCommand $command
     */
    protected function handleRenameContact(RenameContactCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @return RenameContactCommand|null
     */
    public function command()
    {
        return $this->command;
    }
}
