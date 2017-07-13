<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Queue\Serializer;

use GpsLab\Component\Command\Command;
use Symfony\Component\Serializer\SerializerInterface;

class SymfonySerializer implements Serializer
{
    const DEFAULT_FORMAT = 'predis';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $format = self::DEFAULT_FORMAT;

    /**
     * @param SerializerInterface $serializer
     * @param string|null         $format
     */
    public function __construct(SerializerInterface $serializer, $format = null)
    {
        $this->serializer = $serializer;
        $this->format = $format ?: self::DEFAULT_FORMAT;
    }

    /**
     * @param object $data
     *
     * @return string
     */
    public function serialize($data)
    {
        return $this->serializer->serialize($data, $this->format);
    }

    /**
     * @param string $data
     *
     * @return object
     */
    public function deserialize($data)
    {
        return $this->serializer->deserialize($data, Command::class, $this->format);
    }
}
