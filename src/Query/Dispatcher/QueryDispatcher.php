<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Dispatcher;

use GpsLab\Component\Query\Query;

interface QueryDispatcher
{
    /**
     * @param Query $query
     *
     * @return mixed
     */
    public function handle(Query $query);
}
