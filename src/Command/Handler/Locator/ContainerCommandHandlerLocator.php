<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Locator\Handler;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\CommandHandler;
use GpsLab\Component\Command\Handler\Locator\CommandHandlerLocator;
use Psr\Container\ContainerInterface;

class ContainerCommandHandlerLocator implements CommandHandlerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string[]
     */
    private $command_handler_ids = [];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Command $command
     *
     * @return CommandHandler|null
     */
    public function getCommandHandler(Command $command)
    {
        return $this->lazyLoad(get_class($command));
    }

    /**
     * @param string $command_name
     * @param string $service
     */
    public function registerService($command_name, $service)
    {
        $this->command_handler_ids[$command_name] = $service;
    }

    /**
     * @param $command_name
     *
     * @return CommandHandler
     */
    private function lazyLoad($command_name)
    {
        if (isset($this->command_handler_ids[$command_name])) {
            $handler = $this->container->get($this->command_handler_ids[$command_name]);

            if ($handler instanceof CommandHandler) {
                return $handler;
            }
        }

        return null;
    }
}
