# akeneo-table-attribute-bundle

Symfony bundle that adds a "table" attribute type to Akeneo PIM 6.0, allowing products to carry multi-dimensional data as configurable column tables. Distributed as a Composer library (`flagbit/table-attribute-bundle`); installed inside an Akeneo PIM instance.

## Tech Stack

- **Language**: PHP 8.0
- **Framework**: Symfony 5.4 (peer dependency via Akeneo), Doctrine
- **Host platform**: Akeneo PIM Community Edition ^6.0
- **Frontend**: JavaScript (RequireJS modules), LESS stylesheets
- **Testing**: PHPSpec 7.2 (unit/spec), PHPUnit 9.0 (integration), Jest (frontend)
- **Code quality**: PHP CodeSniffer (PSR-2), PHPLint, Scrutinizer CI

## Repository Structure

```
src/                    Bundle source (PSR-4: Flagbit\Bundle\TableAttributeBundle\)
  AttributeType/        Akeneo attribute type definitions
  Component/            Core table value components
  Controller/           Symfony controllers
  DependencyInjection/  Bundle extension and configuration
  Entity/               Doctrine entities
  Form/                 Symfony form types
  Http/                 HTTP utilities
  Normalizer/           Akeneo normalizer implementations
  Provider/             Service providers
  Resources/            Config (services.xml), translations (EN/DE), JS/LESS assets
  Validator/            Constraint validators and guesser
spec/                   PHPSpec specifications (unit tests)
tests/                  PHPUnit integration tests (TestKernel-based)
jest/                   Jest frontend tests
```

## Local Development

This is a library bundle — it is installed *inside* an existing Akeneo PIM instance, not run standalone. For isolated testing:

**Prerequisites**: PHP 8.0, Composer, Node 12, Yarn

```bash
composer install
yarn install
```

No `.env` file is required for running tests; the integration test kernel is self-contained.

## Build / Test / Lint

All commands run from the repository root after `composer install`.

**PHP linting**
```bash
vendor/bin/phplint ./src
```

**Code style (PSR-2)**
```bash
vendor/bin/phpcs -d memory_limit=-1 --standard=PSR2 --extensions=php ./src
```

**PHPSpec (unit/spec)**
```bash
vendor/bin/phpspec run
```

**PHPUnit (integration)**
```bash
vendor/bin/phpunit
```

**Frontend tests (Jest)**
```bash
yarn run test
# expands to: yarn update-extensions && jest --no-cache --config jest.config.js
```

CI runs all four steps on every push/PR via `.github/workflows/main.yml`.

## Conventions

- PSR-4 autoloading under namespace `Flagbit\Bundle\TableAttributeBundle\`
- Code style: PSR-2 (enforced by phpcs.xml)
- Unit specs live in `spec/`, integration tests in `tests/`
- Translations in `src/Resources/translations/` for `en` and `de` locales
- Versioned changelogs per major release: `CHANGELOG-<major>.<minor>.md`
- Conventional Commits for commit messages

## Gotchas / Notes

- Requires `akeneo/pim-community-dev ^6.0` as a peer dependency — the bundle cannot be tested without Composer installing the full Akeneo PIM vendor tree (large download).
- Frontend tests depend on Akeneo's own Node workspace being present in `vendor/akeneo/pim-community-dev`; run `yarn install` with workspaces to populate it.
- `package.json` declares `"private": true` and configures Yarn workspaces pointing into the Akeneo vendor directory — this is intentional for test setup, not a standalone app.
- PHPSpec config (`phpspec.yml` or inline) and PHPUnit config (`phpunit.xml.dist`) are the authoritative sources for test suites; a `phpunit.xml.dist.bak` exists as a backup, do not use it.
