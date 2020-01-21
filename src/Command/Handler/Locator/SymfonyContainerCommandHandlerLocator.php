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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyContainerCommandHandlerLocator implements CommandHandlerLocator, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array[]
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
     * @param string $service_name
     * @param string $class_name
     */
    public function registerSubscriberService(string $service_name, string $class_name): void
    {
        if (is_a($class_name, CommandSubscriber::class, true)) {
            foreach (forward_static_call([$class_name, 'getSubscribedCommands']) as $command_name => $method) {
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

        $handler = [$service, $method];

        if (is_callable($handler)) {
            return $handler;
        }

        return null;
    }
}
