<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Fixture\Query\Handler;

use GpsLab\Component\Tests\Fixture\Query\ContactByIdentity;

class ContactByIdentityHandler
{
    /**
     * @var ContactByIdentity|null
     */
    private $query;

    /**
     * @param ContactByIdentity $query
     */
    public function handleContactByIdentity(ContactByIdentity $query): void
    {
        $this->query = $query;
    }

    /**
     * @return ContactByIdentity|null
     */
    public function query(): ?ContactByIdentity
    {
        return $this->query;
    }
}
