<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Kernel;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EnterpriseFilterStubPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition(sprintf('pimee_workflow.query.filter.%s_registry', $this->type));
        $filterTag = sprintf('pimee_workflow.elasticsearch.query.%s_filter', $this->type);

        $filters = $this->findTaggedServices($filterTag, $container);
        foreach ($filters as $filter) {
            $registry->addMethodCall('register', [$filter]);
        }
    }

    private function findTaggedServices(string $tagName, ContainerBuilder $container): array
    {
        $services = $container->findTaggedServiceIds($tagName);

        $sortedServices = [];
        foreach ($services as $serviceId => $tags) {
            foreach ($tags as $tag) {
                $priority = $tag['priority'] ?? 30;
                $sortedServices[$priority][] = new Reference($serviceId);
            }
        }
        krsort($sortedServices);

        return count($sortedServices) > 0 ? array_merge(...$sortedServices) : [];
    }
}
