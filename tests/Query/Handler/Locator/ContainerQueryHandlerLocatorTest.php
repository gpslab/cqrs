<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Query\Handler\Locator;

use GpsLab\Component\Query\Handler\Locator\ContainerQueryHandlerLocator;
use GpsLab\Component\Query\Query;
use GpsLab\Component\Tests\Fixture\Query\ContactByIdentity;
use GpsLab\Component\Tests\Fixture\Query\ContactByNameQuery;
use GpsLab\Component\Tests\Fixture\Query\Handler\ContactByIdentityHandler;
use GpsLab\Component\Tests\Fixture\Query\Handler\ContestQuerySubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerQueryHandlerLocatorTest extends TestCase
{
    /**
     * @var MockObject|ContainerInterface
     */
    private $container;

    /**
     * @var MockObject|Query
     */
    private $query;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var ContainerQueryHandlerLocator
     */
    private $locator;

    protected function setUp(): void
    {
        $this->query = $this->createMock(Query::class);
        $this->handler = function (Query $query): void {
            $this->assertSame($query, $this->query);
        };
        $this->container = $this->createMock(ContainerInterface::class);
        $this->locator = new ContainerQueryHandlerLocator($this->container);
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

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->findHandler($this->query);
        $this->assertSame($this->handler, $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler($this->query);
        $this->assertSame($this->handler, $handler);
    }

    public function testFindHandlerServiceInvoke(): void
    {
        $service = 'foo';
        $query = new ContactByIdentity();
        $handler_obj = new ContactByIdentityHandler();
        $method = 'handleContactByIdentity';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->willReturn($handler_obj)
        ;

        $this->locator->registerService(ContactByIdentity::class, $service, $method);

        $handler = $this->locator->findHandler($query);
        $this->assertSame([$handler_obj, $method], $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler($query);
        $this->assertSame([$handler_obj, $method], $handler);

        // test exec handler
        $handler($query);
        $this->assertSame($query, $handler_obj->query());
    }

    public function testNoQueryHandler(): void
    {
        $service = 'foo';

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($service)
            ->willReturn(null)
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->findHandler($this->query);
        $this->assertNull($handler);
    }

    public function testNoAnyCommandHandler(): void
    {
        $handler = $this->locator->findHandler($this->query);
        $this->assertNull($handler);
    }

    public function testHandlerIsNotAQueryHandler(): void
    {
        $service = 'foo';

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($service)
            ->willReturn(new \stdClass())
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->findHandler($this->query);
        $this->assertNull($handler);
    }

    public function testRegisterSubscriber(): void
    {
        $service = 'foo';
        $subscriber = new ContestQuerySubscriber();

        $this->container
            ->expects($this->exactly(3))
            ->method('get')
            ->with($service)
            ->willReturn($subscriber)
        ;

        $this->locator->registerSubscriberService($service, get_class($subscriber));

        $handler = $this->locator->findHandler(new ContactByIdentity());
        $this->assertIsCallable($handler);
        $this->assertSame([$subscriber, 'getByIdentity'], $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler(new ContactByIdentity());
        $this->assertIsCallable($handler);
        $this->assertSame([$subscriber, 'getByIdentity'], $handler);

        $handler = $this->locator->findHandler(new ContactByNameQuery());
        $this->assertIsCallable($handler);
        $this->assertSame([$subscriber, 'getByNameQuery'], $handler);
    }
}
