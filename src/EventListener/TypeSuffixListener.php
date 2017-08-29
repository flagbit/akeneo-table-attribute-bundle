<?php

namespace Flagbit\Bundle\TableAttributeBundle\EventListener;

use Pim\Bundle\ElasticSearchBundle\Query\ProductQueryUtility;

class TypeSuffixListener
{
    public function addTypeSuffix()
    {
        // Class only existing and required if PIM EE and ElasticSearchBundle are used otherwise is not necessary at all
        if (class_exists(ProductQueryUtility::class)) {
            ProductQueryUtility::addTypeSuffix('flagbit_catalog_table', '-table');
        }
    }
}
