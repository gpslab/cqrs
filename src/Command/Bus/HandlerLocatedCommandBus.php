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
use GpsLab\Component\Command\Exception\HandlerNotFoundException;
use GpsLab\Component\Command\Handler\Locator\CommandHandlerLocator;

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
    public function handle(Command $command): void
    {
        $handler = $this->locator->findHandler($command);

        if (!is_callable($handler)) {
            throw HandlerNotFoundException::notFound($command);
        }

        $handler($command);
    }
}
