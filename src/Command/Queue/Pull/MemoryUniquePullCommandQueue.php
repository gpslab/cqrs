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

class MemoryUniquePullCommandQueue implements PullCommandQueue
{
    /**
     * @var Command[]
     */
    private $commands = [];

    /**
     * Publish command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function publish(Command $command)
    {
        $index = array_search($command, $this->commands);

        // remove exists command and publish it again
        if (false !== $index) {
            unset($this->commands[$index]);
        }

        $this->commands[] = $command;

        return true;
    }

    /**
     * Pop command from queue. Return NULL if queue is empty.
     *
     * @return Command|null
     */
    public function pull()
    {
        return array_shift($this->commands);
    }
}
