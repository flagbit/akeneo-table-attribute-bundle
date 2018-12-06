<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Validator\Constraints;

use Flagbit\Bundle\TableAttributeBundle\Validator\Constraints\Table;
use Flagbit\Bundle\TableAttributeBundle\Validator\Constraints\TableValidator;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Constraints as C;
use Symfony\Component\Validator\ValidatorBuilder;

class TableValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TableValidator::class);
    }

    public function it_is_invalid_on_wrong_value()
    {
        $validator = (new ValidatorBuilder())->getValidator();
        $executionContext = (new ExecutionContextFactory(new IdentityTranslator()))->createContext($validator, '');

        $this->initialize($executionContext);
        $this->validate('[{"foo":1,"bar":"bar"}]', $this->createTableConstraint())->shouldHaveViolations($executionContext, 2);
    }

    public function it_is_valid_on_correct_value()
    {
        $validator = (new ValidatorBuilder())->getValidator();
        $executionContext = (new ExecutionContextFactory(new IdentityTranslator()))->createContext($validator, '');
        $value = '[{"foo":null,"bar":false},{"foo":null,"bar":false},{"foo":null,"bar":false},{"foo":null,"bar":false}]';

        $this->initialize($executionContext);
        $this->validate($value, $this->createTableConstraint())->shouldHaveViolations($executionContext, 0);
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
     * @return Table
     */
    private function createTableConstraint()
    {
        return new Table(['constraints' => [
            new C\Collection([
                'fields' => [
                    'foo' => [new C\IsNull()],
                    'bar' => [new C\IsFalse()],
                ],
            ])
        ]]);
    }
}
