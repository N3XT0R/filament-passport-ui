# Filament Passport UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/n3xt0r/filament-passport-ui.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/filament-passport-ui)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=N3XT0R_filament-passport-ui&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=N3XT0R_filament-passport-ui)
![ISO 27001 Audit Ready](https://img.shields.io/badge/ISO%2027001-audit--ready-blue?style=flat-square)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Maintainability](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui/maintainability.svg)](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui)
[![Code Coverage](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui/coverage.svg)](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/php-code-style.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3A"PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/n3xt0r/filament-passport-ui.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/filament-passport-ui)
---

![Filament Passport UI Logo](art/logo.png)

**Filament Passport UI** provides a structured administrative interface for managing **Laravel Passport** OAuth
resources
using **Filament v4**.

It is designed for applications that already rely on Filament as their primary admin panel and want to manage OAuth
clients, tokens, scopes, and related authorization concepts in a clear and maintainable way without custom internal
tooling or CLI-driven workflows.

This package focuses on **administration and visibility**, not on implementing OAuth flows themselves.

---

## What this package does

Filament Passport UI adds a domain-oriented admin layer on top of Laravel Passport:

- OAuth clients are managed explicitly instead of being created once via CLI
- Scopes are modeled and managed in a structured way
- Tokens and grants become visible and reviewable
- Authorization decisions remain enforced by Passport at runtime

Passport itself is **not modified or extended internally**.  
This package operates entirely at the application and UI level.

---

## Features

### OAuth Clients

- Manage OAuth clients by grant type: **authorization code**, **client credentials**, **password**, **personal access**,
  **implicit**, and **device**
- View client metadata and ownership
- Enable or revoke clients via UI

### Tokens

- View issued **access tokens**
- Revoke tokens explicitly
- Inspect token state and expiration

### Scopes & Authorization

- Manage scopes via UI instead of static configuration
- Model scopes as **resource + action**
- Group and reason about permissions in a human-readable way
- Designed to work with structured scope usage (e.g. attribute-based enforcement)

### Filament Integration

- Native **Filament v4 resources and pages**
- Consistent UX aligned with Filament conventions
- No custom panels or hacks required

### Auditability & Compliance

- Administrative actions (e.g. creating, updating, revoking clients or tokens) are fully auditable
- Changes are recorded via `spatie/laravel-activitylog`
- Enables traceability of security-relevant actions for compliance requirements (e.g. ISO/IEC 27001)
- Audit logs remain application-owned and can be integrated into existing ISMS processes

> Note: ISO/IEC 27001 certification applies to organizations and processes.
> This package supports auditability requirements but does not constitute certification or compliance by itself.

### Design Principles

- No changes to Passport internals
- No assumptions about application architecture
- Authorization context is defined by the application, not by this package
- Administrative actions are explicit and reviewable

---

## What this package does *not* do

- It does **not** implement OAuth flows
- It does **not** replace Passport
- It does **not** enforce authorization decisions by itself
- It does **not** guess application context or security rules

Authorization logic remains the responsibility of the application and its developers.

---

## Why this exists

Laravel Passport provides a standards-compliant OAuth2 implementation and intentionally stays neutral regarding
administration and governance.

In real-world applications, this often results in:

- OAuth clients created via CLI and never revisited
- Unclear ownership of machine-to-machine clients
- Scopes defined ad-hoc as strings without structure
- No central overview of active tokens and permissions

**Filament Passport UI fills this gap** by providing:

- Visibility into OAuth configuration
- A structured mental model for scopes
- Explicit administrative workflows
- A single place to review and manage OAuth-related state

This is especially useful in systems with multiple integrations, services, or teams.

---

## Requirements

- PHP ^8.4
- Laravel ^12
- Laravel Passport
- Filament v4

---

## Installation

Install via Composer:

```bash
composer require n3xt0r/filament-passport-ui
```

Run the installer:

```bash
php artisan filament-passport-ui:install
```

---

## Models & Configuration

By default, the package uses the standard Laravel Passport models.

If your application uses custom Passport models, publish the configuration file and adjust the model mappings
accordingly.

---

## Documentation

Additional documentation is available at:
[Docs](docs/index.md)

---

## Testing

```bash
composer install
composer test
```

### Local development

```bash
composer install
composer serve
```

Open:

```
http://localhost:8000/admin
```

login with `test@example.com` and `password`.

---

## Status

This package is actively developed and evolving.

Feedback, issues, and architectural discussion are welcome.

--- 

## Future Architecture Notes

A dedicated core package for non-Filament-related authorization and domain logic already exists:

https://github.com/N3XT0R/laravel-passport-authorization-core

In future releases, additional shared authorization concepts and domain logic **will be progressively consolidated**
into this core package as part of the planned architectural evolution.

The goal is to keep **Filament Passport UI** focused purely on **administrative UI concerns**, while all reusable and
Filament-agnostic authorization logic is maintained within the core package.

This consolidation is a deliberate and planned architectural step.  
The Filament-facing API and user-facing behavior of this package are expected to remain stable throughout this process.

