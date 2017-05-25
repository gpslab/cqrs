<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Handler\Locator;

use GpsLab\Component\Command\Handler\Locator\ContainerCommandHandlerLocator;
use GpsLab\Component\Command\Handler\CommandHandler;
use GpsLab\Component\Command\Command;
use Psr\Container\ContainerInterface;

class ContainerCommandHandlerLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    private $container;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CommandHandler
     */
    private $handler;

    /**
     * @var ContainerCommandHandlerLocator
     */
    private $locator;

    protected function setUp()
    {
        $this->command = $this->getMock(Command::class);
        $this->handler = $this->getMock(CommandHandler::class);
        $this->container = $this->getMock(ContainerInterface::class);

        $this->locator = new ContainerCommandHandlerLocator($this->container);
    }

    public function testGetCommandHandler()
    {
        $service = 'foo';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->will($this->returnValue($this->handler))
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->getCommandHandler($this->command);
        $this->assertEquals($this->handler, $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->getCommandHandler($this->command);
        $this->assertEquals($this->handler, $handler);
    }

    public function testNoCommandHandler()
    {
        $service = 'foo';

        $this->container
            ->expects($this->exactly(1))
            ->method('get')
            ->with($service)
            ->will($this->returnValue(null))
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->getCommandHandler($this->command);
        $this->assertNull($handler);
    }

    public function testHandlerIsNotACommandHandler()
    {
        $service = 'foo';

        $this->container
            ->expects($this->exactly(1))
            ->method('get')
            ->with($service)
            ->will($this->returnValue(new \stdClass()))
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->getCommandHandler($this->command);
        $this->assertNull($handler);
    }
}
