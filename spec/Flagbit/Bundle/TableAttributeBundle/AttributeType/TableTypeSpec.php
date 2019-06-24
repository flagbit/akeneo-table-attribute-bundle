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

    public function it_is_initializable()
    {
        $this->shouldHaveType(TableType::class);
    }

    public function it_returns_flagbit_catalog_table()
    {
        $this->getName()->shouldReturn('flagbit_catalog_table');
    }

    public function it_returns_text_forbackend_type(){
        $this->getBackendType()->shouldReturn('text');
    }
}
