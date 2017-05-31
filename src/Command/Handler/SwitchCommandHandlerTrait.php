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

trait SwitchCommandHandlerTrait
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        call_user_func([$this, $this->makeHandleMethodName($command)], $command);
    }

    /**
     * @param Command $command
     *
     * @return string
     */
    private function makeHandleMethodName(Command $command)
    {
        $class = get_class($command);

        if ('Command' === substr($class, -7)) {
            $class = substr($class, 0, -7);
        }

        $class = str_replace('_', '\\', $class); // convert names for classes not in namespace
        $parts = explode('\\', $class);

        return 'handle'.end($parts);
    }
}
