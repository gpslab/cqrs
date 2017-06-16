<?php

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
use Psr\Container\ContainerInterface;

class ContainerQueryHandlerLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    private $container;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Query
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

    protected function setUp()
    {
        $this->query = $this->getMock(Query::class);
        $this->handler = function (Query $query) {
            $this->assertEquals($query, $this->query);
        };
        $this->container = $this->getMock(ContainerInterface::class);

        $this->locator = new ContainerQueryHandlerLocator($this->container);
    }

    public function testFindHandler()
    {
        $service = 'foo';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->will($this->returnValue($this->handler))
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->findHandler($this->query);
        $this->assertEquals($this->handler, $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->findHandler($this->query);
        $this->assertEquals($this->handler, $handler);
    }

    public function testNoQueryHandler()
    {
        $service = 'foo';

        $this->container
            ->expects($this->exactly(1))
            ->method('get')
            ->with($service)
            ->will($this->returnValue(null))
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->findHandler($this->query);
        $this->assertNull($handler);
    }

    public function testHandlerIsNotAQueryHandler()
    {
        $service = 'foo';

        $this->container
            ->expects($this->exactly(1))
            ->method('get')
            ->with($service)
            ->will($this->returnValue(new \stdClass()))
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->findHandler($this->query);
        $this->assertNull($handler);
    }
}
