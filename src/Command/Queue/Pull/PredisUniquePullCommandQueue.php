<?php
declare(strict_types=1);

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
    private $queue_name;

    /**
     * @param Client          $client
     * @param Serializer      $serializer
     * @param LoggerInterface $logger
     * @param string          $queue_name
     */
    public function __construct(Client $client, Serializer $serializer, LoggerInterface $logger, string $queue_name)
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
    public function publish(Command $command): bool
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
    public function pull(): ?Command
    {
        $value = $this->client->lpop($this->queue_name);

        if (!$value) {
            return null;
        }

        try {
            $command = $this->serializer->deserialize($value);

            if ($command instanceof Command) {
                return $command;
            }

            throw new \RuntimeException(sprintf('The denormalization command is expected "%s", got "%s" inside.', Command::class, get_class($command)));
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
