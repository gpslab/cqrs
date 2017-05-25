<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Query\Handler;

use GpsLab\Component\Query\Handler\SwitchQueryHandler;
use GpsLab\Component\Tests\Fixture\Query\ContactByNameQuery;

class ContactByNameHandler extends SwitchQueryHandler
{
    /**
     * @var ContactByNameQuery|null
     */
    private $query;

    /**
     * @param ContactByNameQuery $query
     */
    protected function handleContactByName(ContactByNameQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return ContactByNameQuery|null
     */
    public function query()
    {
        return $this->query;
    }
}
