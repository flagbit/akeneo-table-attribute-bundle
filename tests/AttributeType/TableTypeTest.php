<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\AttributeType;

use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TableTypeTest extends KernelTestCase
{
    public function testAttributeTypeIsRegisteredCorrect()
    {
        self::bootKernel();
        $container = self::$container;

        $attributeTypeRegistry = $container->get('pim_catalog.registry.attribute_type');

        $attributeType = $attributeTypeRegistry->get('flagbit_catalog_table');

        self::assertInstanceOf(TableType::class, $attributeType);
    }
}
