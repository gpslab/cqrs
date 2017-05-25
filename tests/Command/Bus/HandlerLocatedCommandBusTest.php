<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\tests\Command\Bus;

use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\CommandHandler;
use GpsLab\Component\Command\Handler\Locator\CommandHandlerLocator;

class HandlerLocatedCommandBusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CommandHandlerLocator
     */
    private $locator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CommandHandler
     */
    private $handler;

    /**
     * @var HandlerLocatedCommandBus
     */
    private $bus;

    protected function setUp()
    {
        $this->handler = $this->getMock(CommandHandler::class);
        $this->command = $this->getMock(Command::class);
        $this->locator = $this->getMock(CommandHandlerLocator::class);
        $this->bus = new HandlerLocatedCommandBus($this->locator);
    }

    public function testHandle()
    {
        $this->locator
            ->expects($this->once())
            ->method('getCommandHandler')
            ->with($this->command)
            ->will($this->returnValue($this->handler))
        ;

        $this->handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->command)
        ;

        $this->bus->handle($this->command);
    }

    /**
     * @expectedException \GpsLab\Component\Command\Exception\HandlerNotFoundException
     */
    public function testNoHandler()
    {
        $this->locator
            ->expects($this->once())
            ->method('getCommandHandler')
            ->with($this->command)
            ->will($this->returnValue(null))
        ;

        $this->bus->handle($this->command);
    }
}
