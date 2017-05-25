<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Handler\Locator;

use GpsLab\Component\Query\Handler\QueryHandler;
use GpsLab\Component\Query\Query;
use Psr\Container\ContainerInterface;

class ContainerQueryHandlerLocator implements QueryHandlerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string[]
     */
    private $query_handler_ids = [];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Query $query
     *
     * @return QueryHandler|null
     */
    public function getQueryHandler(Query $query)
    {
        return $this->lazyLoad(get_class($query));
    }

    /**
     * @param string $query_name
     * @param string $service
     */
    public function registerService($query_name, $service)
    {
        $this->query_handler_ids[$query_name] = $service;
    }

    /**
     * @param $query_name
     *
     * @return QueryHandler
     */
    private function lazyLoad($query_name)
    {
        if (isset($this->query_handler_ids[$query_name])) {
            $handler = $this->container->get($this->query_handler_ids[$query_name]);

            if ($handler instanceof QueryHandler) {
                return $handler;
            }
        }

        return null;
    }
}
