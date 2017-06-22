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
use Psr\Container\ContainerInterface;

class ContainerQueryHandlerLocator implements QueryHandlerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
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
     * @return callable|null
     */
    public function findHandler(Query $query)
    {
        return $this->lazyLoad(get_class($query));
    }

    /**
     * @param string $query_name
     * @param string $service
     * @param string $method
     */
    public function registerService($query_name, $service, $method = '__invoke')
    {
        $this->query_handler_ids[$query_name] = [$service, $method];
    }

    /**
     * @param $query_name
     *
     * @return callable
     */
    private function lazyLoad($query_name)
    {
        if (isset($this->query_handler_ids[$query_name])) {
            list($service, $method) = $this->query_handler_ids[$query_name];

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
    private function resolve($service, $method)
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
