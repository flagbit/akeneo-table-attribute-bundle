<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\DependencyInjection;

use Flagbit\Bundle\TableAttributeBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use PhpSpec\ObjectBehavior;

class ConfigurationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Configuration::class);
    }

    public function it_returns_Treebuilder_object(){
        $this->getConfigTreeBuilder()->shouldBeAnInstanceOf(TreeBuilder::Class);
    }
}
