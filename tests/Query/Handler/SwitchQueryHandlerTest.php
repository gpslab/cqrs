<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\tests\Query\Handler;

use GpsLab\Component\Tests\Fixture\Query\ContactByIdentity;
use GpsLab\Component\Tests\Fixture\Query\ContactByNameQuery;
use GpsLab\Component\Tests\Fixture\Query\Handler\ContactByIdentityHandler;
use GpsLab\Component\Tests\Fixture\Query\Handler\ContactByNameHandler;

class SwitchQueryHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testSwitch()
    {
        $query = new ContactByNameQuery();

        $handler = new ContactByNameHandler();
        $handler->handle($query);

        $this->assertEquals($query, $handler->query());
    }

    public function testSwitchNoSuffix()
    {
        $query = new ContactByIdentity();

        $handler = new ContactByIdentityHandler();
        $handler->handle($query);

        $this->assertEquals($query, $handler->query());
    }
}
