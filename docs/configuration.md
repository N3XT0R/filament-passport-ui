# Configuration

Filament Passport UI exposes **very limited configuration** by design.

Starting with v2, all authorization- and domain-related configuration has been
moved to the **Laravel Passport Authorization Core** package.

Filament Passport UI itself only provides configuration for **UI-specific
concerns**.

---

## Installation

```bash
composer require n3xt0r/filament-passport-ui
```

After installation, run the installer command:

```bash
php artisan filament-passport-ui:install
```

The installer performs the following tasks:

- publishes the Filament Passport UI configuration file
- triggers required seeders
- ensures required core package assets are available

> Note:  
> Core-related migrations and configuration are owned by
> `laravel-passport-authorization-core` and are documented separately.

---

## Configuration File

After installation, the configuration file is available at:

```
config/passport-ui.php
```

### Available Options

#### Navigation Group

Filament Passport UI allows configuring the **Filament navigation group**
under which all Passport-related resources are registered.

```php
return [

   /*
    |--------------------------------------------------------------------------
    | Navigation Groups
    |--------------------------------------------------------------------------
    |
    | This values controls the navigation group name used by Filament
    | for all Passport-related resources.
    |
    */

    'navigation_group' => 'Authentication',
];
```

This is the **only configurable option** provided by Filament Passport UI.

All authorization rules, scope definitions, grants, and context handling
are configured and resolved by the core package.

---

## Authorization Core Configuration

Authorization-related configuration, including:

- scope resources
- scope actions
- grants
- authorization context

is handled by the **Laravel Passport Authorization Core** package.

Refer to the core documentation for details:

```
docs/migration-to-v2.md
```

and the core package repository:

https://github.com/N3XT0R/laravel-passport-authorization-core

---

## Seeders

The `filament-passport-ui:install` command triggers the required seeders
to ensure that default authorization structures are available.

These seeders may also be triggered independently via the core package
installer if needed.

Running both installers is safe and idempotent.

---

## Summary

- Filament Passport UI exposes **UI-only configuration**
- Authorization configuration lives exclusively in the core package
- Only the navigation group is configurable at the UI level
- Installer commands remain the recommended integration path
