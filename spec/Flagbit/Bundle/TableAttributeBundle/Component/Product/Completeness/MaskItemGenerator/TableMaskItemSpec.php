<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Component\Product\Completeness\MaskItemGenerator;

use Flagbit\Bundle\TableAttributeBundle\Component\Product\Completeness\MaskItemGenerator\TableMaskItem;
use PhpSpec\ObjectBehavior;

class TableMaskItemSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TableMaskItem::class);
    }

    public function it_masks_for_raw_value()
    {
        $this->forRawValue('a', 'b', 'c', 'd')->shouldBe(['a-b-c']);
    }

    public function it_supports_attribute_types()
    {
        $this->supportedAttributeTypes()->shouldBe(['flagbit_catalog_table']);
    }
}
