# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is `gupalo/twigext`, a PHP library providing Twig template extensions. It offers filters and functions for arrays, encoding, formatting, JSON, progress bars, random values, and strings.

## Commands

### Run Tests
```bash
./vendor/bin/phpunit
```

### Run a Single Test
```bash
./vendor/bin/phpunit tests/TwigExt/ArrayExtensionTest.php
./vendor/bin/phpunit --filter testMaxValue
```

### Run Static Analysis
```bash
./vendor/bin/phpstan analyse
```
PHPStan is configured at level 4 via `phpstan.dist.neon`.

### Install Dependencies
```bash
composer install
```

## Architecture

### Namespaces
- `Gupalo\TwigExt\` - Twig extensions (in `src/TwigExt/`)
- `Gupalo\TwigExtException\` - Exceptions (in `src/TwigExtException/`)
- `Gupalo\Tests\TwigExt\` - Tests (in `tests/TwigExt/`)

### Extension Pattern
Each extension class extends `Twig\Extension\AbstractExtension` and registers filters/functions via `getFilters()` or `getFunctions()`. Methods are registered using first-class callable syntax (`$this->methodName(...)`).

### Key Dependencies
- Requires PHP 8.4+
- Uses `gupalo/dateutils` for date formatting in `FormatExtension`
- Uses `symfony/property-access` for object property access in `ArrayExtension`
- Uses `gupalo/json` for JSON operations

### FormatExtension Customization
`FormatExtension` accepts constructor parameters for customizing CSS classes and HTML element output via translations array and `$wrapSpan` flag.
