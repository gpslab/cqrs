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

class DirectBindingCommandHandlerLocator implements CommandHandlerLocator
{
    /**
     * @var callable[]
     */
    private $handlers = [];

    /**
     * Bind command handler to concrete command by class name.
     *
     * @param string   $command_name
     * @param callable $handler
     */
    public function registerHandler($command_name, callable $handler)
    {
        $this->handlers[$command_name] = $handler;
    }

    /**
     * @param Command $command
     *
     * @return callable|null
     */
    public function findHandler(Command $command)
    {
        $command_name = get_class($command);

        return isset($this->handlers[$command_name]) ? $this->handlers[$command_name] : null;
    }
}
