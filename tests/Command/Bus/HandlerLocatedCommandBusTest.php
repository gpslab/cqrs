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
use GpsLab\Component\Command\Exception\HandlerNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HandlerLocatedCommandBusTest extends TestCase
{
    /**
     * @var MockObject|CommandHandlerLocator
     */
    private $locator;

    /**
     * @var MockObject|Command
     */
    private $command;

    /**
     * @var HandlerLocatedCommandBus
     */
    private $bus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(Command::class);
        $this->locator = $this->createMock(CommandHandlerLocator::class);
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

    public function testNoHandler()
    {
        $this->expectException(HandlerNotFoundException::class);

        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->command)
            ->will($this->returnValue(null))
        ;

        $this->bus->handle($this->command);
    }
}
