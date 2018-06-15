# Flagbit TableAttributeBundle for Akeneo PIM #

[![Build Status](https://img.shields.io/travis/Flagbit/akeneo-table-attribute-bundle.svg?style=flat-square)](https://github.com/Flagbit/akeneo-table-attribute-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/Flagbit/akeneo-table-attribute-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Flagbit/akeneo-table-attribute-bundle)
[![Packagist Version](https://img.shields.io/packagist/v/Flagbit/table-attribute-bundle.svg?style=flat-square)](https://packagist.org/packages/flagbit/table-attribute-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Adds the new attribute type *Table* for Akeneo products.

## Installation ##

Now you can simply install the package with the following command. 

``` bash
composer require flagbit/table-attribute-bundle
```

### Enable the bundle ###

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

protected function registerProjectBundles()
{
    return [
        // ...
        new Flagbit\Bundle\TableAttributeBundle\FlagbitTableAttributeBundle(),
        // ...
    ];
}
```

### Configuration ###

Add to config yml to `mapping_overrides`:

``` yml
akeneo_storage_utils:
    mapping_overrides:
        -
            original: Pim\Bundle\CatalogBundle\Entity\AttributeOption
            override: Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption
```

Update the database schema:

``` bash
php app/console -e=prod doctrine:schema:update --force
```

In case you're using Doctrine migrations, you have to create a new migration class

``` bash
php app/console -e=prod doctrine:migration:diff
```

and migrate the schema updates:

``` bash
php app/console doctrine:migrations:migrate
```

## License ##

The TableAttributeBundle is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
