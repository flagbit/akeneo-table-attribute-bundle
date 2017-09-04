<?php

namespace Flagbit\Bundle\TableAttributeBundle\Validator\Constraints;

use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Pim\Component\Catalog\Validator\Constraints\AttributeTypeForOptionValidator as BaseValidator;
use Pim\Component\Catalog\AttributeTypes;
use Pim\Component\Catalog\Model\AttributeOptionInterface;
use Symfony\Component\Validator\Constraint;

/**
 * Class AttributeTypeForOptionValidator
 *
 * Validator for attribute used for an option
 *
 * @package Flagbit\Bundle\TableAttributeBundle\Validator\Constraints
 * @author Ruben Beglaryan <ruben.beglaryan@flagbit.de>
 */
class AttributeTypeForOptionValidator extends BaseValidator
{
    /**
     * @param object     $attributeOption
     * @param Constraint $constraint
     */
    public function validate($attributeOption, Constraint $constraint)
    {
        /** @var AttributeOptionInterface */
        if ($attributeOption instanceof AttributeOptionInterface) {
            $attribute = $attributeOption->getAttribute();
            $authorizedTypes = [
                AttributeTypes::OPTION_SIMPLE_SELECT,
                AttributeTypes::OPTION_MULTI_SELECT,
                TableType::FLAGBIT_CATALOG_TABLE
            ];

            if (!in_array($attribute->getAttributeType(), $authorizedTypes)) {
                $this->addInvalidAttributeViolation($constraint, $attributeOption);
            }
        }
    }
}
