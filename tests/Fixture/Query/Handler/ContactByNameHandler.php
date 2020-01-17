<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Query\Handler;

use GpsLab\Component\Tests\Fixture\Query\ContactByNameQuery;

class ContactByNameHandler
{
    /**
     * @var ContactByNameQuery|null
     */
    private $query;

    /**
     * @param ContactByNameQuery $query
     */
    public function handleContactByName(ContactByNameQuery $query): void
    {
        $this->query = $query;
    }

    /**
     * @return ContactByNameQuery|null
     */
    public function query(): ?ContactByNameQuery
    {
        return $this->query;
    }
}
