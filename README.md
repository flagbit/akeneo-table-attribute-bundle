# TableAttributeBundle #

Adds the new AttributeType *Table* for Akeneo products.

### Installation ###

Since there is still no stable version and no releases in packagist.org, you must first manually add the repository 
to your `composer.json`.

``` json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://bitbucket.org/flagbit/tableattributebundle.git"
        }
    ],
```

Now you can simply install the package with the following command. 

``` bash
composer require flagbit/table-attribute-bundle:@alpha
```

#### Enable the bundle ####

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

#### TODO ####

Release package on packagist and add documentation for installation and configuration of this bundle for a Akeneo project.


