<?php

declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Query\Handler;

use GpsLab\Component\Query\Handler\QuerySubscriber;
use GpsLab\Component\Tests\Fixture\Query\ContactByIdentity;
use GpsLab\Component\Tests\Fixture\Query\ContactByNameQuery;

class ContestQuerySubscriber implements QuerySubscriber
{
    /**
     * @return array<class-string, string>
     */
    public static function getSubscribedQueries(): array
    {
        return [
            ContactByIdentity::class => 'getByIdentity',
            ContactByNameQuery::class => 'getByNameQuery',
        ];
    }

    /**
     * @param ContactByIdentity $query
     *
     * @return string
     */
    public function getByIdentity(ContactByIdentity $query): string
    {
        // return some data
        return get_class($query);
    }

    /**
     * @param ContactByNameQuery $query
     *
     * @return string
     */
    public function getByNameQuery(ContactByNameQuery $query): string
    {
        // return some data
        return get_class($query);
    }
}
