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

class SymfonySerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SerializerInterface
     */
    private $serializer;

    protected function setUp()
    {
        $this->serializer = $this->getMock(SerializerInterface::class);
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
            ->will($this->returnValue($result))
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
            ->will($this->returnValue($result))
        ;

        $serializer = new SymfonySerializer($this->serializer, $format);

        $this->assertEquals($result, $serializer->deserialize($data));
    }
}
