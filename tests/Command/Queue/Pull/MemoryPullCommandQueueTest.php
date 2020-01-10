<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Queue\Pull;

use GpsLab\Component\Command\Queue\Pull\MemoryPullCommandQueue;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;
use PHPUnit\Framework\TestCase;

class MemoryPullCommandQueueTest extends TestCase
{
    /**
     * @var MemoryPullCommandQueue
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new MemoryPullCommandQueue();
    }

    public function testQueue()
    {
        $queue = [
            new CreateContact(),
            new RenameContactCommand(),
            new CreateContact(), // duplicate
        ];

        foreach ($queue as $command) {
            $this->assertTrue($this->queue->publish($command));
        }

        $expected = array_reverse($queue);
        $i = count($expected);
        while ($command = $this->queue->pull()) {
            $this->assertEquals($expected[--$i], $command);
        }

        $this->assertEquals(0, $i, 'Queue cleared');
        $this->assertNull($command, 'No commands in queue');
    }
}
