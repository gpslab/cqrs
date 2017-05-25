<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Handler\Locator;

use GpsLab\Component\Command\Handler\CommandHandler;
use GpsLab\Component\Command\Command;

class DirectBindingCommandHandlerLocator implements CommandHandlerLocator
{
    /**
     * @var CommandHandler[]
     */
    private $handlers = [];

    /**
     * Bind command handler to concrete command by class name.
     *
     * @param string         $command_name
     * @param CommandHandler $handler
     */
    public function registerHandler($command_name, CommandHandler $handler)
    {
        $this->handlers[$command_name] = $handler;
    }

    /**
     * @param Command $command
     *
     * @return CommandHandler|null
     */
    public function getCommandHandler(Command $command)
    {
        $command_name = get_class($command);

        return isset($this->handlers[$command_name]) ? $this->handlers[$command_name] : null;
    }
}
