<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Component\Product\Factory\Value;

use Akeneo\Pim\Enrichment\Component\Product\Value\ScalarValue;
use Akeneo\Pim\Structure\Component\Query\PublicApi\AttributeType\Attribute;
use Akeneo\Tool\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use EmptyIterator;
use Flagbit\Bundle\TableAttributeBundle\Component\Product\Factory\Value\TableValueFactory;
use PhpSpec\ObjectBehavior;

class TableValueFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TableValueFactory::class);
    }

    public function it_creates_scopable_and_localizable_table_value()
    {
        $attribute = $this->createAttribute(true, true);

        $this->createByCheckingData($attribute, 'channelCode', 'de_DE', 'data')
            ->shouldBeLike(ScalarValue::scopableLocalizableValue('code', 'data', 'channelCode', 'de_DE'));
    }

    public function it_creates_scopable_table_value()
    {
        $attribute = $this->createAttribute(true, false);

        $this->createByCheckingData($attribute, 'channelCode', null, 'data')
            ->shouldBeLike(ScalarValue::scopableValue('code', 'data', 'channelCode'));
    }

    public function it_creates_localizable_table_value()
    {
        $attribute = $this->createAttribute(false, true);

        $this->createByCheckingData($attribute, null, 'de_DE', 'data')
            ->shouldBeLike(ScalarValue::localizableValue('code', 'data', 'de_DE'));
    }

    public function it_creates_table_value()
    {
        $attribute = $this->createAttribute(false, false);

        $this->createByCheckingData($attribute, null, null, 'data')
            ->shouldBeLike(ScalarValue::value('code', 'data'));
    }

    public function it_throws_exception_on_nonscalar_data()
    {
        $attribute = $this->createAttribute(false, false);

        $this->shouldThrow()->during('createByCheckingData', [$attribute, null, null, new EmptyIterator()]);
    }

    public function it_throws_exception_on_empty_data()
    {
        $attribute = $this->createAttribute(false, false);

        $this->shouldThrow(InvalidPropertyTypeException::class)->during('createByCheckingData', [$attribute, null, null, "\0\n "]);
    }

    private function createAttribute(bool $isScopable, bool $isLocalizable): Attribute
    {
        return new Attribute('code', 'flagbit_catalog_table', [], $isLocalizable, $isScopable, null, null, false, 'backend', ['de_DE', 'en_US']);
    }

    public function it_supports_attribute_type()
    {
        $this->supportedAttributeType()->shouldBe('flagbit_catalog_table');
    }
}
