<?php

namespace Flagbit\Bundle\TableAttributeBundle\AttributeType;

use Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintGuesser\TableGuesser;
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
            'validationRule' => [
                'name'      => 'validationRule',
                'fieldType' => 'hidden',
                'data'   => TableGuesser::VALIDATION_RULE,
            ],
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
