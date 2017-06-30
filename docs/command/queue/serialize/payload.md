Payload serializer
==================

If you use the [Payload package](https://github.com/gpslab/payload), you can simplify the serialization of your
specific commands.

```php
use GpsLab\Component\Command\Command;
use GpsLab\Component\Payload\Payload;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RenameArticleCommandSerializer implements NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof RenameArticleCommand;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'type' => 'RenameArticle',
            'payload' => $object->payload(),
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if ($data['type'] !== 'RenameArticle' || $class !== RenameArticleCommand::class) {
            throw new UnsupportedException();
        }

        return new RenameArticleCommand($data['payload']);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Command::class && isset($data['type'], $data['payload']) && $data['type'] === 'RenameArticle';
    }
}
```

You can use [universal serializers](https://github.com/gpslab/payload#serialize) from Payload package and wrap it.
Remember that the `$type` and `$class` for denormalization will always be equal to `GpsLab\Component\Command\Command`
in `PredisCommandQueue` and `PredisUniqueCommandQueue`.
