<?php

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

class DirectBindingQueryHandlerLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Query
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

    protected function setUp()
    {
        $this->query = $this->getMock(Query::class);
        $this->handler = function (Query $query) {
            $this->assertEquals($query, $this->query);
        };

        $this->locator = new DirectBindingQueryHandlerLocator();
    }

    public function testFindHandler()
    {
        $this->locator->registerHandler(get_class($this->query), $this->handler);

        $handler = $this->locator->findHandler($this->query);
        $this->assertEquals($this->handler, $handler);
    }

    public function testNoQueryHandler()
    {
        $this->locator->registerHandler('foo', $this->handler);

        $handler = $this->locator->findHandler($this->query);
        $this->assertNull($handler);
    }
}
