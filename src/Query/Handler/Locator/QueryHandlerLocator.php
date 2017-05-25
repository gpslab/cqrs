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

interface QueryHandlerLocator
{
    /**
     * @param Query $query
     *
     * @return QueryHandler|null
     */
    public function getQueryHandler(Query $query);
}
