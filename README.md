<h1 align="center">
  Flagbit Table Attribute for Akeneo PIM
  <br>
</h1>

<h4 align="center">Adds the new attribute type Table for Akeneo products.</h4>

<p align="center">
    <a href="https://travis-ci.org/flagbit/akeneo-table-attribute-bundle">
        <img src="https://img.shields.io/travis/flagbit/akeneo-table-attribute-bundle/master.svg?style=flat-square"/>
    </a>
    <img src="https://poser.pugx.org/flagbit/table-attribute-bundle/downloads?format=flat-square">
    <a href="https://scrutinizer-ci.com/g/Flagbit/akeneo-table-attribute-bundle">
        <img src="https://img.shields.io/scrutinizer/g/flagbit/akeneo-table-attribute-bundle.svg?style=flat-square">
    </a>
    <a href="https://packagist.org/packages/flagbit/table-attribute-bundle">
        <img src="https://img.shields.io/packagist/v/flagbit/table-attribute-bundle.svg?style=flat-square">
    </a>
    <a href="LICENSE">
        <img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square">
    </a>
</p>

<p align="center">
  <a href="#key-features">Key Features</a> •
  <a href="#installation">Installation</a> •
  <a href="#compatibility">Compatibility</a> •
  <a href="#development">Development</a> •
  <a href="#contributing">Contributing</a>
</p>

## Key Features

Provides a _table_ as attribute type where you can define a set of columns of different types and validation rules.

#### Column Types

* Text
* Number (Integer or Decimal)
* Simple select
* Simple select from URL

#### Import/Export

The extension supports the standard Akeneo product import/export, so you don't need to create any special import/export profile for table information.

All product information related to attributes of type _table_ will be imported/exported as JSON. 

## Installation

Simply install the package with the following command: 

``` bash
composer require flagbit/table-attribute-bundle
```

### Enable the bundle

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

### Configuration

Add `mapping_overrides` in `app/config/config.yml`:

``` yml
akeneo_storage_utils:
    mapping_overrides:
        -
            original: Akeneo\Pim\Structure\Component\Model\AttributeOption
            override: Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption
```

Clear the cache:

``` bash
php bin/console --env=prod cache:clear
```

Update the database schema:

``` bash
php bin/console --env=prod doctrine:schema:update --force
```

Build and install the new front-end dependencies (new icon, etc.)

``` bash
php bin/console --env=prod pim:installer:assets --symlink --clean
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

## Compatibility

This extension supports the latest Akeneo PIM CE/EE stable versions:

* 3.0 (LTS)
* 2.3 (LTS)

## Development

### Running Test-Suits

The TableAttributeBundle is covered with tests and every change and addition has also to be covered with
unit or/and integration tests. It uses two testing suits: [PHPSpec](https://www.phpspec.net) and
[PHPUnit](https://phpunit.de/).

To run the tests you have to change to this project's root directory and run the following commands in your console:

``` bash
vendor/bin/phpunit
vendor/bin/phpspec run
```

### Coding style

TableAttributeBundle uses the [PSR-2](https://www.php-fig.org/psr/psr-2/) coding style and can be checked with
[Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer).

``` bash
vendor/bin/phpcs --standard=PSR2 --extensions=php ./src
```

## Contributing

Contributions are always welcome! Please have a look at the [contribution guidelines](CONTRIBUTING.md) first.

## License

The TableAttributeBundle is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

#

<p align="center">
Supported with ❤ by <a href="https://www.flagbit.de">Flagbit GmbH & Co. KG</a>
</p>
