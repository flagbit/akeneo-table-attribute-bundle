<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Validator\Constraints;

use Akeneo\Pim\Structure\Component\AttributeTypes;
use Akeneo\Pim\Structure\Component\Model\Attribute;
use Akeneo\Pim\Structure\Component\Model\AttributeOptionInterface;
use Akeneo\Pim\Structure\Component\Validator\Constraints\AttributeTypeForOption;
use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Flagbit\Bundle\TableAttributeBundle\Validator\Constraints\AttributeTypeForOptionValidator;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\ValidatorBuilder;

class AttributeTypeForOptionValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(AttributeTypeForOptionValidator::class);
    }

    public function it_validates_option_simple_select(
        AttributeOptionInterface $attributeOption,
        Attribute $attributeModel
    )
    {
        $expectedViolations = 0;
        $this->checkViolations(AttributeTypes::OPTION_SIMPLE_SELECT, $attributeOption, $attributeModel, $expectedViolations);
    }

    public function it_validates_option_multi_select(
        AttributeOptionInterface $attributeOption,
        Attribute $attributeModel
    )
    {
        $expectedViolations = 0;
        $this->checkViolations(AttributeTypes::OPTION_MULTI_SELECT, $attributeOption, $attributeModel, $expectedViolations);
    }

    public function it_validates_flagbit_table(
        AttributeOptionInterface $attributeOption,
        Attribute $attributeModel
    )
    {
        $expectedViolations = 0;
        $this->checkViolations(TableType::FLAGBIT_CATALOG_TABLE, $attributeOption, $attributeModel, $expectedViolations);
    }

    public function it_finds_violations_for_wrong_attribute_type(
        AttributeOptionInterface $attributeOption,
        Attribute $attributeModel
    )
    {
        $expectedViolations = 1;
        $type = "wrong_type";

        $this->checkViolations($type, $attributeOption, $attributeModel, $expectedViolations);
    }

    private function checkViolations(
        String $type,
        AttributeOptionInterface $attributeOption,
        Attribute $attributeModel,
        int $expectedViolations
    )
    {
        $validator = (new ValidatorBuilder())->getValidator();
        $executionContext = (new ExecutionContextFactory(new IdentityTranslator()))->createContext($validator, '');
        $executionContext->setConstraint($this->createAttributeTypeForOptionConstraint());

        $this->initialize($executionContext);
        $attributeModel->getType()->willReturn($type);
        $attributeModel->getCode()->willReturn('22');
        $attributeOption->getAttribute()->willReturn($attributeModel);

        $this->validate($attributeOption, $this->createAttributeTypeForOptionConstraint())->shouldHaveViolations($executionContext, $expectedViolations);
    }

    /**
     * @return array
     */
    public function getMatchers(): array
    {
        return [
            'haveViolations' => function ($subject, $context, $count) {
                $violationCount = count($context->getViolations());
                if ($violationCount !== $count) {
                    throw new FailureException(sprintf('Expected violations: %d, but %d occured', $count, $violationCount));
                }
                return true;
            }
        ];
    }

    /**
     * @return AttributeTypeForOption
     */
    private function createAttributeTypeForOptionConstraint()
    {
        return new AttributeTypeForOption();
    }
}
