<?php

namespace Flagbit\Bundle\TableAttributeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FlagbitTableAttributeExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('array_converters.xml');
        $loader->load('attribute_types.xml');
        $loader->load('comparators.xml');
        $loader->load('updaters.xml');
        $loader->load('entities.xml');
        $loader->load('validators.xml');
        $loader->load('query_builders.xml');
        $loader->load('factories.xml');
    }
}
