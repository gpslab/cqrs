<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Handler\Locator;

use GpsLab\Component\Query\Handler\QuerySubscriber;
use GpsLab\Component\Query\Query;

class DirectBindingQueryHandlerLocator implements QueryHandlerLocator
{
    /**
     * @var callable[]
     */
    private $handlers = [];

    /**
     * Bind query handler to concrete query by class name.
     *
     * @param string   $query_name
     * @param callable $handler
     */
    public function registerHandler(string $query_name, callable $handler): void
    {
        $this->handlers[$query_name] = $handler;
    }

    /**
     * @param QuerySubscriber $subscriber
     */
    public function registerSubscriber(QuerySubscriber $subscriber): void
    {
        foreach ($subscriber::getSubscribedQueries() as $query_name => $methods) {
            foreach ($methods as $method) {
                $this->registerHandler($query_name, [$subscriber, $method]);
            }
        }
    }

    /**
     * @param Query $query
     *
     * @return callable|null
     */
    public function findHandler(Query $query): ?callable
    {
        return $this->handlers[get_class($query)] ?? null;
    }
}
