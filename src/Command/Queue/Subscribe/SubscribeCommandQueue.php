<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue\Subscribe;

use GpsLab\Component\Command\Queue\CommandQueue;

/**
 * Publish and Subscribe command queue.
 */
interface SubscribeCommandQueue extends CommandQueue
{
    /**
     * Subscribe on command queue.
     *
     * @param callable $handler
     */
    public function subscribe(callable $handler): void;

    /**
     * Unsubscribe on command queue.
     *
     * @param callable $handler
     *
     * @return bool
     */
    public function unsubscribe(callable $handler): bool;
}
