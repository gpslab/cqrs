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
     * Pop command from queue. Return NULL if queue is empty.
     *
     * @return Command|null
     */
    public function pop();
}
