<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Kernel;

require_once __DIR__.'/../../vendor/akeneo/pim-community-dev/app/AppKernel.php';

use Flagbit\Bundle\TableAttributeBundle\FlagbitTableAttributeBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TestKernel extends \AppKernel
{
    /**
     * Registers your custom bundles
     *
     * @return array
     */
    protected function registerProjectBundles()
    {
        return [
            new FlagbitTableAttributeBundle(),
        ];
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return __DIR__;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return __DIR__
            . DIRECTORY_SEPARATOR
            . 'var'
            . DIRECTORY_SEPARATOR
            . 'cache'
            . DIRECTORY_SEPARATOR
            . $this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'logs';
    }

    protected function build(ContainerBuilder $container)
    {
        $serviceIds = ['pim_catalog.validator.constraint_guesser.chained_attribute'];
        $container->addCompilerPass(new PublicServiceCompilerPass($serviceIds));
    }

}
