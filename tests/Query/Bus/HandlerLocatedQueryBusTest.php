<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Query\Bus;

use GpsLab\Component\Query\Bus\HandlerLocatedQueryBus;
use GpsLab\Component\Query\Query;
use GpsLab\Component\Query\Handler\Locator\QueryHandlerLocator;
use PHPUnit\Framework\TestCase;

class HandlerLocatedQueryBusTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|QueryHandlerLocator
     */
    private $locator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Query
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

    protected function setUp()
    {
        $this->query = $this->getMock(Query::class);
        $this->handler = function (Query $query) {
            $this->assertEquals($this->query, $query);
        };
        $this->locator = $this->getMock(QueryHandlerLocator::class);
        $this->bus = new HandlerLocatedQueryBus($this->locator);
    }

    public function testDispatch()
    {
        $data = 'foo';
        $handled_query = null;
        $handler = function (Query $query) use (&$handled_query, $data) {
            $handled_query = $query;

            return $data;
        };
        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->query)
            ->will($this->returnValue($handler))
        ;

        $this->assertEquals($data, $this->bus->handle($this->query));
        $this->assertEquals($this->query, $handled_query);
    }

    /**
     * @expectedException \GpsLab\Component\Query\Exception\HandlerNotFoundException
     */
    public function testNoHandler()
    {
        $this->locator
            ->expects($this->once())
            ->method('findHandler')
            ->with($this->query)
            ->will($this->returnValue(null))
        ;

        $this->bus->handle($this->query);
    }
}
