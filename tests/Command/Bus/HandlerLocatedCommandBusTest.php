<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Bus;

use GpsLab\Component\Command\Bus\HandlerLocatedCommandBus;
use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\Locator\CommandHandlerLocator;
use PHPUnit\Framework\TestCase;

class HandlerLocatedCommandBusTest extends TestCase
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
     * @var HandlerLocatedCommandBus
     */
    private $bus;

    protected function setUp()
    {
        $this->command = $this->getMock(Command::class);
        $this->locator = $this->getMock(CommandHandlerLocator::class);
        $this->bus = new HandlerLocatedCommandBus($this->locator);
    }

    public function testHandle()
    {
        $handled_command = null;
        $handler = function (Command $command) use (&$handled_command) {
            $handled_command = $command;
        };

        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->command)
            ->will($this->returnValue($handler))
        ;

        $this->bus->handle($this->command);
        $this->assertEquals($this->command, $handled_command);
    }

    /**
     * @expectedException \GpsLab\Component\Command\Exception\HandlerNotFoundException
     */
    public function testNoHandler()
    {
        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->command)
            ->will($this->returnValue(null))
        ;

        $this->bus->handle($this->command);
    }
}
