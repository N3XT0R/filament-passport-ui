# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.3.0] - 2026-03-22

### Added

- Added support for **Laravel 13**

## [2.2.0] - 2026-01-31

### Added

- Added support for **Filament v5** while maintaining compatibility with **Filament v4**.
- Added official support for **PHP 8.5**.

## [2.1.0] - 2026-01-18

### Added

- Added explicit separation between **client scopes** and **user scopes**, enforcing a clear authorization model where
  user permissions are always a subset of the client’s granted capabilities.
- Added support for configuring **client-level scopes** and **user-level scopes** independently during client creation,
  while guaranteeing that users can never receive permissions not granted to the client itself.
- Introduced a new, component-based scope configuration system built on dedicated, reusable UI components instead of
  form builders.
- Added a dedicated `ScopeCheckboxList` component to encapsulate scope rendering, grouping, and and selection logic in a
  reusable and UI-focused way.
- Added support for dynamically restricting selectable user scopes based on the scopes selected for the client, ensuring
  correct delegation semantics directly in the UI.
- Established a clearer boundary between domain logic and UI concerns by moving scope grouping and rendering
  responsibilities into purpose-built components rather than form builders.
- Added a generic `CacheFlasher` support utility to safely transfer short-lived, one-time values across redirect and
  lifecycle boundaries without relying on sessions or persistent storage, providing a deterministic and
  security-conscious alternative for ephemeral state handoff.
- User scopes are always enforced as a strict subset of client scopes; users cannot grant permissions the client itself
  doesn't hold.

### Changed

- Reworked the client creation form from a single, flat form schema to a multi-step wizard-based flow.
- Replaced the former unified `ClientResourceForm` with a dedicated `CreateClientForm` tailored specifically for client
  creation.
- Introduced a step-based separation between **client configuration** and **user permission assignment**, improving
  clarity and guiding users through the authorization setup process.
- Changed the client creation UX to conditionally include a dedicated **user permission step** based on the selected
  grant type.
- Refactored scope assignment logic to explicitly distinguish between **client scopes** and **user scopes**, instead of
  handling scopes implicitly within a single form.
- Enforced the **Principle of Least Privilege** by ensuring that user-level permissions are always restricted to a
  subset of the scopes granted to the client.
- Updated owner handling during client creation to treat the owner as contextual data, removing redundant owner
  configuration during the user permission step.
- Improved form structure and readability by grouping related fields using grids and wizard steps instead of a single
  linear schema.
- Owner (user) is required for all grant types except client_credentials.

### Deprecated

- Deprecated `ScopeFormSectionBuilder` in favor of the new component-based scope configuration approach.
- Deprecated form-builder-based scope rendering in favor of explicit, reusable UI components to improve clarity,
  composability, and long-term maintainability.

## [2.0.0] - 2026-01-10

### Changed

- Refactored Filament Passport UI to delegate all authorization domain logic to the
  **[Laravel Passport Authorization Core](https://github.com/N3XT0R/laravel-passport-authorization-core)** package.
- Removed duplicated authorization logic from the UI layer in favor of shared,
  reusable use cases provided by the core package.
- Established a clear separation between administrative UI concerns and
  authorization domain responsibilities as part of the v2 architecture.

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
