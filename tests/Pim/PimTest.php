<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Pim;

use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Pim\Component\Catalog\Comparator\Attribute\ScalarComparator;
use Pim\Component\Catalog\Value\ScalarValue;
use Pim\Component\Connector\ArrayConverter\FlatToStandard\Product\ValueConverter\TextConverter as FlatToStandardTextConverter;
use Pim\Component\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\TextConverter as StandardToFlatTextConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PimTest extends KernelTestCase
{

    public function testFlatToStandardConverterRegisters()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $registryValueConverter = $container->get('pim_connector.array_converter.flat_to_standard.product.value_converter.registry');

        $converter = $registryValueConverter->getConverter('flagbit_catalog_table');

        self::assertInstanceOf(FlatToStandardTextConverter::class, $converter);
    }

    public function testStandardToFlatConverterRegisters()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $registryValueConverter = $container->get('pim_connector.array_converter.standard_to_flat.product.value_converter.registry');

        $attribute = new Attribute();
        $attribute->setAttributeType('flagbit_catalog_table');

        $converter = $registryValueConverter->getConverter($attribute);

        self::assertInstanceOf(StandardToFlatTextConverter::class, $converter);
    }

    public function testAttributeTypeIsRegisteredCorrect()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $attributeTypeRegistry = $container->get('pim_catalog.registry.attribute_type');

        $attributeType = $attributeTypeRegistry->get('flagbit_catalog_table');

        self::assertInstanceOf(TableType::class, $attributeType);
    }

    public function testAttributeComparedSuccessfully()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $comparatorRegistry = $container->get('pim_catalog.comparator.registry');

        $comparator = $comparatorRegistry->getAttributeComparator('flagbit_catalog_table');

        self::assertInstanceOf(ScalarComparator::class, $comparator);
    }

    public function testScalarValueCreated()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $attribute = new Attribute();
        $attribute->setAttributeType('flagbit_catalog_table');
        $attribute->setLocalizable(false);
        $attribute->setScopable(false);

        $valueFactory = $container->get('pim_catalog.factory.value');

        $value = $valueFactory->create($attribute, null, null, '{}');

        self::assertInstanceOf(ScalarValue::class, $value);
    }
}