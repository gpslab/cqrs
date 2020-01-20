<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Command\Queue\Subscribe;

use Exception;
use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Queue\Serializer\Serializer;
use GpsLab\Component\Command\Queue\Subscribe\PredisSubscribeCommandQueue;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Superbalist\PubSub\Redis\RedisPubSubAdapter;

class PredisSubscribeCommandQueueTest extends TestCase
{
    /**
     * @var MockObject|Command
     */
    private $command;

    /**
     * @var MockObject|RedisPubSubAdapter
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
     * @var PredisSubscribeCommandQueue
     */
    private $queue;

    /**
     * @var string
     */
    private $queue_name = 'commands';

    protected function setUp(): void
    {
        if (!class_exists(RedisPubSubAdapter::class)) {
            $this->markTestSkipped('php-pubsub-redis is not installed.');
        }

        $this->command = $this->createMock(Command::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->logger = $this->createMock(LoggerInterface::class);
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

    public function testPublish(): void
    {
        $massage = 'foo';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($this->command)
            ->willReturn($massage)
        ;

        $this->client
            ->expects($this->once())
            ->method('publish')
            ->with($this->queue_name, $massage)
        ;

        $this->assertTrue($this->queue->publish($this->command));
    }

    public function testSubscribe(): void
    {
        $subscriber_called = false;
        $handler = function ($command) use (&$subscriber_called): void {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertSame($this->command, $command);
            $subscriber_called = true;
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
            ->willReturnCallback(function ($queue_name, $handler_wrapper) use ($handler): void {
                $this->assertSame($this->queue_name, $queue_name);
                $this->assertIsCallable($handler_wrapper);

                $message = 'foo';
                $this->serializer
                    ->expects($this->once())
                    ->method('deserialize')
                    ->with($message)
                    ->willReturn($this->command)
                ;

                $handler_wrapper($message);
            })
        ;

        $this->queue->subscribe($handler);

        $this->assertTrue($subscriber_called);
    }

    public function testErrorOnDeserialize(): void
    {
        $subscriber_called = false;
        $handler = function ($command) use (&$subscriber_called): void {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertSame($this->command, $command);
            $subscriber_called = true;
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
            ->willReturnCallback(function ($queue_name, $handler_wrapper) use ($handler): void {
                $this->assertSame($this->queue_name, $queue_name);
                $this->assertIsCallable($handler_wrapper);

                $exception = new \Exception('bar');
                $message = 'foo';
                $this->serializer
                    ->expects($this->once())
                    ->method('deserialize')
                    ->with($message)
                    ->willThrowException($exception)
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

                $handler_wrapper($message);
            })
        ;

        $this->queue->subscribe($handler);

        $this->assertFalse($subscriber_called);
    }

    public function testDeserializeBadResult(): void
    {
        $subscriber_called = false;
        $handler = function ($command) use (&$subscriber_called): void {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertEquals($this->command, $command);
            $subscriber_called = true;
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
            ->willReturnCallback(function ($queue_name, $handler_wrapper): void {
                $this->assertEquals($this->queue_name, $queue_name);
                $this->assertIsCallable($handler_wrapper);

                $result = new \stdClass();
                $message = 'foo';
                $exception_message = sprintf(
                    'The denormalization command is expected "%s", got "%s" inside.',
                    Command::class,
                    \stdClass::class
                );
                $this->serializer
                    ->expects($this->once())
                    ->method('deserialize')
                    ->with($message)
                    ->willReturn($result)
                ;

                $this->logger
                    ->expects($this->once())
                    ->method('critical')
                    ->with(
                        'Failed denormalize a command in the Redis queue',
                        [$message, $exception_message]
                    )
                ;

                $this->client
                    ->expects($this->once())
                    ->method('publish')
                    ->with($this->queue_name, $message)
                ;

                $handler_wrapper($message);
            })
        ;

        $this->queue->subscribe($handler);

        $this->assertFalse($subscriber_called);
    }

    public function testSubscribeHandlerFailure(): void
    {
        $this->expectException(Exception::class);

        $exception = new \Exception('bar');
        $handler = function ($command) use ($exception): void {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertSame($this->command, $command);

            throw $exception;
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
            ->willReturnCallback(function ($queue_name, $handler_wrapper) use ($handler): void {
                $this->assertSame($this->queue_name, $queue_name);
                $this->assertIsCallable($handler_wrapper);

                $message = 'foo';
                $this->serializer
                    ->expects($this->once())
                    ->method('deserialize')
                    ->with($message)
                    ->willReturn($this->command)
                ;

                $handler_wrapper($message);
            })
        ;

        $this->queue->subscribe($handler);
    }

    public function testLazeSubscribe(): void
    {
        $handler1 = function ($command): void {
            $this->assertInstanceOf(Command::class, $command);
            $this->assertSame($this->command, $command);
        };
        $handler2 = static function (Command $command): void {
        };

        $this->client
            ->expects($this->once())
            ->method('subscribe')
        ;

        $this->assertFalse($this->queue->unsubscribe($handler1));
        $this->assertFalse($this->queue->unsubscribe($handler2));

        $this->queue->subscribe($handler1);

        $this->assertTrue($this->queue->unsubscribe($handler1));
        $this->assertFalse($this->queue->unsubscribe($handler1));

        $this->queue->subscribe($handler2);

        $this->assertTrue($this->queue->unsubscribe($handler2));
        $this->assertFalse($this->queue->unsubscribe($handler2));
    }
}
