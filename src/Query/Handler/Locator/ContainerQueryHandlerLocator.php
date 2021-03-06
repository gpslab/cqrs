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
use Psr\Container\ContainerInterface;

class ContainerQueryHandlerLocator implements QueryHandlerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array[]
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
    public function findHandler(Query $query): ?callable
    {
        return $this->lazyLoad(get_class($query));
    }

    /**
     * @param string $query_name
     * @param string $service
     * @param string $method
     */
    public function registerService(string $query_name, string $service, string $method = '__invoke'): void
    {
        $this->query_handler_ids[$query_name] = [$service, $method];
    }

    /**
     * @param string $service_name
     * @param string $class_name
     */
    public function registerSubscriberService(string $service_name, string $class_name): void
    {
        $get_subscribed_queries = [$class_name, 'getSubscribedQueries'];
        if (is_callable($get_subscribed_queries) && is_a($class_name, QuerySubscriber::class, true)) {
            foreach ($get_subscribed_queries() as $query_name => $method) {
                $this->registerService($query_name, $service_name, $method);
            }
        }
    }

    /**
     * @param string $query_name
     *
     * @return callable|null
     */
    private function lazyLoad(string $query_name): ?callable
    {
        if (isset($this->query_handler_ids[$query_name])) {
            [$service, $method] = $this->query_handler_ids[$query_name];

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
