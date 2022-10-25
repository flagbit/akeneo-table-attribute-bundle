<?php

namespace Flagbit\Bundle\TableAttributeBundle\Normalizer;

use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AttributeOptionNormalizer implements NormalizerInterface
{
    /**
     * @var NormalizerInterface
     */
    private $baseNormalizer;

    public function __construct(NormalizerInterface $baseNormalizer)
    {
        $this->baseNormalizer = $baseNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $normalizedValues = $this->baseNormalizer->normalize($object, $format, $context);

        /**
 * @var AttributeOption $object
*/
        if ($object->isTableAttribute()) {
            $normalizedValues['type'] = $object->getType();
            $normalizedValues['type_config'] = $object->getTypeConfig();
            $normalizedValues['constraints'] = $object->getConstraints();
        }

        return $normalizedValues;
    }

    /**
     * @param  mixed $data
     * @param  null  $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->baseNormalizer->supportsNormalization($data, $format);
    }
}
