<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Pim;

use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Pim\Bundle\CatalogBundle\Elasticsearch\Filter\Attribute\OptionFilter;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Pim\Component\Catalog\Comparator\Attribute\ScalarComparator;
use Pim\Component\Catalog\Query\Filter\FilterRegistry;
use Pim\Component\Catalog\Repository\AttributeRepositoryInterface;
use Pim\Component\Catalog\Updater\Setter\AttributeSetter;
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

    public function testProductUpdatedSuccessfully()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $repository = $this->createMock(IdentifiableObjectRepositoryInterface::class);

        $container->set('pim_catalog.repository.cached_attribute', $repository);

        $registryUpdater = $container->get('pim_catalog.updater.setter.registry');

        $attribute = new Attribute();
        $attribute->setAttributeType('flagbit_catalog_table');

        $updater = $registryUpdater->getAttributeSetter($attribute);

        self::assertInstanceOf(AttributeSetter::class, $updater);
    }

    /**
     * @dataProvider queryBuildersProvider
     */
    public function testQueryBuilderFiltersCorrectly($operator, $service)
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $attribute = new Attribute();
        $attribute->setType('flagbit_catalog_table');

        $repository = $this->createMock(AttributeRepositoryInterface::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($attribute);

        $container->set('pim_catalog.repository.attribute', $repository);

        /** @var FilterRegistry $filterRegistry */
        $filterRegistry = $container->get($service);

        $filter = $filterRegistry->getFilter('flagbit_catalog_table', $operator);

        self::assertInstanceOf(OptionFilter::class, $filter);
    }

    public function queryBuildersProvider()
    {
        return [
            ['IN', 'pim_catalog.query.filter.product_registry'],
            ['EMPTY', 'pim_catalog.query.filter.product_registry'],
            ['NOT EMPTY', 'pim_catalog.query.filter.product_registry'],
            ['NOT IN', 'pim_catalog.query.filter.product_registry'],
            ['IN', 'pim_catalog.query.filter.product_model_registry'],
            ['EMPTY', 'pim_catalog.query.filter.product_model_registry'],
            ['NOT EMPTY', 'pim_catalog.query.filter.product_model_registry'],
            ['NOT IN', 'pim_catalog.query.filter.product_model_registry'],
            ['IN', 'pim_catalog.query.filter.product_and_product_model_registry'],
            ['EMPTY', 'pim_catalog.query.filter.product_and_product_model_registry'],
            ['NOT EMPTY', 'pim_catalog.query.filter.product_and_product_model_registry'],
            ['NOT IN', 'pim_catalog.query.filter.product_and_product_model_registry'],
        ];
    }
}
