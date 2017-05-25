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
use GpsLab\Component\Tests\Fixture\Command\UpdateContactCommand;

class UpdateContactHandler extends SwitchCommandHandler
{
    /**
     * @var UpdateContactCommand|null
     */
    private $command;

    /**
     * @param UpdateContactCommand $command
     */
    protected function handleUpdateContact(UpdateContactCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @return UpdateContactCommand|null
     */
    public function command()
    {
        return $this->command;
    }
}
