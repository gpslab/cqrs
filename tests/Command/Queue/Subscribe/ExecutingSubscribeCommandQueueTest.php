<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Queue\Subscribe;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Queue\Subscribe\ExecutingSubscribeCommandQueue;

class ExecutingSubscribeCommandQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private $command;

    /**
     * @var ExecutingSubscribeCommandQueue
     */
    private $queue;

    protected function setUp()
    {
        $this->command = $this->getMock(Command::class);
        $this->queue = new ExecutingSubscribeCommandQueue();
    }

    public function testPublish()
    {
        $subscriber_called = false;

        $this->queue->subscribe(function ($command) use (&$subscriber_called) {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertEquals($this->command, $command);
            $subscriber_called = true;
        });

        $this->assertTrue($this->queue->publish($this->command));
        $this->assertTrue($subscriber_called);
    }
}
