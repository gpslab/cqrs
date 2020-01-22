<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Query\Bus;

use GpsLab\Component\Query\Bus\HandlerLocatedQueryBus;
use GpsLab\Component\Query\Exception\HandlerNotFoundException;
use GpsLab\Component\Query\Handler\Locator\QueryHandlerLocator;
use GpsLab\Component\Query\Query;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HandlerLocatedQueryBusTest extends TestCase
{
    /**
     * @var MockObject|QueryHandlerLocator
     */
    private $locator;

    /**
     * @var MockObject|Query
     */
    private $query;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var HandlerLocatedQueryBus
     */
    private $bus;

    protected function setUp(): void
    {
        $this->query = $this->createMock(Query::class);
        $this->handler = function (Query $query): void {
            $this->assertSame($this->query, $query);
        };
        $this->locator = $this->createMock(QueryHandlerLocator::class);
        $this->bus = new HandlerLocatedQueryBus($this->locator);
    }

    public function testDispatch(): void
    {
        $data = 'foo';
        $handled_query = null;
        $handler = static function (Query $query) use (&$handled_query, $data): string {
            $handled_query = $query;

            return $data;
        };
        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->query)
            ->willReturn($handler)
        ;

        $this->assertSame($data, $this->bus->handle($this->query));
        $this->assertSame($this->query, $handled_query);
    }

    public function testNoHandler(): void
    {
        $this->expectException(HandlerNotFoundException::class);

        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->query)
            ->willReturn(null)
        ;

        $this->bus->handle($this->query);
    }
}
