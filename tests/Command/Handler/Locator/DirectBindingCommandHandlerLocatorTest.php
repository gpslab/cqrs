<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Handler\Locator;

use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;
use GpsLab\Component\Command\Handler\CommandHandler;
use GpsLab\Component\Command\Command;

class DirectBindingCommandHandlerLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CommandHandler
     */
    private $handler;

    /**
     * @var DirectBindingCommandHandlerLocator
     */
    private $locator;

    protected function setUp()
    {
        $this->command = $this->getMock(Command::class);
        $this->handler = $this->getMock(CommandHandler::class);

        $this->locator = new DirectBindingCommandHandlerLocator();
    }

    public function testGetCommandHandler()
    {
        $this->locator->registerHandler(get_class($this->command), $this->handler);

        $handler = $this->locator->getCommandHandler($this->command);
        $this->assertEquals($this->handler, $handler);
    }

    public function testNoCommandHandler()
    {
        $this->locator->registerHandler('foo', $this->handler);

        $handler = $this->locator->getCommandHandler($this->command);
        $this->assertNull($handler);
    }
}