<?php

/**
 * Carousel project.
 *
 * @author Peter Gribanov <PGribanov@1tv.com>
 */

namespace GpsLab\Component\Query\Handler\Locator;

use GpsLab\Component\Query\Handler\QueryHandler;
use GpsLab\Component\Query\Query;

class DirectBindingQueryHandlerLocator implements QueryHandlerLocator
{
    /**
     * @var QueryHandler[]
     */
    private $handlers = [];

    /**
     * Bind query handler to concrete query by class name.
     *
     * @param string       $query_name
     * @param QueryHandler $handler
     */
    public function registerHandler($query_name, QueryHandler $handler)
    {
        $this->handlers[$query_name] = $handler;
    }

    /**
     * @param Query $query
     *
     * @return QueryHandler|null
     */
    public function getQueryHandler(Query $query)
    {
        $query_name = get_class($query);

        return isset($this->handlers[$query_name]) ? $this->handlers[$query_name] : null;
    }
}
