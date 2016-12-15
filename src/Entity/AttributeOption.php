<?php

namespace Flagbit\Bundle\TableAttributeBundle\Entity;

use Pim\Bundle\CatalogBundle\Entity\AttributeOption as BaseAttributeOption;

/**
 * @todo move Table specific columns into an own entity
 */
class AttributeOption extends BaseAttributeOption
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $constraints;

    /**
     * @var array
     */
    private $typeConfig;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @param array $constraints
     */
    public function setConstraints($constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * @return array
     */
    public function getTypeConfig()
    {
        return $this->typeConfig;
    }

    /**
     * @param array $typeConfig
     */
    public function setTypeConfig($typeConfig)
    {
        $this->typeConfig = $typeConfig;
    }
}
