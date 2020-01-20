<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Handler\Locator;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\Locator\DirectBindingCommandHandlerLocator;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\Handler\ContestCommandSubscriber;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DirectBindingCommandHandlerLocatorTest extends TestCase
{
    /**
     * @var MockObject|Command
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

    protected function setUp(): void
    {
        $this->command = $this->createMock(Command::class);
        $this->handler = function (Command $command): void {
            $this->assertSame($command, $this->command);
        };
        $this->locator = new DirectBindingCommandHandlerLocator();
    }

    public function testFindHandler(): void
    {
        $this->locator->registerHandler(get_class($this->command), $this->handler);

        $handler = $this->locator->findHandler($this->command);
        $this->assertSame($this->handler, $handler);
    }

    public function testNoCommandHandler(): void
    {
        $this->locator->registerHandler('foo', $this->handler);

        $handler = $this->locator->findHandler($this->command);
        $this->assertNull($handler);
    }

    public function testRegisterSubscriber(): void
    {
        $subscriber = new ContestCommandSubscriber();

        $this->locator->registerSubscriber($subscriber);

        $handler = $this->locator->findHandler(new CreateContact());
        $this->assertIsCallable($handler);
        $this->assertSame([$subscriber, 'onCreate'], $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler(new CreateContact());
        $this->assertIsCallable($handler);
        $this->assertSame([$subscriber, 'onCreate'], $handler);

        $handler = $this->locator->findHandler(new RenameContactCommand());
        $this->assertIsCallable($handler);
        $this->assertSame([$subscriber, 'onRename'], $handler);
    }
}
