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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyContainerCommandHandlerLocator implements CommandHandlerLocator, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $command_handler_ids = [];

    /**
     * @param Command $command
     *
     * @return callable|null
     */
    public function findHandler(Command $command)
    {
        $command_name = get_class($command);

        if (!($this->container instanceof ContainerInterface || !isset($this->command_handler_ids[$command_name]))) {
            return null;
        }

        return $this->resolve($this->command_handler_ids[$command_name]);
    }

    /**
     * @param string $command_name
     * @param string $service
     * @param string $method
     */
    public function registerService($command_name, $service, $method = '__invoke')
    {
        $this->command_handler_ids[$command_name] = [$service, $method];
    }

    /**
     * @param array $handler
     *
     * @return callable|null
     */
    private function resolve(array $handler)
    {
        list($service, $method) = $handler;

        if (is_callable($service)) {
            return $service;
        }

        if (is_callable([$service, $method])) {
            return [$service, $method];
        }

        return null;
    }
}
