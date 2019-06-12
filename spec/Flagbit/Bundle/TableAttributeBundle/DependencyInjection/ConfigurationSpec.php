<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\DependencyInjection;

use Flagbit\Bundle\TableAttributeBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use PhpSpec\ObjectBehavior;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Configuration::class);
    }

    function it_should_return_Treebuilder_object(){
        $this->getConfigTreeBuilder()->shouldBeAnInstanceOf(TreeBuilder::Class);
    }
}
