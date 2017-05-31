<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Handler\Locator;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\CommandHandler;

interface CommandHandlerLocator
{
    /**
     * @param Command $command
     *
     * @return CommandHandler|null
     */
    public function findHandler(Command $command);
}
