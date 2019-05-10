<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Provider\Field;

use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TableFieldProviderTest extends KernelTestCase
{
    public function testFieldProviderIsCompatible()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $chainedFieldProvider = $container->get('pim_enrich.provider.field.chained');

        $attribute = new Attribute();
        $attribute->setAttributeType('flagbit_catalog_table');

        $fieldProvider = $chainedFieldProvider->getField($attribute);

        self::assertSame('flagbit-table-field', $fieldProvider);
    }
}
