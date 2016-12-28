<?php

namespace Flagbit\Bundle\TableAttributeBundle\AttributeType;

use Pim\Bundle\CatalogBundle\AttributeType\AbstractAttributeType;
use Pim\Component\Catalog\Model\AttributeInterface;

class TableType extends AbstractAttributeType
{
    const FLAGBIT_CATALOG_TABLE = 'flagbit_catalog_table';
    /**
     * {@inheritdoc}
     */
    protected function defineCustomAttributeProperties(AttributeInterface $attribute)
    {
        $properties = parent::defineCustomAttributeProperties($attribute) + [
            'minimumInputLength' => [
                'name'      => 'minimumInputLength',
                'fieldType' => 'pim_number'
            ],
            'columns' => [
                'name'      => 'columns',
                'fieldType' => 'pim_enrich_options',
                'options'   => [
                    'property_path' => 'properties[options]',
                ]
            ]
        ];
        return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::FLAGBIT_CATALOG_TABLE;
    }
}
