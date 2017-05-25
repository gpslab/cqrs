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
use GpsLab\Component\Command\Handler\CommandHandler;
use GpsLab\Component\Command\Handler\Locator\CommandHandlerLocator;
use GpsLab\Component\Command\Exception\HandlerNotFoundException;

class HandlerLocatedCommandBus implements CommandBus
{
    /**
     * @var CommandHandlerLocator
     */
    private $locator;

    /**
     * @param CommandHandlerLocator $locator
     */
    public function __construct(CommandHandlerLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $handler = $this->locator->getCommandHandler($command);

        if (!($handler instanceof CommandHandler)) {
            throw HandlerNotFoundException::notFound($command);
        }

        $handler->handle($command);
    }
}
