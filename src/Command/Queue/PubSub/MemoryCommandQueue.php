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

class MemoryCommandQueue implements CommandQueue
{
    /**
     * @var callable|null
     */
    private $callback;

    /**
     * Push command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function publish(Command $command)
    {
        // absence of a subscriber is not an error
        if (is_callable($this->callback)) {
            call_user_func($this->callback, $command);
        }

        return true;
    }

    /**
     * Subscribe on command queue.
     *
     * @param callable $callback
     */
    public function subscribe(callable $callback)
    {
        $this->callback = $callback;
    }
}
