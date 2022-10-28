<?php

namespace Flagbit\Bundle\TableAttributeBundle\Entity;

interface ConstraintConfigInterface
{
    /**
     * @return array
     */
    public function getConstraints(): array;
}
