<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\DependencyInjection\Compiler;

use Flagbit\Bundle\TableAttributeBundle\DependencyInjection\Compiler\TwigAttributeIconsExtensionPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigAttributeIconsExtensionPassSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TwigAttributeIconsExtensionPass::class);
    }

    public function it_adds_icons_if_parameter_and_service_found(ContainerBuilder $container)
    {
        $container->hasDefinition('pim_enrich.twig.attribute_extension')
            ->shouldBeCalledTimes(1)
            ->willReturn(true)
        ;

        $container->hasParameter('pim_enrich.attribute_icons')
            ->shouldBeCalledTimes(1)
            ->willReturn(true)
        ;

        $pimParameters = [
            'foo',
            'bar'
        ];
        $container->getParameter('pim_enrich.attribute_icons')
            ->shouldBeCalledTimes(1)
            ->willReturn($pimParameters)
        ;

        $flagbitParameters = [
            'baz'
        ];
        $container->getParameter('flagbit_table_attribute.attribute_icons')
            ->shouldBeCalledTimes(1)
            ->willReturn($flagbitParameters)
        ;

        $container->setParameter('pim_enrich.attribute_icons', ['foo', 'bar', 'baz'])
            ->shouldBeCalledTimes(1)
        ;

        $this->process($container);
    }

    public function it_doesnt_add_icons_if_parameter_not_found(ContainerBuilder $container)
    {
        $container->hasDefinition('pim_enrich.twig.attribute_extension')
            ->shouldBeCalledTimes(1)
            ->willReturn(true)
        ;

        $container->hasParameter('pim_enrich.attribute_icons')
            ->shouldBeCalledTimes(1)
            ->willReturn(false)
        ;

        $this->process($container);
    }

    public function it_doesnt_add_icons_if_service_not_found(ContainerBuilder $container)
    {
        $container->hasDefinition('pim_enrich.twig.attribute_extension')
            ->shouldBeCalledTimes(1)
            ->willReturn(false)
        ;

        $this->process($container);
    }
}
