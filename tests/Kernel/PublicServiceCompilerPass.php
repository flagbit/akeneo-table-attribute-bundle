<?php


namespace Flagbit\Bundle\TableAttributeBundle\Test\Kernel;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PublicServiceCompilerPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $serviceIds;

    public function __construct(array $serviceIds)
    {
        $this->serviceIds = $serviceIds;
    }

    public function process(ContainerBuilder $container)
    {
        foreach ($this->serviceIds as $serviceId) {
            $container->getDefinition($serviceId)->setPublic(true);
        }
    }
}
