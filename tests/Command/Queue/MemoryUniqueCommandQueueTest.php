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
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;

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
        $queue = [
            new CreateContact(),
            new RenameContactCommand(),
            new CreateContact(),        // duplicate
            new RenameContactCommand(), // duplicate
            new RenameContactCommand(), // duplicate
            new CreateContact(),        // duplicate
        ];
        $expected = [
            new CreateContact(),
            new RenameContactCommand(),
        ];

        foreach ($queue as $command) {
            $this->assertTrue($this->queue->push($command));
        }

        $i = count($expected);
        while ($command = $this->queue->pull()) {
            $this->assertEquals($expected[--$i], $command);
        }

        $this->assertEquals(0, $i, 'Queue cleared');
        $this->assertNull($command, 'No commands in queue');
    }
}
