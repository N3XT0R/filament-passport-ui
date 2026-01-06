# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Filament Passport UI v1.0.0 - 2026-01-06

### 🎉 Filament Passport UI v1.0.0

This is the **first stable release** of **Filament Passport UI**, providing a structured and auditable administrative interface for managing **Laravel Passport OAuth2** resources using **Filament v4**.

The package focuses on **visibility, governance, and explicit administration** of OAuth configuration, without modifying Passport internals or enforcing authorization logic at runtime.


---

### ✨ Highlights

- Native **Filament v4 plugin** for managing Laravel Passport OAuth clients, tokens, and scopes
- Full support for **all Passport grant types**
- **Database-backed scope modeling** using structured *resource + action* concepts
- Clean Architecture–inspired **Use Case layer** with explicit domain behavior
- Comprehensive **audit logging** and event-based lifecycle tracking
- Designed for **administration and compliance**, not OAuth flow implementation


---

### 🧩 Key Features

#### OAuth Client & Token Management

- Manage OAuth clients through dedicated Filament resources
- Support for Authorization Code, Client Credentials, Password, Personal Access, Implicit, and Device grants
- Inspect, identify, and revoke issued access tokens with improved traceability

#### Scopes & Authorization Modeling

- Database-backed scope management
- Scopes modeled as **resources and actions**
- Granular scope-to-owner assignments
- Centralized scope registry with caching support

#### Explicit Domain Architecture

- Application Use Cases for all critical operations (create, edit, revoke, ownership changes)
- Domain events dispatched explicitly for lifecycle actions
- Strategy-based OAuth client factory per grant type
- Repository and service layers with clear contracts and cached decorators

#### Administrative UX Enhancements

- Revoke status visibility and toggles for OAuth clients
- Custom Filament actions bound directly to use cases
- Consistent UX aligned with Filament conventions

#### Auditability & Operations

- Full audit logging via `spatie/laravel-activitylog`
- Console commands for installation and cache management
- Designed to support compliance and traceability requirements (e.g. ISO/IEC 27001)

#### Configuration & Extensibility

- Customizable owner models and Passport mappings
- Optional database-backed scopes
- Navigation customization
- English and German localization

#### Tooling & Quality

- Database migrations and seeders for scope management
- Automated test coverage for stability
- Comprehensive documentation covering configuration, usage, and testing


---

### 🧭 Stability Notice

This release marks the **stable 1.0.0 API**.
Future changes will follow **Semantic Versioning**.

Non-Filament-related authorization and domain logic may be extracted into a dedicated core package in future versions, while the Filament-facing API and behavior are expected to remain stable.

## [Unreleased]

## [1.0.0] - 2026-01-06

### Added

- **Filament v4 Plugin** for Laravel Passport OAuth2 administration via `FilamentPassportUiPlugin`.
  
- **OAuth Client Management** through a dedicated Filament resource (`ClientResource`) with full support for all
  Passport grant types:
  
  - Authorization Code
  - Client Credentials
  - Password Grant
  - Personal Access
  - Implicit Grant
  - Device Grant
  
- **Token Management Resource** (`TokenResource`) for inspecting, identifying, and revoking issued access tokens,
  including an explicit token ID column for improved traceability.
  
- **Database-backed Scope Management** with structured *resource + action* modeling:
  
  - `PassportScopeResourceResource` for managing scope resources
  - `PassportScopeActionsResource` for managing scope actions
  - `PassportScopeGrant` model for granular scope-to-owner assignments
  
- **Application Use Case Layer** following Clean Architecture principles, providing explicit and auditable domain
  operations:
  
  - `CreateClientUseCase`
  - `EditClientUseCase`
  - `GetAllowedGrantTypeOptions`
  - `GetAllOwnersUseCase`
  - `GetAllOwnersRelationshipUseCase`
  - `SaveOwnershipRelationUseCase`
  - `ClearCacheUseCase` for centralized cache invalidation
  
- **Explicit Domain Event Dispatching** for all relevant lifecycle operations (create, update, delete, revoke), ensuring
  that domain events are emitted only when actions are executed through use cases, resulting in predictable and
  traceable behavior.
  
- **OAuth Client Factory** using a strategy-based approach to encapsulate grant-specific client creation logic:
  
  - `AuthorizationCodeClientStrategy`
  - `ClientCredentialsClientStrategy`
  - `PasswordGrantClientStrategy`
  - `PersonalAccessClientStrategy`
  - `ImplicitGrantClientStrategy`
  - `DeviceGrantClientStrategy`
  
- **Service Layer Abstractions** for core authorization concerns:
  
  - `ClientService` for OAuth client lifecycle and ownership handling
  - `GrantService` for granting and revoking scopes on tokenable models
  - `ScopeRegistryService` for scope discovery, registration, and caching
  
- **Repository Pattern with Contracts** for all core domain models:
  
  - `ClientRepository`, `TokenRepository`, `OwnerRepository`
  - `ResourceRepository`, `ActionRepository`, `ScopeGrantRepository`
  - Cached repository decorators to improve performance and reduce query overhead
  
- **UI Enhancements for Client Administration**:
  
  - `RevokeColumn` for explicit revoke-state visibility in client tables
  - `RevokeToggle` for controlled client revocation via forms
  - Custom Filament actions bound directly to use cases for consistent logging and behavior
  
- **Event System** covering OAuth and scope lifecycle changes:
  
  - `OAuthClientCreated`, `OAuthClientRevoked`
  - `ScopeCreated`, `ScopeDeactivated`
  
- **Audit Logging Integration** via `spatie/laravel-activitylog`, providing full traceability of security-relevant
  administrative actions.
  
- **Value Objects and DTOs** for stricter domain modeling and type safety:
  
  - `ScopeName` value object for structured scope naming
  - `CreateOAuthClientData`, `ScopeDTO` for explicit data transfer
  
- **Configurable Package Options** via `config/passport-ui.php`, including:
  
  - Custom owner model and label attribute
  - Toggle for database-backed scopes
  - Custom Passport model mappings
  - Navigation customization options
  
- **Console Commands** for operational support:
  
  - Interactive install command
  - `filament-passport-ui:cleanup-cache` for explicit cache invalidation, including scope registry cache
  
- **Localization Support** with English and German translations.
  
- **Database Migrations and Seeders** for scope management:
  
  - `passport_scope_resources`
  - `passport_scope_actions`
  - `passport_scope_grant`
  - Default seed data for initial scope setup
  
- **Test Coverage for Stability**, ensuring predictable behavior across authorization, ownership, and scope management
  features.
  
- **Comprehensive Documentation** covering installation, configuration, scoped authorization concepts, and testing
  strategies.
  

### [1.0.0-beta.3] - 2026-01-06

### Added

- Added `IdColumn` to `TokenTable` for better token identification
- introduce custom actions bound to use cases

### Changed

- Replaced default DeleteAction and record create/update handlers with use case-based implementations for unified
  logging.

### Fixed

- Fixed a bug where wrong model was used in `LastLoginColumn`
- Fixed owner resolution in `EditClientUseCase` when receiving an identifier instead of an entity.

### [1.0.0-beta.2] - 2026-01-05

### Added

- introduced tests for stability

### Changed

- Moved Logic from UI layer to Usecase layer for better separation of concerns

### Fixed

- some minor bugs on method declarations and edge cases fixed

### [1.0.0-beta.1] - 2026-01-04

### Added

- **Filament v4 Plugin** for Laravel Passport OAuth2 administration via `FilamentPassportUiPlugin`
- **OAuth Client Management Resource** (`ClientResource`) with support for all grant types:
  - Authorization Code
  - Client Credentials
  - Password Grant
  - Personal Access
  - Implicit Grant
  - Device Grant
  
- **Token Management Resource** (`TokenResource`) for viewing and managing issued access tokens
- **Database-backed Scope Management** with structured resource + action modeling:
  - `PassportScopeResourceResource` for managing scope resources
  - `PassportScopeActionsResource` for managing scope actions
  - `PassportScopeGrant` model for granular scope assignments
  
- **OAuth Client Factory** with strategy pattern for creating different client types:
  - `AuthorizationCodeClientStrategy`
  - `ClientCredentialsClientStrategy`
  - `PasswordGrantClientStrategy`
  - `PersonalAccessClientStrategy`
  - `ImplicitGrantClientStrategy`
  - `DeviceGrantClientStrategy`
  
- **Application Use Cases** (Clean Architecture pattern):
  - `CreateClientUseCase` for creating new OAuth clients with automatic scope assignment
  - `EditClientUseCase` for modifying existing OAuth clients
  - `GetAllowedGrantTypeOptions` for retrieving available grant types
  - `GetAllOwnersUseCase` and `GetAllOwnersRelationshipUseCase` for owner lookup
  - `SaveOwnershipRelationUseCase` for transferring client ownership
  
- **Service Layer**:
  - `ClientService` for OAuth client lifecycle management with ownership support
  - `GrantService` for granting/revoking scopes on tokenable models
  - `ScopeRegistryService` for scope discovery and registration
  
- **Repository Pattern** with contracts:
  - `ClientRepository`, `TokenRepository`, `OwnerRepository`
  - `ResourceRepository`, `ActionRepository`, `ScopeGrantRepository`
  - Cached repository decorators for performance
  
- **Event System**:
  - `OAuthClientCreated`, `OAuthClientRevoked` events
  - `ScopeCreated`, `ScopeDeactivated` events
  
- **Observer Support** for `Client`, `PassportScopeAction`, and `PassportScopeResource` models
- **Activity Logging** integration via Spatie Activity Log for OAuth operations
- **Value Objects**: `ScopeName` for structured scope naming convention
- **DTOs**: `CreateOAuthClientData`, `ScopeDTO` for type-safe data transfer
- **Configurable options** via `config/passport-ui.php`:
  - Custom owner model and label attribute
  - Toggle database-backed scopes
  - Custom model mappings for Passport models
  - Navigation customization
  
- **Localization** support with English and German translations
- **Database Migrations** for scope management tables:
  - `passport_scope_resources`
  - `passport_scope_actions`
  - `passport_scope_grant`
  
- **Database Seeders** for default scope resources and actions
- **Install Command** with interactive setup wizard
- **Comprehensive Documentation** covering configuration, scoped controllers, and testing
