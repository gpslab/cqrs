<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Handler;

use GpsLab\Component\Command\Command;

interface CommandHandler
{
    /**
     * @param Command $command
     */
    public function handle(Command $command);
}
