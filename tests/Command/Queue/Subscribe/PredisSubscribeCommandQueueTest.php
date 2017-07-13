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
use GpsLab\Component\Command\Queue\Serializer\Serializer;
use GpsLab\Component\Command\Queue\Subscribe\PredisSubscribeCommandQueue;
use Psr\Log\LoggerInterface;
use Superbalist\PubSub\Redis\RedisPubSubAdapter;

class PredisSubscribeCommandQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RedisPubSubAdapter
     */
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Serializer
     */
    private $serializer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface
     */
    private $logger;

    /**
     * @var PredisSubscribeCommandQueue
     */
    private $queue;

    /**
     * @var string
     */
    private $queue_name = 'commands';

    protected function setUp()
    {
        if (!class_exists(RedisPubSubAdapter::class)) {
            $this->markTestSkipped('php-pubsub-redis is not installed.');
        }

        $this->command = $this->getMock(Command::class);
        $this->serializer = $this->getMock(Serializer::class);
        $this->logger = $this->getMock(LoggerInterface::class);
        $this->client = $this
            ->getMockBuilder(RedisPubSubAdapter::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->queue = new PredisSubscribeCommandQueue(
            $this->client,
            $this->serializer,
            $this->logger,
            $this->queue_name
        );
    }

    public function testPublish()
    {
        $massage = 'foo';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($this->command)
            ->will($this->returnValue($massage))
        ;

        $this->client
            ->expects($this->once())
            ->method('publish')
            ->with($this->queue_name, $massage)
        ;

        $this->assertTrue($this->queue->publish($this->command));
    }

    public function testSubscribe()
    {
        $subscriber_called = false;
        $handler = function ($command) use (&$subscriber_called) {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertEquals($this->command, $command);
            $subscriber_called = true;
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
            ->will($this->returnCallback(function ($queue_name, $handler_wrapper) use ($handler) {
                $this->assertEquals($this->queue_name, $queue_name);
                $this->assertTrue(is_callable($handler_wrapper));

                $message = 'foo';
                $this->serializer
                    ->expects($this->once())
                    ->method('deserialize')
                    ->with($message)
                    ->will($this->returnValue($this->command))
                ;

                call_user_func($handler_wrapper, $message);
            }))
        ;

        $this->queue->subscribe($handler);

        $this->assertTrue($subscriber_called);
    }

    public function testSubscribeFailure()
    {
        $subscriber_called = false;
        $handler = function ($command) use (&$subscriber_called) {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertEquals($this->command, $command);
            $subscriber_called = true;
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
            ->will($this->returnCallback(function ($queue_name, $handler_wrapper) use ($handler) {
                $this->assertEquals($this->queue_name, $queue_name);
                $this->assertTrue(is_callable($handler_wrapper));

                $exception = new \Exception('bar');
                $message = 'foo';
                $this->serializer
                    ->expects($this->once())
                    ->method('deserialize')
                    ->with($message)
                    ->will($this->throwException($exception))
                ;

                $this->logger
                    ->expects($this->once())
                    ->method('critical')
                    ->with(
                        'Failed denormalize a command in the Redis queue',
                        [$message, $exception->getMessage()]
                    )
                ;

                $this->client
                    ->expects($this->once())
                    ->method('publish')
                    ->with($this->queue_name, $message)
                ;

                call_user_func($handler_wrapper, $message);
            }))
        ;

        $this->queue->subscribe($handler);

        $this->assertFalse($subscriber_called);
    }

    /**
     * @expectedException \Exception
     */
    public function testSubscribeHandlerFailure()
    {
        $exception = new \Exception('bar');
        $handler = function ($command) use ($exception) {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertEquals($this->command, $command);
            throw $exception;
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
            ->will($this->returnCallback(function ($queue_name, $handler_wrapper) use ($handler) {
                $this->assertEquals($this->queue_name, $queue_name);
                $this->assertTrue(is_callable($handler_wrapper));

                $message = 'foo';
                $this->serializer
                    ->expects($this->once())
                    ->method('deserialize')
                    ->with($message)
                    ->will($this->returnValue($this->command))
                ;

                call_user_func($handler_wrapper, $message);
            }))
        ;

        $this->queue->subscribe($handler);
    }

    public function testLazeSubscribe()
    {
        $handler = function ($command) {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertEquals($this->command, $command);
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
        ;

        $this->queue->subscribe($handler);
        $this->queue->subscribe(function ($command) {
        });
    }
}
