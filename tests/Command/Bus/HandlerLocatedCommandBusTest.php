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

    public function testHandle(): void
    {
        $handled_command = null;
        $handler = static function (Command $command) use (&$handled_command): void {
            $handled_command = $command;
        };

        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->command)
            ->willReturn($handler)
        ;

        $this->bus->handle($this->command);
        $this->assertSame($this->command, $handled_command);
    }

    public function testNoHandler(): void
    {
        $this->expectException(HandlerNotFoundException::class);

        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->command)
            ->willReturn(null)
        ;

        $this->bus->handle($this->command);
    }
}
