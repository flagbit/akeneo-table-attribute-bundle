<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Entity;

use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use PhpSpec\ObjectBehavior;

class AttributeOptionSpec extends ObjectBehavior
{
    function it_is_an_attribute_option_of_a_different_attribute(AttributeInterface $attribute)
    {
        $this->setAttribute($attribute);
        $attribute->getType()->willReturn('foo');
        $this->isTableAttribute()->shouldReturn(false);
    }

    function it_is_an_attribute_option_of_a_table_attribute(AttributeInterface $attribute)
    {
        $this->setAttribute($attribute);
        $attribute->getType()->willReturn(TableType::FLAGBIT_CATALOG_TABLE);
        $this->isTableAttribute()->shouldReturn(true);
    }

    function it_is_an_attribute_option_without_an_attribute()
    {
        $this->isTableAttribute()->shouldReturn(false);
    }
}
