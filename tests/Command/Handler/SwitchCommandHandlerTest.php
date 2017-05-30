<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Handler;

use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\Handler\CreateContactHandler;
use GpsLab\Component\Tests\Fixture\Command\Handler\RenameContactHandler;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;

class SwitchCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testSwitch()
    {
        $command = new RenameContactCommand();

        $handler = new RenameContactHandler();
        $handler->handle($command);

        $this->assertEquals($command, $handler->command());
    }

    public function testSwitchNoSuffix()
    {
        $command = new CreateContact();

        $handler = new CreateContactHandler();
        $handler->handle($command);

        $this->assertEquals($command, $handler->command());
    }
}
