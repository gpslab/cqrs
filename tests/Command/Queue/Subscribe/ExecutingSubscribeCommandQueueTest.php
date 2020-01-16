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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExecutingSubscribeCommandQueueTest extends TestCase
{
    /**
     * @var MockObject|Command
     */
    private $command;

    /**
     * @var ExecutingSubscribeCommandQueue
     */
    private $queue;

    protected function setUp(): void
    {
        $this->command = $this->createMock(Command::class);
        $this->queue = new ExecutingSubscribeCommandQueue();
    }

    public function testPublish()
    {
        $subscriber_called = false;
        $handler = function ($command) use (&$subscriber_called) {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertEquals($this->command, $command);
            $subscriber_called = true;
        };

        $this->assertFalse($this->queue->unsubscribe($handler));

        $this->queue->subscribe($handler);

        $this->assertTrue($this->queue->publish($this->command));
        $this->assertTrue($subscriber_called);
        $this->assertTrue($this->queue->unsubscribe($handler));
        $this->assertFalse($this->queue->unsubscribe($handler));
    }
}
