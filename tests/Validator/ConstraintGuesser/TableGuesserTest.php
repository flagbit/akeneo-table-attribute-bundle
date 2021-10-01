<?php

namespace Flagbit\Bundle\TableAttributeBundle\Tests\Validator\ConstraintGuesser;

use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;
use Flagbit\Bundle\TableAttributeBundle\Validator\Constraints\Table;
use Akeneo\Pim\Structure\Component\Model\Attribute;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TableGuesserTest extends KernelTestCase
{
    public function testTableGuesserIsTaggedCorrectly(): void
    {
        self::bootKernel();
        $container = self::$container;

        $chainedAttributeConstraintGuesser = $container->get('pim_catalog.validator.constraint_guesser.chained_attribute');

        $attribute = new Attribute();
        $attribute->setType('flagbit_catalog_table');

        $constraintGuesser = $chainedAttributeConstraintGuesser->guessConstraints($attribute);

        self::assertCount(1, $constraintGuesser);
        self::assertInstanceOf(Table::class, $constraintGuesser[0]);
    }

    /**
     * @dataProvider provideValidTableValues
     */
    public function testValidTableData(string $tableValue): void
    {
        self::bootKernel();
        $container = self::$container;

        $guesser = $container->get('flagbit_table_attribute.validator.constraint_guesser.table');
        $validator = $container->get('validator');

        $jsonConfig = [
            'Type' => 'int',
            'Range' => ['max' => 10, 'min' => 1]
        ];

        $option1 = $this->createMock(AttributeOption::class);
        $option1->method('getConstraints')->willReturn($jsonConfig);
        $option1->method('getCode')->willReturn('foo');
        $option2 = $this->createMock(AttributeOption::class);
        // No constraints configured for "bar"
        $option2->method('getConstraints')->willReturn([]);
        $option2->method('getCode')->willReturn('bar');
        $collection = new ArrayCollection([$option1, $option2]);

        $attribute = $this->createMock(AttributeInterface::class);
        $attribute->method('getType')->willReturn('flagbit_catalog_table');
        $attribute->method('getOptions')->willReturn($collection);

        $constraints = $guesser->guessConstraints($attribute);

        $violations = $validator->validate($tableValue, $constraints);

        self::assertCount(0, $violations);
    }

    public function provideValidTableValues(): array
    {
        return [
            'complete' => ['[{"foo": 5, "bar": "text"},{"foo": 10, "bar": "text2"}]'],
            'missing bar field' => ['[{"foo": 5}]'],
            'completely empty' => ['[]'],
        ];
    }
}
