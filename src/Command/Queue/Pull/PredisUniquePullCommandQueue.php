<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue\Pull;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Queue\Serializer\Serializer;
use Predis\Client;
use Psr\Log\LoggerInterface;

class PredisUniquePullCommandQueue implements PullCommandQueue
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Serializer
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
     * @param Client          $client
     * @param Serializer      $serializer
     * @param LoggerInterface $logger
     * @param string          $queue_name
     */
    public function __construct(Client $client, Serializer $serializer, LoggerInterface $logger, $queue_name)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->queue_name = $queue_name;
    }

    /**
     * Publish command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function publish(Command $command)
    {
        $value = $this->serializer->serialize($command);

        // remove exists command and publish it again
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
            return $this->serializer->deserialize($value);
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
