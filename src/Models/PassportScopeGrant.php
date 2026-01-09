<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant as BaseModel;

/**
 * Scope grant assigned to a Passport owner (User, ServiceAccount, etc.)
 */
class PassportScopeGrant extends BaseModel
{
    use HasFactory;
}
