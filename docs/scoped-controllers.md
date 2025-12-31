# Example

``` php 

use N3xt0r\PassportModernScopes\Attributes\RequiresAnyScope;
use N3xt0r\PassportModernScopes\Attributes\RequiresScope;

#[RequiresScope('users:read')]
final class UserController
{

    public function index()
    {
    //
    }

    #[RequiresAnyScope('users:update', 'users:write')]
    public function update()
    {
        //
    }
}
```


