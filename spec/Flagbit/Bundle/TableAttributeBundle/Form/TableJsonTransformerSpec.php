<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Form;

use Flagbit\Bundle\TableAttributeBundle\Form\TableJsonTransformer;
use PhpSpec\ObjectBehavior;

class TableJsonTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TableJsonTransformer::class);
    }

    public function it_keeps_transform_value()
    {
        $originalValue = '{"Blank":{},"NotNull":{}}';
        $expectedvalue = '{"Blank":{},"NotNull":{}}';

        $this->transform($originalValue)->shouldBe($expectedvalue);
    }

    public function it_reverse_transforms_to_constraints_array_format()
    {
        $originalValue = '{"Blank":{},"NotNull":{}}';
        $expectedvalue = [
          'Blank' => [],
          'NotNull' => [],
        ];

        $this->reverseTransform($originalValue)->shouldBe($expectedvalue);
    }

    public function it_reverse_transform_null()
    {
        $originalValue = null;

        $this->reverseTransform($originalValue)->shouldBeNull();
    }
}
