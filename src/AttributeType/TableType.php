<?php

namespace Flagbit\Bundle\TableAttributeBundle\AttributeType;

use Akeneo\Pim\Structure\Component\AttributeType\AbstractAttributeType;

class TableType extends AbstractAttributeType
{
    const FLAGBIT_CATALOG_TABLE = 'flagbit_catalog_table';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::FLAGBIT_CATALOG_TABLE;
    }
}
