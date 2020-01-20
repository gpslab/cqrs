<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Query\Exception;

use GpsLab\Component\Query\Query;

final class HandlerNotFoundException extends \RuntimeException
{
    /**
     * @param Query $query
     *
     * @return self
     */
    public static function notFound(Query $query): self
    {
        $parts = explode('\\', get_class($query));

        return new self(sprintf('Not found handler for query "%s".', end($parts)));
    }
}
