<?php

namespace Flagbit\Bundle\TableAttributeBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;

class TableJsonTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($value)
    {
        return json_decode($value, true);
    }
}
