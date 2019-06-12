<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\AttributeType;

use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use PhpSpec\ObjectBehavior;

class TableTypeSpec extends ObjectBehavior
{

    public function let()
    {
        $this->beConstructedWith('text');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TableType::class);
    }

    public function it_should_return_flagbit_catalog_table()
    {
        $this->getName()->shouldReturn('flagbit_catalog_table');
    }

    public function it_should_return_text_forbackend_type(){
        $this->getBackendType()->shouldReturn('text');
    }
}
