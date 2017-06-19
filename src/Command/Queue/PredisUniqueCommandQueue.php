<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue;

use GpsLab\Component\Command\Command;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PredisUniqueCommandQueue implements CommandQueue
{
    const FORMAT = PredisCommandQueue::FORMAT;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $queue_name = '';

    /**
     * @param Client              $client
     * @param SerializerInterface $serializer
     * @param LoggerInterface     $logger
     * @param string              $queue_name
     */
    public function __construct(Client $client, SerializerInterface $serializer, LoggerInterface $logger, $queue_name)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->queue_name = $queue_name;
    }

    /**
     * Push command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function push(Command $command)
    {
        $value = $this->serializer->serialize($command, self::FORMAT);

        // remove exists command and push it again
        $this->client->lrem($this->queue_name, 0, $value);

        return (bool) $this->client->rpush($this->queue_name, [$value]);
    }

    /**
     * Pop command from queue. Return NULL if queue is empty.
     *
     * @return Command|null
     */
    public function pull()
    {
        $value = $this->client->lpop($this->queue_name);

        if (!$value) {
            return null;
        }

        try {
            return $this->serializer->deserialize($value, Command::class, self::FORMAT);
        } catch (\Exception $e) {
            // it's a critical error
            // it is necessary to react quickly to it
            $this->logger->critical('Failed denormalize a command in the Redis queue', [$value, $e->getMessage()]);

            // try denormalize in later
            $this->client->rpush($this->queue_name, [$value]);

            return null;
        }
    }
}
