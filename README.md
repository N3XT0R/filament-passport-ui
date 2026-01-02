# Filament Passport UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/n3xt0r/filament-passport-ui.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/filament-passport-ui)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Maintainability](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui/maintainability.svg)](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/php-code-style.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3A"PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/n3xt0r/filament-passport-ui.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/filament-passport-ui)

---

![Filament Passport UI Logo](art/logo.png)

Filament admin UI for managing **Laravel Passport** OAuth clients, tokens, and scopes.

This package provides a clean, opinionated Filament integration for Laravel Passport, allowing OAuth-related resources
to be managed directly from the Filament admin panel instead of via CLI commands or static configuration.

It is intended for applications that already use Filament as their primary admin interface and want first-class Passport
administration without maintaining custom internal tooling.

---

## Features

- Manage **OAuth clients** (personal access, password, client credentials)
- View and revoke **access tokens**
- Manage **scopes** via UI (optionally database-driven)
- Native **Filament v4 Resources & Pages**
- No modifications to Passport internals
- Works with existing Passport installations

> This package focuses on **administration**, not authentication flows.

---

## Why this exists

Laravel Passport provides a solid and standards-compliant OAuth2 implementation.  
It intentionally focuses on protocol mechanics and leaves administrative concerns to the application.

In practice, this often leads to recurring problems:

- OAuth clients are created once via CLI and then forgotten
- Client ownership is unclear, especially for `client_credentials` grants
- Scopes are either not used at all or defined ad-hoc in code without structure
- There is no central place to review, manage, or reason about OAuth configuration

This package exists to fill that gap.

**Filament Passport UI adds an administrative and domain-oriented layer on top of Laravel Passport**, without changing
its behavior or assumptions. It does not replace Passport, nor does it attempt to redefine OAuth flows. Instead, it
provides structure, visibility, and responsibility where Passport intentionally remains neutral.

Key principles behind this package:

- OAuth clients should have explicit ownership
- Scopes should be composable, understandable, and centrally managed
- Administrative actions should be visible, reviewable, and auditable
- OAuth configuration should be manageable without embedding governance logic in application code

By integrating with Filament, this package offers a practical and predictable way to manage OAuth clients and scopes in
real-world applications, especially in systems with multiple integrations, services, or teams.

---

## Requirements

- PHP ^8.4
- Laravel ^12
- Laravel Passport
- Filament v4

---

## Installation

Install the package via Composer:

```bash
composer require n3xt0r/filament-passport-ui 
```

```bash
php artisan filament-passport-ui:install
``` 

### Models

By default, the package uses the standard Laravel Passport models. If you have custom models, publish the config file
and update the model classes accordingly.

#### Docs

Documentation is available at:
[Docs](docs/index.md)

## Tests

```bash
composer install
composer test
```

### running local for Development

```bash
composer install
./vendor/bin/testbench filament:install --panels
composer serve
```

> Open http://localhost:8000/admin
