<?php

namespace Flagbit\Bundle\TableAttributeBundle\Component\Product\Factory\Value;

use Akeneo\Pim\Enrichment\Component\Product\Factory\Value\ScalarValueFactory;
use Akeneo\Pim\Enrichment\Component\Product\Model\ValueInterface;
use Akeneo\Pim\Structure\Component\Query\PublicApi\AttributeType\Attribute;
use Akeneo\Tool\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Flagbit\Bundle\TableAttributeBundle\AttributeType\TableType;

final class TableValueFactory extends ScalarValueFactory
{
    public function createByCheckingData(
        Attribute $attribute,
        ?string $channelCode,
        ?string $localeCode,
        $data
    ): ValueInterface {
        if (!is_scalar($data) || (is_string($data) && '' === trim($data))) {
            throw InvalidPropertyTypeException::stringExpected(
                $attribute->code(),
                TableValueFactory::class,
                $data
            );
        }

        return parent::createWithoutCheckingData($attribute, $channelCode, $localeCode, $data);
    }

    public function supportedAttributeType(): string
    {
        return TableType::FLAGBIT_CATALOG_TABLE;
    }
}
