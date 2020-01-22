<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Bus;

use GpsLab\Component\Query\Exception\HandlerNotFoundException;
use GpsLab\Component\Query\Handler\Locator\QueryHandlerLocator;
use GpsLab\Component\Query\Query;

class HandlerLocatedQueryBus implements QueryBus
{
    /**
     * @var QueryHandlerLocator
     */
    private $locator;

    /**
     * @param QueryHandlerLocator $locator
     */
    public function __construct(QueryHandlerLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param Query $query
     *
     * @return mixed
     */
    public function handle(Query $query)
    {
        $handler = $this->locator->findHandler($query);

        if (!is_callable($handler)) {
            throw HandlerNotFoundException::notFound($query);
        }

        return $handler($query);
    }
}
