<?php

namespace Flagbit\Bundle\TableAttributeBundle\Validator\Constraints;

use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Akeneo\Pim\Structure\Component\Validator\Constraints\AttributeTypeForOptionValidator as BaseValidator;
use Akeneo\Pim\Structure\Component\AttributeTypes;
use Akeneo\Pim\Structure\Component\Model\AttributeOptionInterface;
use Symfony\Component\Validator\Constraint;

/**
 * Class AttributeTypeForOptionValidator
 *
 * Validator for attribute used for an option
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
                TableType::FLAGBIT_CATALOG_TABLE,
            ];

            if (!in_array($attribute->getType(), $authorizedTypes)) {
                $this->addInvalidAttributeViolation($constraint, $attributeOption, $authorizedTypes);
            }
        }
    }
}
