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

/**
 * Publish and Subscribe command queue.
 */
interface CommandQueue
{
    /**
     * Publish command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function publish(Command $command);

    /**
     * Subscribe on command queue.
     *
     * @param callable $handler
     */
    public function subscribe(callable $handler);
}
