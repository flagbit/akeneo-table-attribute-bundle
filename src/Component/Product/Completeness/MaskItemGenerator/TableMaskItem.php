<?php

namespace Flagbit\Bundle\TableAttributeBundle\Component\Product\Completeness\MaskItemGenerator;

use Akeneo\Pim\Enrichment\Component\Product\Completeness\MaskItemGenerator\MaskItemGeneratorForAttributeType;
use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;

class TableMaskItem implements MaskItemGeneratorForAttributeType
{
    public function forRawValue(string $attributeCode, string $channelCode, string $localeCode, $value): array
    {
        return [
            sprintf(
                '%s-%s-%s',
                $attributeCode,
                $channelCode,
                $localeCode
            )
        ];
    }

    public function supportedAttributeTypes(): array
    {
        return [
            TableType::FLAGBIT_CATALOG_TABLE,
        ];
    }
}
