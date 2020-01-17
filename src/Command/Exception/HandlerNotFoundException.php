<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Exception;

use GpsLab\Component\Command\Command;

final class HandlerNotFoundException extends \RuntimeException
{
    /**
     * @param Command $command
     *
     * @return self
     */
    public static function notFound(Command $command): self
    {
        $parts = explode('\\', get_class($command));

        return new self(sprintf('Not found handler for command "%s".', end($parts)));
    }
}
