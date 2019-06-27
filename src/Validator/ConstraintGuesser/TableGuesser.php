<?php

namespace Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintGuesser;

use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;
use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;
use Flagbit\Bundle\TableAttributeBundle\Validator\ConstraintFactory;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Akeneo\Pim\Enrichment\Component\Product\Validator\ConstraintGuesserInterface;

class TableGuesser implements ConstraintGuesserInterface
{
    /**
     * @var ConstraintFactory
     */
    private $constraintFactory;

    /**
     * @param ConstraintFactory $constraintFactory
     */
    public function __construct(ConstraintFactory $constraintFactory)
    {
        $this->constraintFactory = $constraintFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supportAttribute(AttributeInterface $attribute)
    {
        return TableType::FLAGBIT_CATALOG_TABLE === $attribute->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function guessConstraints(AttributeInterface $attribute)
    {
        $constraints = [];

        $fieldConstraints = [];
        /** @var AttributeOption $option */
        // DocBlock of getOptions() claims to be only ArrayAccess, but Options are a Doctrine Collection
        foreach ($attribute->getOptions() as $option) {
            $fieldConstraints[$option->getCode()] = $this->constraintFactory->createByConstraintConfig($option);
        }

        $constraints[] = $this->constraintFactory->createTableConstraint($fieldConstraints);

        return $constraints;
    }
}
