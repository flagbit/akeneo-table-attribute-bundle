# Flagbit TableAttributeBundle for Akeneo PIM #

[![Build Status](https://img.shields.io/travis/flagbit/akeneo-table-attribute-bundle/master.svg?style=flat-square)](https://travis-ci.org/flagbit/akeneo-table-attribute-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/flagbit/akeneo-table-attribute-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Flagbit/akeneo-table-attribute-bundle)
[![Packagist Version](https://img.shields.io/packagist/v/flagbit/table-attribute-bundle.svg?style=flat-square)](https://packagist.org/packages/flagbit/table-attribute-bundle)
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

Add to config yml to `mapping_overrides` in `app/config/config.yml`:

``` yml
akeneo_storage_utils:
    mapping_overrides:
        -
            original: Pim\Bundle\CatalogBundle\Entity\AttributeOption
            override: Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption
```

Clear your cache:

``` bash
php bin/console --env=prod cache:clear
```

Update the database schema:

``` bash
php bin/console --env=prod doctrine:schema:update --force
```

Build and install the new front-end dependencies (new icon, etc.)

``` bash
php bin/console pim:installer:assets --symlink --clean --env=prod
yarn run webpack
```

In case you're using Doctrine migrations, you have to create a new migration class

``` bash
php bin/console --env=prod doctrine:migration:diff
```

and migrate the schema updates:

``` bash
php bin/console --env=prod doctrine:migrations:migrate
```

## Features ##

Provides a the table as attribute type where you can define a set of columns of different types and validation rules.

#### Column Types ####
* Text
* Number (Integer or Decimal)
* Simple select
* Simple select from URL

#### Import/Export ####
The extension supports the standard Akeneo product import/export, so you don't need to create any special import/export
profile for table information.

All product information related to attributes of type _table_ will be imported/exported as JSON. 

## Akeneo Compatibility ##

This extension supports the latest Akeneo PIM CE/EE stable versions:

* 2.3 (LTS)
* 2.1 and 2.2 (STS)

## License ##

The TableAttributeBundle is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
