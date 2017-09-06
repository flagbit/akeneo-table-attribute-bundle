<?php

namespace Flagbit\Bundle\TableAttributeBundle;

use Flagbit\Bundle\TableAttributeBundle\DependencyInjection\Compiler\TwigAttributeIconsExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FlagbitTableAttributeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TwigAttributeIconsExtensionPass());
    }
}
