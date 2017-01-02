<?php

namespace Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintGuesser;

use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Validator\ConstraintGuesserInterface;
use Symfony\Component\Validator\Constraints\IsNull;

class TableGuesser implements ConstraintGuesserInterface
{
    /**
     * @var string A string with a max length of 10 chars
     */
    const VALIDATION_RULE = 'fb_table';

    /**
     * {@inheritdoc}
     */
    public function supportAttribute(AttributeInterface $attribute)
    {
        return TableType::FLAGBIT_CATALOG_TABLE === $attribute->getAttributeType();
    }

    /**
     * {@inheritdoc}
     */
    public function guessConstraints(AttributeInterface $attribute)
    {
        $constraints = [];

        if (self::VALIDATION_RULE === $attribute->getValidationRule()) {
            $constraint = [];
            /** @var AttributeOption $option */
            // DocBlock of getOptions() claims to be only ArrayAccess, but Options are a Doctrine Collection
            foreach ($attribute->getOptions() as $option) {
                $constraint[$option->getCode()] = $option->getConstraints();
            }

            $constraints[] = new IsNull();
        }

        return $constraints;
    }
}
