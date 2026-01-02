# Scoped Controllers

This Package includes the `n3xt0r/passport-modern-scopes` package, which provides attributes to enforce scope
requirements on controller methods.

Here is an Example Implementation of its Usage:

``` php 

use N3XT0R\PassportModernScopes\Support\Attributes\RequiresScope;
use N3XT0R\PassportModernScopes\Support\Attributes\RequiresAnyScope;

#[RequiresScope('users:read')]
final class UserController
{
    public function index()
    {
        // Requires users:read
    }

    #[RequiresAnyScope('users:update', 'users:write')]
    public function update()
    {
        // Requires at least one of the given scopes
    }
}
```

For more information, visit
the [n3xt0r/passport-modern-scopes](https://github.com/N3XT0R/laravel-passport-modern-scopes).
