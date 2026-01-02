# Using database backend Scopes

To manage scopes via the Filament UI, you need to implement `HasPassportScopeGrantsInterface`
and use `HasPassportScopeGrantsTrait` and `HasApiTokensTrait` on your User model:

```php
use N3XT0R\FilamentPassportUI\Contracts\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUI\Traits\HasPassportScopeGrantsTrait;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\FilamentPassportUi\Models\Traits\HasApiTokensTrait;

class User extends Authenticatable implements OAuthenticatable, HasPassportScopeGrantsInterface
{;
    use HasApiTokensTrait;
    use HasPassportScopeGrantsTrait;

    // ...
}
```

This setup allows you to manage OAuth2 scopes directly from the Filament admin panel, providing a user-friendly
interface for scope management.

Change Configuration file to use Database Backed Scopes:

```php
return [
    // ...
    'use_database_scopes' => true,
    // ...
];
```
