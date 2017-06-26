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
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PredisPullCommandQueue implements PullCommandQueue
{
    const DEFAULT_FORMAT = 'predis';

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
     * @var string
     */
    private $format = '';

    /**
     * @param Client              $client
     * @param SerializerInterface $serializer
     * @param LoggerInterface     $logger
     * @param string              $queue_name
     * @param string|null         $format
     */
    public function __construct(
        Client $client,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        $queue_name,
        $format = null
    ) {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->queue_name = $queue_name;
        $this->format = $format ?: self::DEFAULT_FORMAT;
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
        $value = $this->serializer->serialize($command, $this->format);

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
            return $this->serializer->deserialize($value, Command::class, $this->format);
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
