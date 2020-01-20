<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Handler\Locator;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Handler\CommandSubscriber;
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
     * @param string $service_name
     * @param string $class_name
     */
    public function registerSubscriberService(string $service_name, string $class_name): void
    {
        if ($class_name instanceof CommandSubscriber) {
            foreach ($class_name::getSubscribedCommands() as $command_name => $method) {
                $this->registerService($command_name, $service_name, $method);
            }
        }
    }

    /**
     * @param string $command_name
     *
     * @return callable|null
     */
    private function lazyLoad(string $command_name): ?callable
    {
        if (isset($this->command_handler_ids[$command_name])) {
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
