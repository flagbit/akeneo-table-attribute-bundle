<?php

namespace Flagbit\Bundle\TableAttributeBundle\EventListener;

use Pim\Bundle\ElasticSearchBundle\Query\ProductQueryUtility;

class TypeSuffixListener
{
    public function addTypeSuffix()
    {
        ProductQueryUtility::addTypeSuffix('flagbit_catalog_table', '-table');
    }
}
