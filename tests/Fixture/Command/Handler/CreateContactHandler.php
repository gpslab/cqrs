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
use GpsLab\Component\Tests\Fixture\Command\CreateContact;

class CreateContactHandler extends SwitchCommandHandler
{
    /**
     * @var CreateContact|null
     */
    private $command;

    /**
     * @param CreateContact $command
     */
    protected function handleCreateContact(CreateContact $command)
    {
        $this->command = $command;
    }

    /**
     * @return CreateContact|null
     */
    public function command()
    {
        return $this->command;
    }
}
