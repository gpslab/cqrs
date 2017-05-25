<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Handler;

use GpsLab\Component\Query\Query;

trait SwitchQueryHandlerTrait
{
    /**
     * @param Query $query
     *
     * @return mixed
     */
    public function handle(Query $query)
    {
        return call_user_func([$this, $this->getHandleMethod($query)], $query);
    }

    /**
     * @param Query $query
     *
     * @return string
     */
    private function getHandleMethod(Query $query)
    {
        $class = get_class($query);

        if ('Query' === substr($class, -5)) {
            $class = substr($class, 0, -5);
        }

        $class = str_replace('_', '\\', $class); // convert names for classes not in namespace
        $parts = explode('\\', $class);

        return 'handle'.end($parts);
    }
}
