<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Handler\Locator;

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
    public function registerHandler($query_name, callable $handler)
    {
        $this->handlers[$query_name] = $handler;
    }

    /**
     * @param Query $query
     *
     * @return callable|null
     */
    public function findHandler(Query $query)
    {
        $query_name = get_class($query);

        return isset($this->handlers[$query_name]) ? $this->handlers[$query_name] : null;
    }
}
