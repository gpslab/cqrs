<?php
declare(strict_types=1);

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
    public const DEFAULT_FORMAT = 'predis';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $format;

    /**
     * @param SerializerInterface $serializer
     * @param string              $format
     */
    public function __construct(SerializerInterface $serializer, string $format = self::DEFAULT_FORMAT)
    {
        $this->serializer = $serializer;
        $this->format = $format;
    }

    /**
     * @param object $data
     *
     * @return string
     */
    public function serialize($data): string
    {
        return $this->serializer->serialize($data, $this->format);
    }

    /**
     * @param string $data
     *
     * @return object
     */
    public function deserialize(string $data)
    {
        $result = $this->serializer->deserialize($data, Command::class, $this->format);

        if (!is_object($result)) {
            throw new \RuntimeException(sprintf('Failed deserialize data "%s"', $data));
        }

        return $result;
    }
}
