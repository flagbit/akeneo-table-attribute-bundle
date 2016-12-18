<?php

namespace Flagbit\Bundle\TableAttributeBundle\Provider\Field;

use Pim\Bundle\EnrichBundle\Provider\Field\FieldProviderInterface;
use Pim\Component\Catalog\Model\AttributeInterface;

class TableFieldProvider implements FieldProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getField($element)
    {
        return 'flagbit-table-field';
    }

    /**
     * {@inheritdoc}
     */
    public function supports($element)
    {
        return $element instanceof AttributeInterface && 'flagbit_catalog_table' === $element->getAttributeType();
    }
}
