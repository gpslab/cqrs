<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Queue\Pull;

use GpsLab\Component\Command\Queue\Pull\MemoryUniquePullCommandQueue;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;
use PHPUnit\Framework\TestCase;

class MemoryUniquePullCommandQueueTest extends TestCase
{
    /**
     * @var MemoryUniquePullCommandQueue
     */
    private $queue;

    protected function setUp(): void
    {
        $this->queue = new MemoryUniquePullCommandQueue();
    }

    public function testQueue(): void
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
            $this->assertTrue($this->queue->publish($command));
        }

        $i = count($expected);
        while ($command = $this->queue->pull()) {
            $this->assertEquals($expected[--$i], $command);
        }

        $this->assertEquals(0, $i, 'Queue cleared');
        $this->assertNull($command, 'No commands in queue');
    }
}
