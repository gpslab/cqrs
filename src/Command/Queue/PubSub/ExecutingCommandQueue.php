<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue\PubSub;

use GpsLab\Component\Command\Command;

class ExecutingCommandQueue implements CommandQueue
{
    /**
     * @var callable|null
     */
    private $handler;

    /**
     * Publish command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function publish(Command $command)
    {
        // absence of a handler is not a error
        if (is_callable($this->handler)) {
            call_user_func($this->handler, $command);
        }

        return true;
    }

    /**
     * Subscribe on command queue.
     *
     * @param callable $handler
     */
    public function subscribe(callable $handler)
    {
        $this->handler = $handler;
    }
}
