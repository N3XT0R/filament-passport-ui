# Filament Passport UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/n3xt0r/filament-passport-ui.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/filament-passport-ui)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=N3XT0R_filament-passport-ui&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=N3XT0R_filament-passport-ui)
![ISO 27001 Audit Ready](https://img.shields.io/badge/ISO%2027001-audit--ready-blue?style=flat-square)
![PHP 8.4/8.5](https://img.shields.io/badge/PHP-8.4%2F8.5-777BB4?style=flat-square)
![Filament 4/5](https://img.shields.io/badge/Filament-4%2F5-FDAE4B?style=flat-square)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Maintainability](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui/maintainability.svg)](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui)
[![Code Coverage](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui/coverage.svg)](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/php-code-style.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3A"PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/n3xt0r/filament-passport-ui.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/filament-passport-ui)

![Filament Passport UI Logo](art/logo.png)

**Filament Passport UI** provides a structured administrative interface for managing **Laravel Passport** OAuth
resources using **Filament**.

This package focuses on **administration, visibility, and governance** not on implementing or enforcing OAuth flows.

Designed for applications that already rely on Filament as their primary admin panel and need to manage OAuth clients,
tokens, scopes, and authorization concepts in a centralized, reviewable way without custom tooling or CLI workflows.

## Overview

Filament Passport UI adds an administration layer on top of Laravel Passport:

- Manage OAuth clients explicitly by grant type (authorization code, client credentials, password, personal access,
  implicit, device)
- View and revoke access tokens with full visibility into state and expiration
- Model scopes as structured `resource:action` pairs instead of free-form strings
- Track grants and authorization relationships centrally
- Make all authorization decisions explicit and auditable

**Important:** Passport itself is not modified. This package operates entirely at the application and UI level.

## Features

### Central Management

- Filter and manage OAuth clients by grant type
- Enable or revoke clients via UI
- Structure scopes (not ad-hoc strings)
- Full visibility into authorization state
- Explicit client-level and user-level scope assignment
- Enforce Least Privilege: user scopes are always a strict subset of client scopes

### Filament Integration

- Native Filament Resources and Pages
- Consistent UX aligned with Filament conventions
- Multi-step wizard for client creation with contextual steps based on grant type
- No custom panels required

### Auditability & Compliance

- All administrative actions recorded via `spatie/laravel-activitylog`
- Full traceability of OAuth configuration changes
- Supports compliance requirements (e.g. ISO/IEC 27001)
- Audit logs remain application-owned

> Note: Certification is organization-specific. This package enables auditability but does not constitute compliance by
> itself.

### Design Principles

- No modifications to Passport internals
- No assumptions about application architecture
- Authorization logic remains the responsibility of the application
- All administrative actions are explicit and reviewable

## What This Package Does NOT Do

- Implement OAuth flows
- Replace Passport
- Enforce authorization decisions at runtime
- Infer application-specific security rules

Authorization logic is the responsibility of the application and its developers.

## Why This Exists

Laravel Passport is standards-compliant but intentionally stays neutral on administration and governance. In real-world
applications, this often results in:

- OAuth clients created via CLI and never revisited
- Scopes defined ad-hoc without structure
- No central visibility of active tokens
- Unclear ownership of integrations across teams

**Filament Passport UI solves this** by providing:

- Central visibility into OAuth configuration
- Structured scope modeling (resource:action)
- Explicit administrative workflows
- Single point of review and governance

Essential for systems with multiple integrations or teams managing OAuth access.

## Requirements

- PHP ^8.4 / PHP ^8.5
- Laravel ^12 / Laravel ^13
- Laravel Passport ^13
- Filament v4 / Filament v5

## Installation

```bash
composer require n3xt0r/filament-passport-ui
php artisan filament-passport-ui:install
```

If your application uses custom Passport models, publish the configuration file and adjust model mappings accordingly.

## Architecture

The package maintains strict separation of concerns:

- **Domain Logic:**
  [Laravel Passport Authorization Core](https://github.com/N3XT0R/laravel-passport-authorization-core) (scope and
  grant modeling, authorization context resolution)
- **UI Layer:** Filament Passport UI (administration interface, visibility, governance)

The API remains stable while authorization logic evolves in the core package.

## Migration to v2

Starting with v2, Filament Passport UI uses the
**[Laravel Passport Authorization Core](https://github.com/N3XT0R/laravel-passport-authorization-core)** package.

Database schema and optional configuration are managed by the core package and not published automatically.

See **[Migration to v2](https://github.com/N3XT0R/filament-passport-ui/blob/main/docs/migration-to-v2.md)** for detailed
instructions.

## Development & Testing

```bash
composer install
composer test       # Run tests
composer serve      # Start local dev server
```

Access admin at `http://localhost:8000/admin`  
Login: `test@example.com` / `password`

## Documentation & Status

- **Docs:** [Full Documentation](https://github.com/N3XT0R/filament-passport-ui/blob/main/docs/index.md)
- **Status:** Actively developed. Feedback and discussion welcome on GitHub