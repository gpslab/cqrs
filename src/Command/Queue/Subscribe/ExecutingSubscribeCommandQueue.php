<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue\Subscribe;

use GpsLab\Component\Command\Command;

class ExecutingSubscribeCommandQueue implements SubscribeCommandQueue
{
    /**
     * @var callable[]
     */
    private $handlers = [];

    /**
     * Publish command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function publish(Command $command)
    {
        // absence of a handlers is not a error
        foreach ($this->handlers as $handler) {
            call_user_func($handler, $command);
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
        $this->handlers[] = $handler;
    }

    /**
     * Unsubscribe on command queue.
     *
     * @param callable $handler
     *
     * @return bool
     */
    public function unsubscribe(callable $handler)
    {
        $index = array_search($handler, $this->handlers);

        if ($index === false) {
            return false;
        }

        unset($this->handlers[$index]);

        return true;
    }
}
