<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Validator;

use Flagbit\Bundle\TableAttributeBundle\Entity\ConstraintConfigInterface;
use Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintFactory;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\Exception\ExceptionInterface;
use Symfony\Component\Validator\Constraints as C;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class ConstraintFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConstraintFactory::class);
    }

    // ConstraintFactory::createByConstraintConfig()

    public function it_creates_symfony_constraints_by_config(ConstraintConfigInterface $constraintConfig)
    {
        $json = '{ "Email": null, "Range": {"max": 10, "min": 1} }';

        $constraintConfig->getConstraints()->willReturn($json);

        $this->createByConstraintConfig($constraintConfig)->shouldHaveCount(2);
        $result = $this->createByConstraintConfig($constraintConfig);

        $result[0]->shouldBeAnInstanceOf(C\Email::class);
        
        $result[1]->shouldBeAnInstanceOf(C\Range::class);
        $result[1]->min->shouldBe(1);
        $result[1]->max->shouldBe(10);
    }

    public function it_creates_custom_constraints_by_config(ConstraintConfigInterface $constraintConfig)
    {
        $json = '{"Symfony\\\Component\\\Validator\\\Constraints\\\Email":null}';

        $constraintConfig->getConstraints()->willReturn($json);

        $this->createByConstraintConfig($constraintConfig)->shouldHaveCount(1);
        $result = $this->createByConstraintConfig($constraintConfig);

        $result[0]->shouldBeAnInstanceOf(C\Email::class);
    }

    public function it_skips_on_unknown_constraints_by_config(ConstraintConfigInterface $constraintConfig)
    {
        $json = '{"Foo":null, "Symfony\\\Component\\\Validator\\\Constraints\\\Foo":null}';

        $constraintConfig->getConstraints()->willReturn($json);

        $this->createByConstraintConfig($constraintConfig)->shouldHaveCount(0);
    }

    public function it_skips_on_other_classes_than_constraints(ConstraintConfigInterface $constraintConfig)
    {
        $json = '{"ArrayObject":null}';

        $constraintConfig->getConstraints()->willReturn($json);

        $this->createByConstraintConfig($constraintConfig)->shouldHaveCount(0);
    }

    // ConstraintFactory::createCollectionConstraint()

    public function it_creates_a_constraint_out_of_a_collection()
    {
        $constraints = [
            'foo' => [new C\Email(), new C\IsNull()],
        ];

        $constraint = $this->createCollectionConstraint($constraints);
        $constraint->shouldBeAnInstanceOf(C\All::class);
        $constraint->constraints->shouldHaveCount(1);
        $constraint->constraints[0]->shouldBeAnInstanceOf(C\Collection::class);

        $constraint->constraints[0]->fields->shouldHaveCount(1);
        $constraint->constraints[0]->fields->shouldHaveKey('foo');
        $constraint->constraints[0]->fields['foo']->constraints[0]->shouldBeAnInstanceOf(C\Email::class);
        $constraint->constraints[0]->fields['foo']->constraints[1]->shouldBeAnInstanceOf(C\IsNull::class);
    }

    public function it_throws_an_exception_on_invalid_collection_elements()
    {
        $constraints = [
            'foo' => [new C\Email(), 'foo'],
        ];

        $this->shouldThrow(ConstraintDefinitionException::class)->during('createCollectionConstraint', [$constraints]);
    }
}
