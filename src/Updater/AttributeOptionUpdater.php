<?php

namespace Flagbit\Bundle\TableAttributeBundle\Updater;

use Akeneo\Pim\Structure\Component\Model\AttributeOptionInterface;
use Akeneo\Pim\Structure\Component\Updater\AttributeOptionUpdater as BaseUpdater;
use Akeneo\Tool\Component\StorageUtils\Exception\InvalidPropertyException;
use Akeneo\Tool\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;

class AttributeOptionUpdater extends BaseUpdater
{
    protected function validateDataType($field, $data)
    {
        if (!in_array($field, ['type', 'type_config', 'constraints'])) {
            return parent::validateDataType($field, $data);
        }

        switch ($field) {
            case 'type':
                $availableTypes = ['select', 'select_from_url', 'text', 'number'];
                if (!in_array($data, $availableTypes)) {
                    InvalidPropertyException::dataExpected('type', implode(', ', $availableTypes), static::class);
                }
                break;

            case 'type_config':
                if (!is_array($data)) {
                    throw InvalidPropertyTypeException::arrayExpected($field, static::class, $data);
                }

                foreach ($data as $key => $value) {
                    $this->validateTypeConfig($key, $value);
                }
                break;

            case 'constraints':
                if (!is_array($data)) {
                    throw InvalidPropertyTypeException::arrayExpected($field, static::class, $data);
                }
                break;

            default:
                break;
        }
    }

    protected function validateTypeConfig($key, $value)
    {
        switch ($key) {
            case 'is_decimal':
                if (!is_bool($value)) {
                    InvalidPropertyTypeException::booleanExpected('type_config-is_decimal', static::class, $value);
                }
                break;

            case 'options':
                if (!is_array($value)) {
                    throw InvalidPropertyTypeException::arrayExpected('type_config-options', static::class, $value);
                }
                break;

            case 'options_url':
                if (!is_string($value)) {
                    InvalidPropertyTypeException::stringExpected('type_config-options_url', static::class, $value);
                }
                break;

            default:
                break;
        }
    }

    protected function setData(AttributeOptionInterface $attributeOption, $field, $data)
    {
        parent::setData($attributeOption, $field, $data);

        if ($attributeOption instanceof AttributeOption && $attributeOption->isTableAttribute()) {
            if ('type' === $field) {
                $attributeOption->setType($data);
            }

            if ('type_config' === $field) {
                $attributeOption->setTypeConfig($data);
            }

            if ('constraints' === $field) {
                $attributeOption->setConstraints($data);
            }
        }
    }
}
