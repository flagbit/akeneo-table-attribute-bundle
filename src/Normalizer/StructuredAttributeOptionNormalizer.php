<?php

namespace Flagbit\Bundle\TableAttributeBundle\Normalizer;

use Akeneo\Pim\Structure\Component\Normalizer\InternalApi\StructuredAttributeOptionNormalizer as BaseNormalizer;

class StructuredAttributeOptionNormalizer extends BaseNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $normalizedValues = parent::normalize($object, $format, $context);
        $normalizedValues['type'] = $object->getType();
        $normalizedValues['type_config'] = $object->getTypeConfig();
        $normalizedValues['constraints'] = $object->getConstraints();

        return $normalizedValues;
    }
}
