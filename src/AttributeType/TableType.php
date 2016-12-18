<?php

namespace Flagbit\Bundle\TableAttributeBundle\AttributeType;

use Pim\Bundle\CatalogBundle\AttributeType\AbstractAttributeType;
use Pim\Component\Catalog\Model\AttributeInterface;

class TableType extends AbstractAttributeType
{
    /**
     * {@inheritdoc}
     */
    protected function defineCustomAttributeProperties(AttributeInterface $attribute)
    {
        $properties = parent::defineCustomAttributeProperties($attribute) + [
            'validationRule' => [
                'name'      => 'validationRule',
                'fieldType' => 'hidden',
                'data'   => 'email',
            ],
        ];

        return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'flagbit_catalog_table';
    }
}
