<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Queue;

use GpsLab\Component\Command\Queue\MemoryUniqueCommandQueue;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\UpdateContactCommand;

class MemoryUniqueCommandQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MemoryUniqueCommandQueue
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new MemoryUniqueCommandQueue();
    }

    public function testQueue()
    {
        $create = new CreateContact();
        $update = new UpdateContactCommand();

        $queue = [
            $create,
            $update,
            $create, // duplicate
            $update, // duplicate
            $update, // duplicate
            $create, // duplicate
        ];
        $expected = [
            $create,
            $update,
        ];

        foreach ($queue as $command) {
            $this->assertTrue($this->queue->push($command));
        }

        $i = count($expected);
        while ($command = $this->queue->pop()) {
            $this->assertEquals($expected[--$i], $command);
        }

        $this->assertEquals(0, $i, 'Queue cleared');
        $this->assertNull($command, 'No commands in queue');
    }
}
