<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintGuesser;

use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;
use Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintFactory;
use Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintGuesser\TableGuesser;
use Flagbit\Bundle\TableAttributeBundle\Validator\Constraints\Table;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Constraint;

class TableGuesserSpec extends ObjectBehavior
{

    function let(ConstraintFactory $constraintFactory)
    {
        $this->beConstructedWith($constraintFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TableGuesser::class);
    }

    function it_support_table_attribute(AttributeInterface $attribute)
    {
        $attribute->getAttributeType()->willReturn(TableType::FLAGBIT_CATALOG_TABLE);
        $this->supportAttribute($attribute)->shouldReturn(true);
    }

    function it_should_be_able_to_create_constraint_array(
        AttributeInterface $attribute,
        AttributeOption $attributeOption,
        ConstraintFactory $constraintFactory,
        Constraint $notBlank,
        Constraint $email,
        Table $tableConstraint
    )
    {
        $attribute->getOptions()->willReturn(new ArrayCollection([
            $attributeOption->getWrappedObject(),
        ]));
        $constraints = [
            $notBlank,
            $email
        ];

        $attributeOption->getCode()->willReturn('foo');
        $constraintFactory->createByConstraintConfig($attributeOption)->willReturn($constraints);

        $fieldConstraints['foo']  = $constraints;
        $constraintFactory->createTableConstraint($fieldConstraints)->willReturn($tableConstraint);
        $this->guessConstraints($attribute)->shouldBeArray();
        $this->guessConstraints($attribute)->shouldReturn([$tableConstraint]);
    }

    function it_should_be_able_to_create_constraint_array_without_options(
        AttributeInterface $attribute,
        ConstraintFactory $constraintFactory,
        Table $tableConstraint
    )
    {
        $attribute->getOptions()->willReturn([]);
        $constraints = [];
        $fieldConstraints  = [];
        $constraintFactory->createTableConstraint($fieldConstraints)->willReturn($tableConstraint);
        $this->guessConstraints($attribute)->shouldBeArray();
        $this->guessConstraints($attribute)->shouldReturn([$tableConstraint]);
    }
}
