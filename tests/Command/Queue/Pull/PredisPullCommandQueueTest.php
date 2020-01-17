<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Queue\Pull;

use GpsLab\Component\Command\Queue\Pull\PredisPullCommandQueue;
use GpsLab\Component\Command\Queue\Serializer\Serializer;
use GpsLab\Component\Tests\Fixture\Command\CreateContact;
use GpsLab\Component\Tests\Fixture\Command\RenameContactCommand;
use Predis\Client;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PredisPullCommandQueueTest extends TestCase
{
    /**
     * @var MockObject|Client
     */
    private $client;

    /**
     * @var MockObject|Serializer
     */
    private $serializer;

    /**
     * @var MockObject|LoggerInterface
     */
    private $logger;

    /**
     * @var PredisPullCommandQueue
     */
    private $queue;

    /**
     * @var string
     */
    private $queue_name = 'commands';

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->queue = new PredisPullCommandQueue($this->client, $this->serializer, $this->logger, $this->queue_name);
    }

    public function testPushQueue()
    {
        $queue = [
            new RenameContactCommand(),
            new CreateContact(),
            new RenameContactCommand(), // duplicate
        ];

        $i = 0;
        foreach ($queue as $command) {
            $value = $i.spl_object_hash($command);

            $this->serializer
                ->expects($this->at($i))
                ->method('serialize')
                ->with($command)
                ->willReturn($value)
            ;

            $this->client
                ->expects($this->at($i))
                ->method('__call')
                ->with('rpush', [$this->queue_name, [$value]])
                ->willReturn(1)
            ;
            ++$i;
        }

        foreach ($queue as $command) {
            $this->assertTrue($this->queue->publish($command));
        }
    }

    public function testPopQueue()
    {
        $queue = [
            new RenameContactCommand(),
            new CreateContact(),
            new RenameContactCommand(), // duplicate
        ];

        $i = 0;
        foreach ($queue as $command) {
            $value = $i.spl_object_hash($command);

            $this->serializer
                ->expects($this->at($i))
                ->method('deserialize')
                ->with($value)
                ->willReturn($command)
            ;

            $this->client
                ->expects($this->at($i))
                ->method('__call')
                ->with('lpop', [$this->queue_name])
                ->willReturn($value)
            ;
            ++$i;
        }
        $this->client
            ->expects($this->at($i))
            ->method('__call')
            ->with('lpop', [$this->queue_name])
            ->willReturn(null)
        ;

        $expected = array_reverse($queue);
        $i = count($expected);
        while ($command = $this->queue->pull()) {
            $this->assertEquals($expected[--$i], $command);
        }

        $this->assertEquals(0, $i, 'Queue cleared');
        $this->assertNull($command, 'No commands in queue');
    }

    public function testFailedDeserialize()
    {
        $exception = new \Exception('foo');
        $command = new RenameContactCommand();
        $value = spl_object_hash($command);

        $this->client
            ->expects($this->at(0))
            ->method('__call')
            ->with('lpop', [$this->queue_name])
            ->willReturn($value)
        ;
        $this->client
            ->expects($this->at(1))
            ->method('__call')
            ->with('rpush', [$this->queue_name, [$value]])
            ->willReturn(1)
        ;

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($value)
            ->willThrowException($exception)
        ;

        $this->logger
            ->expects($this->once())
            ->method('critical')
            ->with('Failed denormalize a command in the Redis queue', [$value, $exception->getMessage()])
            ->willReturn(1)
        ;

        $this->assertNull($this->queue->pull());
    }
}
