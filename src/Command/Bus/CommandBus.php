<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Bus;

use GpsLab\Component\Command\Command;

interface CommandBus
{
    /**
     * @param Command $command
     */
    public function handle(Command $command);
}
