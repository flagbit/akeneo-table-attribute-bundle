<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Pim;

use Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Filter\Attribute\OptionFilter;
use Akeneo\Pim\Enrichment\Component\Product\Comparator\Attribute\ScalarComparator;
use Akeneo\Pim\Enrichment\Component\Product\Connector\ArrayConverter\FlatToStandard\ValueConverter\TextConverter as FlatToStandardTextConverter;
use Akeneo\Pim\Enrichment\Component\Product\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\TextConverter as StandardToFlatTextConverter;
use Akeneo\Pim\Enrichment\Component\Product\Query\Filter\FilterRegistry;
use Akeneo\Pim\Enrichment\Component\Product\Updater\Setter\AttributeSetter;
use Akeneo\Pim\Enrichment\Component\Product\Value\ScalarValue;
use Akeneo\Pim\Structure\Component\Model\Attribute;
use Akeneo\Pim\Structure\Component\Query\PublicApi\AttributeType\Attribute as PublicApiAttribute;
use Akeneo\Pim\Structure\Component\Repository\AttributeRepositoryInterface;
use Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PimTest extends KernelTestCase
{

    public function testFlatToStandardConverterRegisters()
    {
        self::bootKernel();
        $container = self::$container;

        $registryValueConverter = $container->get('pim_connector.array_converter.flat_to_standard.product.value_converter.registry');

        $converter = $registryValueConverter->getConverter('flagbit_catalog_table');

        self::assertInstanceOf(FlatToStandardTextConverter::class, $converter);
    }

    public function testStandardToFlatConverterRegisters()
    {
        self::bootKernel();
        $container = self::$container;

        $registryValueConverter = $container->get('pim_connector.array_converter.standard_to_flat.product.value_converter.registry');

        $attribute = new Attribute();
        $attribute->setAttributeType('flagbit_catalog_table');

        $converter = $registryValueConverter->getConverter($attribute);

        self::assertInstanceOf(StandardToFlatTextConverter::class, $converter);
    }

    public function testAttributeComparedSuccessfully()
    {
        self::bootKernel();
        $container = self::$container;

        $registryComparator = $container->get('pim_catalog.comparator.registry');

        $comparator = $registryComparator->getAttributeComparator('flagbit_catalog_table');

        self::assertInstanceOf(ScalarComparator::class, $comparator);
    }

    public function testScalarValueCreated()
    {
        self::bootKernel();
        $container = self::$container;

        $valueFactory = $container->get('akeneo.pim.enrichment.factory.value');

        $attribute = new PublicApiAttribute(
            'foo',
            'flagbit_catalog_table',
            [],
            false,
            false,
            null,
            null,
            'flagbit_catalog_table',
            []
        );

        $value = $valueFactory->createWithoutCheckingData($attribute, null, null, '{}');

        self::assertEquals(ScalarValue::value('foo', '{}'), $value);
    }

    public function testProductUpdatedSuccessfully()
    {
        self::bootKernel();
        $container = self::$container;

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
        $container = self::$container;

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

    /**
     * @dataProvider queryBuildersProvider
     */
    public function testQueryBuilderAttributeFiltersCorrectly($operator, $service)
    {
        self::bootKernel();
        $container = self::$container;

        $attribute = new Attribute();
        $attribute->setType('flagbit_catalog_table');

        /** @var FilterRegistry $filterRegistry */
        $filterRegistry = $container->get($service);

        $filter = $filterRegistry->getAttributeFilter($attribute, $operator);

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
            // service doesn't exist in community. See tests/Kernel/config/packages/test/ee-services.yml
            ['IN', 'pimee_workflow.query.filter.product_proposal_registry'],
            ['EMPTY', 'pimee_workflow.query.filter.product_proposal_registry'],
            ['NOT EMPTY', 'pimee_workflow.query.filter.product_proposal_registry'],
            ['NOT IN', 'pimee_workflow.query.filter.product_proposal_registry'],
            // service doesn't exist in community. See tests/Kernel/config/packages/test/ee-services.yml
            ['IN', 'pimee_workflow.query.filter.published_product_registry'],
            ['EMPTY', 'pimee_workflow.query.filter.published_product_registry'],
            ['NOT EMPTY', 'pimee_workflow.query.filter.published_product_registry'],
            ['NOT IN', 'pimee_workflow.query.filter.published_product_registry'],
        ];
    }
}
