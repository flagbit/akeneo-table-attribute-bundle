<?php

namespace Flagbit\Bundle\TableAttributeBundle\Normalizer;

use Pim\Bundle\EnrichBundle\Normalizer\AttributeOptionNormalizer as BaseNormalizer;

/**
 * Attribute option normalizer for internal api
 *
 * @package Flagbit\Bundle\TableAttributeBundle\Normalizer
 * @author Ruben Beglaryan <ruben.beglaryan@flagbit.de>
 */
class AttributeOptionNormalizer extends BaseNormalizer
{

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {

        $normalizedValues = parent::normalize($object, $format, $context);
        $normalizedValues['type_config'] = $object->getTypeConfig();
        $normalizedValues['constraints'] = $object->getConstraints();


        return $normalizedValues;
    }
}
