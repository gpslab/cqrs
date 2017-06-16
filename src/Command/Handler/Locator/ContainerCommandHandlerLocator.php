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
use Psr\Container\ContainerInterface;

class ContainerCommandHandlerLocator implements CommandHandlerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
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
     * @return callable|null
     */
    public function findHandler(Command $command)
    {
        return $this->lazyLoad(get_class($command));
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
     * @param $command_name
     *
     * @return callable
     */
    private function lazyLoad($command_name)
    {
        if (isset($this->command_handler_ids[$command_name])) {
            list($service, $method) = $this->command_handler_ids[$command_name];
            $handler = $this->container->get($service);

            if (is_callable($handler)) {
                return $handler;
            } elseif (is_callable([$handler, $method])) {
                return [$handler, $method];
            }
        }

        return null;
    }
}
