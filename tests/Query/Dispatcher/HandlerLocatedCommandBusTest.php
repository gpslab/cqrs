<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Query\Dispatcher;

use GpsLab\Component\Query\Dispatcher\HandlerLocatedQueryDispatcher;
use GpsLab\Component\Query\Query;
use GpsLab\Component\Query\Handler\QueryHandler;
use GpsLab\Component\Query\Handler\Locator\QueryHandlerLocator;

class HandlerLocatedCommandBusTest extends \PHPUnit_Framework_TestCase
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
     * @var \PHPUnit_Framework_MockObject_MockObject|QueryHandler
     */
    private $handler;

    /**
     * @var HandlerLocatedQueryDispatcher
     */
    private $bus;

    protected function setUp()
    {
        $this->handler = $this->getMock(QueryHandler::class);
        $this->query = $this->getMock(Query::class);
        $this->locator = $this->getMock(QueryHandlerLocator::class);
        $this->bus = new HandlerLocatedQueryDispatcher($this->locator);
    }

    public function testDispatch()
    {
        $this->locator
            ->expects($this->once())
            ->method('getQueryHandler')
            ->with($this->query)
            ->will($this->returnValue($this->handler))
        ;

        $data = 'foo';
        $this->handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->query)
            ->will($this->returnValue($data))
        ;

        $this->assertEquals($data, $this->bus->dispatch($this->query));
    }

    /**
     * @expectedException \GpsLab\Component\Query\Exception\HandlerNotFoundException
     */
    public function testNoHandler()
    {
        $this->locator
            ->expects($this->once())
            ->method('getQueryHandler')
            ->with($this->query)
            ->will($this->returnValue(null))
        ;

        $this->bus->dispatch($this->query);
    }
}
