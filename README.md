# Filament Passport UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/n3xt0r/filament-passport-ui.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/filament-passport-ui)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Maintainability](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui/maintainability.svg)](https://qlty.sh/gh/N3XT0R/projects/filament-passport-ui)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/filament-passport-ui/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/n3xt0r/filament-passport-ui/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
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
