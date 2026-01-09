# Migration to v2

This document describes how to migrate **Filament Passport UI** to **v2**.

Version 2 introduces a clear architectural separation between **administrative UI concerns**
and **authorization domain logic** by extracting all non-UI authorization concepts into a
dedicated core package.

This migration is **intentional and required** when upgrading from v1.

---

## Overview of the change

Starting with v2:

- All authorization-related **domain logic** lives in:
  **`n3xt0r/laravel-passport-authorization-core`**
- Filament Passport UI focuses **exclusively on administration and presentation**
- Database schema ownership has moved to the core package
- Authorization behavior and data structures are shared and reusable

This change improves:

- architectural clarity
- auditability
- long-term maintainability
- reuse outside of Filament

---

## What changed in v2

### New dependency

Filament Passport UI now depends on:

```
n3xt0r/laravel-passport-authorization-core
```

This package provides:

- scope resources
- scope actions
- scope grants
- authorization context structures

The UI package no longer owns or defines these concerns.

---

### Database migrations

All database tables related to authorization are now defined and versioned by the **core
package**.

These migrations are **not published automatically** and must be published explicitly.

Run the following commands:

```bash
php artisan vendor:publish --tag=passport-authorization-core-migrations
php artisan migrate
```

This will create or update all tables required for:

- structured scopes
- grants
- client-scoped authorization context

> Existing data is preserved as long as migrations are executed in order.

---

### Configuration

The core package provides an optional configuration file for:

- custom Passport model mappings
- integration-specific adjustments

Publish the configuration file if you need customization:

```bash
php artisan vendor:publish --tag=passport-authorization-core-config
```

The configuration file will be available at:

```
config/passport-authorization-core.php
```

If your application uses default Passport models, this step can be skipped.

---

## Behavioral changes

### Authorization logic

- Authorization logic is no longer implemented or duplicated in the UI package
- All authorization decisions and structures are resolved via the core package
- Filament Passport UI consumes the core through explicit use cases

This ensures:

- consistent authorization behavior
- a single source of truth
- audit-friendly state transitions

---

### Usecase-driven interaction

Direct interaction with models or repositories for authorization concerns is no longer
considered supported.

All write and read access to authorization state is expected to go through:

```
Application / Usecase layer (core package)
```

This applies to:

- assigning grants
- resolving effective permissions
- inspecting authorization context

---

## Upgrade checklist

Before considering the migration complete, ensure that:

- [ ] Core package is installed
- [ ] Core migrations are published and migrated
- [ ] Optional core configuration is published (if needed)
- [ ] No UI-level authorization logic remains
- [ ] All authorization writes go through core use cases

---

## Notes on backwards compatibility

- The migration is designed to be **BC-safe on the database level**
- Nullable fields are used where required to avoid breaking existing data
- Behavioral changes are intentional and documented

---

## Summary

Version 2 is a **structural upgrade**, not a cosmetic one.

Filament Passport UI is now:

- a pure administrative UI
- backed by a reusable, audit-friendly authorization core
- architecturally aligned with long-term system evolution

This migration is required to benefit from these improvements.
