<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace  GpsLab\Component\Tests\Command\Queue\Serializer;

use GpsLab\Component\Command\Command;
use GpsLab\Component\Command\Queue\Serializer\SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SymfonySerializerTest extends TestCase
{
    /**
     * @var MockObject|SerializerInterface
     */
    private $serializer;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    /**
     * @return array
     */
    public function formats()
    {
        return [
            [null, 'predis'],
            ['json', 'json'],
        ];
    }

    /**
     * @dataProvider formats
     *
     * @param string $format
     * @param string $expected_format
     */
    public function testSerialize($format, $expected_format)
    {
        $data = new \stdClass();
        $result = 'foo';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($data, $expected_format)
            ->willReturn($result)
        ;

        $serializer = new SymfonySerializer($this->serializer, $format);

        $this->assertEquals($result, $serializer->serialize($data));
    }

    /**
     * @dataProvider formats
     *
     * @param string $format
     * @param string $expected_format
     */
    public function testDeserialize($format, $expected_format)
    {
        $data = 'foo';
        $result = new \stdClass();

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($data, Command::class, $expected_format)
            ->willReturn($result)
        ;

        $serializer = new SymfonySerializer($this->serializer, $format);

        $this->assertEquals($result, $serializer->deserialize($data));
    }
}
