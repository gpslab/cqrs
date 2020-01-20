<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Handler\Locator;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\Locator\ContainerCommandHandlerLocator;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\Handler\CreateContactHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerCommandHandlerLocatorTest extends TestCase
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
     * @var ContainerCommandHandlerLocator
     */
    private $locator;

    protected function setUp(): void
    {
        $this->command = $this->createMock(Command::class);
        $this->handler = function (Command $command): void {
            $this->assertSame($command, $this->command);
        };
        $this->container = $this->createMock(ContainerInterface::class);
        $this->locator = new ContainerCommandHandlerLocator($this->container);
    }

    public function testFindHandler(): void
    {
        $service = 'foo';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->willReturn($this->handler)
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->findHandler($this->command);
        $this->assertSame($this->handler, $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler($this->command);
        $this->assertSame($this->handler, $handler);
    }

    public function testFindHandlerServiceInvoke(): void
    {
        $service = 'foo';
        $command = new CreateContact();
        $handler_obj = new CreateContactHandler();
        $method = 'handleCreateContact';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->willReturn($handler_obj)
        ;

        $this->locator->registerService(CreateContact::class, $service, $method);

        $handler = $this->locator->findHandler($command);
        $this->assertSame([$handler_obj, $method], $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler($command);
        $this->assertSame([$handler_obj, $method], $handler);

        // test exec handler
        call_user_func($handler, $command);
        $this->assertSame($command, $handler_obj->command());
    }

    public function testNoCommandHandler(): void
    {
        $service = 'foo';

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($service)
            ->willReturn(null)
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }

    public function testNoAnyCommandHandler(): void
    {
        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }

    public function testHandlerIsNotACommandHandler(): void
    {
        $service = 'foo';

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($service)
            ->willReturn(new \stdClass())
        ;

        $this->locator->registerService(get_class($this->command), $service);

        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }
}
