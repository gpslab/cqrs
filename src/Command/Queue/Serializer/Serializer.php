<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue\Serializer;

interface Serializer
{
    /**
     * @param object $data
     *
     * @return string
     */
    public function serialize($data);

    /**
     * @param string $data
     *
     * @return object
     */
    public function deserialize($data);
}
