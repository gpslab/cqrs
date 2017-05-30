<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Queue;

use GpsLab\Component\Command\Queue\MemoryCommandQueue;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;

class MemoryCommandQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MemoryCommandQueue
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new MemoryCommandQueue();
    }

    public function testQueue()
    {
        $queue = [
            new CreateContact(),
            new RenameContactCommand(),
            new CreateContact(), // duplicate
        ];

        foreach ($queue as $command) {
            $this->assertTrue($this->queue->push($command));
        }

        $expected = array_reverse($queue);
        $i = count($expected);
        while ($command = $this->queue->pop()) {
            $this->assertEquals($expected[--$i], $command);
        }

        $this->assertEquals(0, $i, 'Queue cleared');
        $this->assertNull($command, 'No commands in queue');
    }
}
