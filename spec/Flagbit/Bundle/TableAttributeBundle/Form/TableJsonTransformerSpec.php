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

    public function it_transform_do_anything()
    {
        $originalValue = '{"Blank":{},"NotNull":{}}';
        $expectedvalue = '{"Blank":{},"NotNull":{}}';

        $this->transform($originalValue)->shouldBe($expectedvalue);
        $this->reverseTransform($originalValue)->shouldBeArray();
    }

    public function it_reverse_transform_returns_right_constraints_array()
    {
        $originalValue = '{"Blank":{},"NotNull":{}}';
        $expectedvalue = [
          'Blank' => [],
          'NotNull' => [],
        ];

        $this->reverseTransform($originalValue)->shouldBe($expectedvalue);
        $this->reverseTransform($originalValue)->shouldBeArray();
    }

    public function it_reverse_transform_returns_null()
    {
        $originalValue = null;

        $this->reverseTransform($originalValue)->shouldBeNull();
    }
}
