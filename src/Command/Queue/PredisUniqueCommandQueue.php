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
use Symfony\Component\Serializer\Serializer;

class PredisUniqueCommandQueue implements CommandQueue
{
    const LIST_KEY = 'unique_commands';
    const FORMAT = PredisCommandQueue::FORMAT;

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
     * @param Client          $client
     * @param Serializer      $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, Serializer $serializer, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->logger = $logger;
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
        $value = $this->serializer->normalize($command, self::FORMAT);

        // remove exists command and push it again
        $this->client->lrem(self::LIST_KEY, 0, $value);

        return (bool) $this->client->rpush(self::LIST_KEY, [$value]);
    }

    /**
     * Pop command from queue. Return NULL if queue is empty.
     *
     * @return Command|null
     */
    public function pop()
    {
        $value = $this->client->lpop(self::LIST_KEY);

        if (!$value) {
            return null;
        }

        try {
            return $this->serializer->denormalize($value, Command::class, self::FORMAT);
        } catch (\Exception $e) {
            // it's a critical error
            // it is necessary to react quickly to it
            $this->logger->critical('Failed denormalize a command in the Redis queue', [$value, $e->getMessage()]);

            // try denormalize in later
            $this->client->rpush(self::LIST_KEY, [$value]);

            return null;
        }
    }
}
