# Contributing

Contributions are welcome! Please follow these steps to set up the project and ensure your changes pass all checks before submitting a PR.

## Setup

1. Fork the repository
2. Clone your fork
3. Install dependencies:

```bash
composer install
```

## Available Scripts

| Command | Description |
|---|---|
| `composer cs-check` | Check code style with PHPCS (PSR-12) |
| `composer cs-fix` | Auto-fix code style violations |
| `composer stan` | Run PHPStan static analysis (level 1) |
| `composer test` | Run the test suite (Pest) |
| `composer check` | Run all checks (style, static analysis, tests) |

## Before Submitting a PR

Make sure all checks pass locally:

```bash
composer check
```

This runs the three checks sequentially:

1. **Code style** (`composer cs-check`) — must pass with no violations
2. **Static analysis** (`composer stan`) — must pass with no errors
3. **Tests** (`composer test`) — all tests must pass

If you have code style violations, you can auto-fix most of them:

```bash
composer cs-fix
```

## CI Pipeline

A GitHub Actions CI pipeline runs automatically on every push and pull request targeting `main`. It runs all checks across **PHP 8.2, 8.3, 8.4, and 8.5**.
