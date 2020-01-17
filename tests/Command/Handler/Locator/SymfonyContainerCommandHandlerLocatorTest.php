<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Handler\Locator;

use GpsLab\Component\Command\Handler\Locator\SymfonyContainerCommandHandlerLocator;
use GpsLab\Component\Command\Command;
use GpsLab\Component\Tests\Fixture\Command\Handler\RenameContactHandler;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SymfonyContainerCommandHandlerLocatorTest extends TestCase
{
    /**
     * @var MockObject|ContainerInterface
     */
    private $container;

    /**
     * @var MockObject|Command
     */
    private $command;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var SymfonyContainerCommandHandlerLocator
     */
    private $locator;

    protected function setUp(): void
    {
        $this->command = $this->createMock(Command::class);
        $this->handler = function (Command $command) {
            $this->assertEquals($command, $this->command);
        };
        $this->container = $this->createMock(ContainerInterface::class);
        $this->locator = new SymfonyContainerCommandHandlerLocator();
    }

    public function testFindHandler()
    {
        $this->locator->setContainer($this->container);
        $service = 'foo';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->will($this->returnValue($this->handler))
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->findHandler($this->command);
        $this->assertEquals($this->handler, $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler($this->command);
        $this->assertEquals($this->handler, $handler);
    }

    public function testFindHandlerServiceInvoke()
    {
        $this->locator->setContainer($this->container);
        $service = 'foo';
        $command = new RenameContactCommand();
        $handler_obj = new RenameContactHandler();
        $method = 'handleRenameContact';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->will($this->returnValue($handler_obj))
        ;

        $this->locator->registerService(RenameContactCommand::class, $service, $method);

        $handler = $this->locator->findHandler($command);
        $this->assertEquals([$handler_obj, $method], $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler($command);
        $this->assertEquals([$handler_obj, $method], $handler);

        // test exec handler
        call_user_func($handler, $command);
        $this->assertEquals($command, $handler_obj->command());
    }

    public function testNoCommandHandler()
    {
        $this->locator->setContainer($this->container);
        $service = 'foo';

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($service)
            ->will($this->returnValue(null))
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }

    public function testHandlerIsNotACommandHandler()
    {
        $this->locator->setContainer($this->container);
        $service = 'foo';

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($service)
            ->will($this->returnValue(new \stdClass()))
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }

    public function testNoAnyCommandHandler()
    {
        $this->locator->setContainer($this->container);
        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }

    public function testNoContainer()
    {
        $service = 'foo';

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }
}
