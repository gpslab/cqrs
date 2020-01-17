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
    public function findHandler(Command $command): ?callable
    {
        return $this->lazyLoad(get_class($command));
    }

    /**
     * @param string $command_name
     * @param string $service
     * @param string $method
     */
    public function registerService(string $command_name, string $service, string $method = '__invoke'): void
    {
        $this->command_handler_ids[$command_name] = [$service, $method];
    }

    /**
     * @param string $command_name
     *
     * @return callable|null
     */
    private function lazyLoad(string $command_name): ?callable
    {
        if ($this->container instanceof ContainerInterface && isset($this->command_handler_ids[$command_name])) {
            [$service, $method] = $this->command_handler_ids[$command_name];

            return $this->resolve($this->container->get($service), $method);
        }

        return null;
    }

    /**
     * @param mixed  $service
     * @param string $method
     *
     * @return callable|null
     */
    private function resolve($service, string $method): ?callable
    {
        if (is_callable($service)) {
            return $service;
        }

        if (is_callable([$service, $method])) {
            return [$service, $method];
        }

        return null;
    }
}
