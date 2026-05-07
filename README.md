# Remove Schema

Remove all Schema Markup / Structured data (Microdata, RDFa and/or JSON-ld) that you don’t want on your site.

## Requirements

- WordPress `6.8+`
- PHP `8.2+`
- Node.js `20+`
- npm `10+`
- Composer `2+`

## Quick Start

```bash
composer install
npm install
npm run build
```

The plugin entrypoint is `remove-schema.php`. Runtime services live in `src/`.

## Testing and Verification

Run the PHP test suite:

```bash
composer test
```

Run PHP coding standards and static analysis:

```bash
composer lint
```

Run the PHP checks individually:

```bash
composer phpcs
composer phpstan
composer phpunit
```

Run JavaScript and CSS linting:

```bash
npm run lint:js
npm run lint:css
```

Run WordPress Plugin Check in the local wp-env environment:

```bash
npm run env:start
npx wp-env run cli wp plugin install plugin-check --activate
npx wp-env run cli wp plugin check remove-schema --exclude-directories=original,.phpstan,.phpunit.cache --exclude-files=.DS_Store,.gitignore,phpstan.neon.dist,phpcs.xml.dist,phpunit.xml.dist
```

Build assets as part of release verification:

```bash
npm run build
```

Full local verification:

```bash
composer test
composer lint
npm run lint:js
npm run lint:css
npm run env:start
npx wp-env run cli wp plugin install plugin-check --activate
npx wp-env run cli wp plugin check remove-schema --exclude-directories=original,.phpstan,.phpunit.cache --exclude-files=.DS_Store,.gitignore,phpstan.neon.dist,phpcs.xml.dist,phpunit.xml.dist
npm run build
```

## Export Plugin ZIP

Build assets and create a release ZIP:

```bash
npm run package:release
```

Create a ZIP from the current built files without rebuilding assets:

```bash
npm run package:zip
```

The generated archive is written to `dist/remove-schema.zip`.

## Project Metadata

- Slug: `remove-schema`
- Namespace: `TimVanIersel\RemoveSchema`
- Prefix: `remove_schema`
- Text domain: `remove-schema`
- Composer package: `timvaniersel/removeschema`
- Plugin URI: https://plugin.nl/remove-schema

## Author

- Name: Tim van Iersel
- Email: tim@plugin.nl
- URI: https://plugin.nl

## Next Steps

1. Install PHP dependencies with `composer install`.
2. Install JavaScript dependencies with `npm install`.
3. Build the admin assets with `npm run build`.
