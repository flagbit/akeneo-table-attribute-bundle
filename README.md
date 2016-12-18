# TableAttributeBundle #

Adds the new AttributeType *Table* for Akeneo products.

### Installation ###

``` php
composer require flagbit/table-attribute-bundle
```

#### Override AttributeOption ####

Add to config yml to `mapping_overrides`:

``` yml
akeneo_storage_utils:
    mapping_overrides:
        -
            original: Pim\Bundle\CatalogBundle\Entity\AttributeOption
            override: Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption
```

Update the database schema:

```
php app/console -e=prod d:s:u --force
```

In case you're using Doctrine migrations, you have to create a new migration class

```
php app/console -e=prod doctrine:migration:diff
```

and migrate the schema updates:

```
php app/console doctrine:migrations:migrate
```

#### TODO ####

Release package on packagist and add documentation for installation and configuration of this bundle for a Akeneo project.
