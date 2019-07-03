# 3.0.1

## Bug fixes

- Fixes issue saving Akeneo AttributeOptions. [#29][pr29]

# 3.0.0

- Add support for Akeneo 3.0.0.

## BC breaks

- `Flagbit\Bundle\TableAttributeBundle\Normalizer\StructuredAttributeOptionNormalizer` was deleted. `Flagbit\Bundle\TableAttributeBundle\Normalizer\AttributeOptionNormalizer` is now used for its service.

[pr29]: https://github.com/flagbit/akeneo-table-attribute-bundle/pull/29
