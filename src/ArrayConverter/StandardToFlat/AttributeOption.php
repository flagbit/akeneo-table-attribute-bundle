<?php

namespace Flagbit\Bundle\TableAttributeBundle\ArrayConverter\StandardToFlat;

use Akeneo\Pim\Structure\Component\ArrayConverter\StandardToFlat\AttributeOption as BaseArrayConverter;

class AttributeOption extends BaseArrayConverter
{
    protected function convertProperty($property, $data, array $convertedItem, array $options)
    {
        switch ($property) {
            case 'constraints':
                $convertedItem['constraints'] = implode(',', array_keys($data));
                break;

            case 'type_config':
                foreach ($data as $key => $value) {
                    $convertedItem['type_config-'.$key] = is_array($value) ? json_encode($value) : (string) $value;
                }
                break;

            default:
                $convertedItem = parent::convertProperty($property, $data, $convertedItem, $options);
                break;
        }

        return $convertedItem;
    }
}
