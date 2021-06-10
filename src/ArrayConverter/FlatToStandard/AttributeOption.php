<?php

namespace Flagbit\Bundle\TableAttributeBundle\ArrayConverter\FlatToStandard;

use Akeneo\Tool\Component\Connector\ArrayConverter\ArrayConverterInterface;

class AttributeOption implements ArrayConverterInterface
{
    /**
     * @var ArrayConverterInterface
     */
    protected $baseArrayConverter;

    public function __construct(ArrayConverterInterface $baseArrayConverter)
    {
        $this->baseArrayConverter = $baseArrayConverter;
    }

    public function convert(array $item, array $options = [])
    {
        $convertedItems = [];
        $baseItem = [];

        foreach ($item as $field => $data) {
            if (preg_match('/^(constraints|type|type_config\-\w+)$/', $field)) {
                $convertedItems = $this->convertItem($field, $data, $convertedItems);
            } else {
                $baseItem[$field] = $data;
            }
        }

        return array_merge(
            $this->baseArrayConverter->convert($baseItem, $options),
            $convertedItems
        );
    }

    protected function convertItem($field, $data, array $convertedItem)
    {
        if (!array_key_exists('type_config', $convertedItem)) {
            $convertedItem['type_config'] = [];
        }

        switch ($field) {
            case 'constraints':
                $constraints = explode(',', $data);
                $convertedItem['constraints'] = array_fill_keys($constraints, []);
                break;

            case 'type_config-is_decimal':
                if (!empty($data)) {
                    $convertedItem['type_config']['is_decimal'] = (bool) $data;
                }
                break;

            case 'type_config-options_url':
                if (!empty($data)) {
                    $convertedItem['type_config']['options_url'] = $data;
                }
                break;

            case 'type_config-options':
                if (!empty($data)) {
                    $convertedItem['type_config']['options'] = json_decode($data, true);
                }
                break;

            default:
                $convertedItem[$field] = $data;
                break;
        }

        return $convertedItem;
    }
}
