<?php

namespace Flagbit\Bundle\TableAttributeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigAttributeIconsExtensionPass implements CompilerPassInterface
{
    /**
     * Add table attribute icons to Akeneo
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('pim_enrich.twig.attribute_extension')) {
            return;
        }

        if (!$container->hasParameter('pim_enrich.attribute_icons')) {
            return;
        }

        $icons = array_merge(
            $container->getParameter('pim_enrich.attribute_icons'),
            $container->getParameter('flagbit_table_attribute.attribute_icons')
        );

        $container->setParameter('pim_enrich.attribute_icons', $icons);
    }
}
