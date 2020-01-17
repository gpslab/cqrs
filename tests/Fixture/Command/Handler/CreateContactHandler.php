<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Command\Handler;

use GpsLab\Component\Tests\Fixture\Command\CreateContact;

class CreateContactHandler
{
    /**
     * @var CreateContact|null
     */
    private $command;

    /**
     * @param CreateContact $command
     */
    public function handleCreateContact(CreateContact $command): void
    {
        $this->command = $command;
    }

    /**
     * @return CreateContact|null
     */
    public function command(): ?CreateContact
    {
        return $this->command;
    }
}
