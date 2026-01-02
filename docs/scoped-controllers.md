# Example

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


