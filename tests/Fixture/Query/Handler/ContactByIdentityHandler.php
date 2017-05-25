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
use GpsLab\Component\Tests\Fixture\Query\ContactByIdentity;

class ContactByIdentityHandler extends SwitchQueryHandler
{
    /**
     * @var ContactByIdentity|null
     */
    private $query;

    /**
     * @param ContactByIdentity $query
     */
    protected function handleContactByIdentity(ContactByIdentity $query)
    {
        $this->query = $query;
    }

    /**
     * @return ContactByIdentity|null
     */
    public function query()
    {
        return $this->query;
    }
}
