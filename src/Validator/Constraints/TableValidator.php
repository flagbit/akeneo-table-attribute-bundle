<?php

namespace Flagbit\Bundle\TableAttributeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\AllValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TableValidator extends AllValidator
{
    /**
     * @param string     $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Table) {
            throw new UnexpectedTypeException($constraint, Table::class);
        }

        $value = json_decode($value, true);

        parent::validate($value, $constraint);
    }
}
