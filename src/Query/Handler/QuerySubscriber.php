<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Handler;

interface QuerySubscriber
{
    /**
     * Get called methods for subscribed queries.
     *
     * <code>
     * [
     *  [<query_name>, <method_name>],
     * ]
     * </code>
     *
     * @return array
     */
    public static function getSubscribedQueries(): array;
}
