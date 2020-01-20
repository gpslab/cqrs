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
    public function registerHandler(string $command_name, callable $handler): void
    {
        $this->handlers[$command_name] = $handler;
    }

    /**
     * @param CommandSubscriber $subscriber
     */
    public function registerSubscriberService(CommandSubscriber $subscriber): void
    {
        foreach ($subscriber::getSubscribedCommands() as $command_name => $methods) {
            foreach ($methods as $method) {
                $this->registerHandler($command_name, [$subscriber, $method]);
            }
        }
    }

    /**
     * @param Command $command
     *
     * @return callable|null
     */
    public function findHandler(Command $command): ?callable
    {
        return $this->handlers[get_class($command)] ?? null;
    }
}
