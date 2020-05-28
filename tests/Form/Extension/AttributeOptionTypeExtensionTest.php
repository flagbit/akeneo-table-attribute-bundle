<?php

namespace Flagbit\Bundle\TableAttributeBundle\Test\Form\Extension;

use Flagbit\Bundle\TableAttributeBundle\Form\Extension\AttributeOptionTypeExtension;
use Akeneo\Pim\Structure\Bundle\Form\Type\AttributeOptionType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttributeOptionTypeExtensionTest extends KernelTestCase
{
    public function testAttributeOptionTypeExtended()
    {
        self::bootKernel();
        $container = self::$container;

        $formExtension = $container->get('form.extension');

        $typeExtension = $formExtension->getTypeExtensions(AttributeOptionType::class);

        self::assertCount(1, $typeExtension);

        self::assertContainsOnlyInstancesOf(AttributeOptionTypeExtension::class, $typeExtension);
    }
}
