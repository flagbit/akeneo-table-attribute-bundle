<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Kernel;

require_once __DIR__.'/../../vendor/akeneo/pim-community-dev/src/Kernel.php';

use Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        $bundles = include __DIR__ . '/../../vendor/akeneo/pim-community-dev/config/bundles.php';
        $bundles += include __DIR__ . '/config/bundles.php';

        foreach ($bundles as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    protected function build(ContainerBuilder $container)
    {
        $serviceIds = [
            'pim_catalog.validator.constraint_guesser.chained_attribute',
            'akeneo.pim.enrichment.factory.value',
            'pim_catalog.repository.attribute',
            'pim_catalog.repository.cached_attribute',
            'form.extension',
        ];
        $container->addCompilerPass(new PublicServiceCompilerPass($serviceIds));
        $container->addCompilerPass(new EnterpriseFilterStubPass('product_proposal'));
        $container->addCompilerPass(new EnterpriseFilterStubPass('published_product'));
    }
}
