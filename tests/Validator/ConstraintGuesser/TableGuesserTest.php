<?php

namespace Flagbit\Bundle\TableAttributeBundle\Tests\Validator\ConstraintGuesser;

use Flagbit\Bundle\TableAttributeBundle\Validator\Constraints\Table;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TableGuesserTest extends KernelTestCase
{
    public function testTableGuesserIsTaggedCorrectly()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $chainedAttributeConstraintGuesser = $container->get('pim_catalog.validator.constraint_guesser.chained_attribute');

        $attribute = new Attribute();
        $attribute->setAttributeType('flagbit_catalog_table');

        $constraintGuesser = $chainedAttributeConstraintGuesser->guessConstraints($attribute);

        self::assertCount(1, $constraintGuesser);
        self::assertInstanceOf(Table::class, $constraintGuesser[0]);
    }
}
