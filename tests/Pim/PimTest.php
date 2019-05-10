<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Pim;

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

    public function testAttributeComparedSuccessfully()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $registryComparator = $container->get('pim_catalog.comparator.registry');

        $comparator = $registryComparator->getAttributeComparator('flagbit_catalog_table');

        self::assertInstanceOf(ScalarComparator::class, $comparator);
    }

    public function testScalarValueCreated()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $valueFactory = $container->get('pim_catalog.factory.value');

        $attribute = new Attribute();
        $attribute->setAttributeType('flagbit_catalog_table');
        $attribute->setLocalizable(false);
        $attribute->setScopable(false);

        $value = $valueFactory->create($attribute, null, null, '{}');

        self::assertInstanceOf(ScalarValue::class, $value);
    }
    // pim_catalog.query.elasticsearch.filter.option.class
    //query_builders
    //pim_catalog.query.filter.product_registry
    //pim_catalog.query.filter.product_mode_registry
    //no match found

}