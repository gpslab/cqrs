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
use GpsLab\Component\Command\Queue\CommandQueue;

interface PullCommandQueue extends CommandQueue
{
    /**
     * Pull command from queue. Return NULL if queue is empty.
     *
     * @return Command|null
     */
    public function pull();
}
