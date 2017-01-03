<?php

namespace Flagbit\Bundle\TableAttributeBundle\Validator;

use Flagbit\Bundle\TableAttributeBundle\Entity\ConstraintConfigInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Exception\ExceptionInterface;
use Symfony\Component\Validator\Exception\RuntimeException;

class ConstraintFactory
{
    /**
     * @param ConstraintConfigInterface $constraintConfig
     *
     * @return Constraint[]
     */
    public function createByConstraintConfig(ConstraintConfigInterface $constraintConfig)
    {
        $constraints = [];

        // TODO json_decode needs to be removed after json_array returns an array instead of a json string.
        $configs = json_decode($constraintConfig->getConstraints(), true);

        foreach($configs as $class => $params) {
            try {
                $constraints[] = $this->createInstance($class, $params);
            } catch (ExceptionInterface $e) {
                // TODO Create log entry for failing instantiation.
                continue;
            }
        }

        return $constraints;
    }

    /**
     * Creates one Contraint for a collection containing multiple field.
     *
     * The given $constraints array must be a associative array where the keys are the same as the keys of the value array
     * that needs to be validated.
     *
     * @param Constraint[] $constraints
     *
     * @return Constraint
     *
     * @throws ExceptionInterface
     */
    public function createCollectionConstraint(array $constraints)
    {
        return new All(['constraints' => [
            new Collection([
                'fields' => $constraints,
            ])
        ]]);
    }

    /**
     * @param string $class
     * @param array|string|int $params
     *
     * @return Constraint
     *
     * @throws \Symfony\Component\Validator\Exception\RuntimeException
     */
    private function createInstance($class, $params)
    {
        if (false === class_exists($class)) {
            $class = '\\Symfony\\Component\\Validator\\Constraints\\'.$class;
        }

        if (false === class_exists($class) || false === in_array(Constraint::class, class_parents($class), true)) {
            throw new RuntimeException(sprintf('Invalid class %s', $class));
        }

        return new $class($params);
    }
}
