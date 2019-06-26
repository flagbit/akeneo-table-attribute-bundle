<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Provider\Field;

use Flagbit\Bundle\TableAttributeBundle\Provider\Field\TableFieldProvider;
use PhpSpec\ObjectBehavior;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;


class TableFieldProviderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TableFieldProvider::class);
    }

    public function it_returns_flagbit_table_field()
    {
        $element = [
            'foo' => 'bar',
        ];
        $this->getField($element)->shouldReturn('flagbit-table-field');
    }

    public function it_checks_correct_support
    (
        AttributeInterface $attributeInterface
    ){
        $attributeInterface->getType()->willReturn('flagbit_catalog_table');
        $this->supports($attributeInterface)->shouldReturn(true);
    }

    public function it_checks_incorrect_support
    (
        AttributeInterface $attributeInterface
    ){
        $attributeInterface->getType()->willReturn('foo_bar');
        $this->supports($attributeInterface)->shouldReturn(false);
    }
}
