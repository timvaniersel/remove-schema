# Remove Schema

Remove unwanted structured data from supported WordPress plugins and themes.

## Requirements

- WordPress `6.8+`
- PHP `8.2+`
- Node.js `24.18+`
- npm `11.16+`
- Composer `2+`

## Quick Start

```bash
composer install
npm ci
npm run build
```

For a local WordPress environment, run `npm run env:start`.

## Checks

```bash
composer test
composer lint
npm run lint:js
npm run lint:css
```

## Export Plugin ZIP

Build assets and create a release ZIP:

```bash
npm run package:release
```

The generated archive is written to `dist/remove-schema.zip`.

## Author

[Tim van Iersel](https://plugin.nl) · [tim@plugin.nl](mailto:tim@plugin.nl)
