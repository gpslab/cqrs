<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\tests\Query\Handler\Locator;

use GpsLab\Component\Query\Handler\Locator\SymfonyContainerQueryHandlerLocator;
use GpsLab\Component\Query\Handler\QueryHandler;
use GpsLab\Component\Query\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyContainerQueryHandlerLocatorTest extends \PHPUnit_Framework_TestCase
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
     * @var \PHPUnit_Framework_MockObject_MockObject|QueryHandler
     */
    private $handler;

    /**
     * @var SymfonyContainerQueryHandlerLocator
     */
    private $locator;

    protected function setUp()
    {
        $this->query = $this->getMock(Query::class);
        $this->handler = $this->getMock(QueryHandler::class);
        $this->container = $this->getMock(ContainerInterface::class);

        $this->locator = new SymfonyContainerQueryHandlerLocator();
    }

    public function testGetQueryHandler()
    {
        $this->locator->setContainer($this->container);
        $service = 'foo';

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->with($service)
            ->will($this->returnValue($this->handler))
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->getQueryHandler($this->query);
        $this->assertEquals($this->handler, $handler);

        // double call ContainerInterface::get()
        $handler = $this->locator->getQueryHandler($this->query);
        $this->assertEquals($this->handler, $handler);
    }

    public function testNoQueryHandler()
    {
        $this->locator->setContainer($this->container);
        $service = 'foo';

        $this->container
            ->expects($this->exactly(1))
            ->method('get')
            ->with($service)
            ->will($this->returnValue(null))
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->getQueryHandler($this->query);
        $this->assertNull($handler);
    }

    public function testHandlerIsNotAQueryHandler()
    {
        $this->locator->setContainer($this->container);
        $service = 'foo';

        $this->container
            ->expects($this->exactly(1))
            ->method('get')
            ->with($service)
            ->will($this->returnValue(new \stdClass()))
        ;

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->getQueryHandler($this->query);
        $this->assertNull($handler);
    }

    public function testNoContainer()
    {
        $service = 'foo';

        $this->locator->registerService(get_class($this->query), $service);

        $handler = $this->locator->getQueryHandler($this->query);
        $this->assertNull($handler);
    }
}
