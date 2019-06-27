<?php

namespace Flagbit\Bundle\TableAttributeBundle\Provider\Field;

use Akeneo\Platform\Bundle\UIBundle\Provider\Field\FieldProviderInterface;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;

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
        return $element instanceof AttributeInterface && 'flagbit_catalog_table' === $element->getType();
    }
}
