# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
