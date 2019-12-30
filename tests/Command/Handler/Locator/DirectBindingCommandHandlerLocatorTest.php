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
use GpsLab\Component\Command\Command;
use PHPUnit\Framework\TestCase;

class DirectBindingCommandHandlerLocatorTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private $command;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var DirectBindingCommandHandlerLocator
     */
    private $locator;

    protected function setUp()
    {
        $this->command = $this->getMock(Command::class);
        $this->handler = function (Command $command) {
            $this->assertEquals($command, $this->command);
        };

        $this->locator = new DirectBindingCommandHandlerLocator();
    }

    public function testFindHandler()
    {
        $this->locator->registerHandler(get_class($this->command), $this->handler);

        $handler = $this->locator->findHandler($this->command);
        $this->assertEquals($this->handler, $handler);
    }

    public function testNoCommandHandler()
    {
        $this->locator->registerHandler('foo', $this->handler);

        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }
}
