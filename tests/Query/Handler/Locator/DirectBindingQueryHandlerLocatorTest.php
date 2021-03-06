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

use GpsLab\Component\Query\Handler\Locator\DirectBindingQueryHandlerLocator;
use GpsLab\Component\Query\Query;
use GpsLab\Component\Tests\Fixture\Query\ContactByIdentity;
use GpsLab\Component\Tests\Fixture\Query\ContactByNameQuery;
use GpsLab\Component\Tests\Fixture\Query\Handler\ContestQuerySubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DirectBindingQueryHandlerLocatorTest extends TestCase
{
    /**
     * @var MockObject|Query
     */
    private $query;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var DirectBindingQueryHandlerLocator
     */
    private $locator;

    protected function setUp(): void
    {
        $this->query = $this->createMock(Query::class);
        $this->handler = function (Query $query): void {
            $this->assertSame($query, $this->query);
        };
        $this->locator = new DirectBindingQueryHandlerLocator();
    }

    public function testFindHandler(): void
    {
        $this->locator->registerHandler(get_class($this->query), $this->handler);

        $handler = $this->locator->findHandler($this->query);
        $this->assertSame($this->handler, $handler);
    }

    public function testNoQueryHandler(): void
    {
        $this->locator->registerHandler('foo', $this->handler);

        $handler = $this->locator->findHandler($this->query);
        $this->assertNull($handler);
    }

    public function testRegisterSubscriber(): void
    {
        $subscriber = new ContestQuerySubscriber();

        $this->locator->registerSubscriber($subscriber);

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
