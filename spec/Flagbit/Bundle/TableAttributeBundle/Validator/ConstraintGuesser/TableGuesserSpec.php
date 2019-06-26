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
    public function let(ConstraintFactory $constraintFactory)
    {
        $this->beConstructedWith($constraintFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TableGuesser::class);
    }

    public function it_supports_table_attribute(AttributeInterface $attribute)
    {
        $attribute->getType()->willReturn(TableType::FLAGBIT_CATALOG_TABLE);
        $this->supportAttribute($attribute)->shouldReturn(true);
    }

    public function it_not_supports_wrong_type(AttributeInterface $attribute)
    {
        $attribute->getType()->willReturn('not_existing_type');
        $this->supportAttribute($attribute)->shouldReturn(false);
    }

    public function it_creates_constraint_array(
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

    public function it_creates_constraint_array_without_options(
        AttributeInterface $attribute,
        ConstraintFactory $constraintFactory,
        Table $tableConstraint
    )
    {
        $attribute->getOptions()->willReturn([]);
        $fieldConstraints  = [];
        $constraintFactory->createTableConstraint($fieldConstraints)->willReturn($tableConstraint);
        $this->guessConstraints($attribute)->shouldBeArray();
        $this->guessConstraints($attribute)->shouldReturn([$tableConstraint]);
    }
}
