<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue\PullPush;

use GpsLab\Component\Command\Command;

/**
 * Push and Pull command queue.
 */
interface CommandQueue
{
    /**
     * Push command to queue.
     *
     * @param Command $command
     *
     * @return bool
     */
    public function push(Command $command);

    /**
     * Pull command from queue. Return NULL if queue is empty.
     *
     * @return Command|null
     */
    public function pull();
}
