<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\Traits\HasApiTokensTrait;
use N3XT0R\FilamentPassportUi\Models\Traits\HasPassportScopeGrantsTrait;

#[UseFactory(\Database\Factories\HasApiTokensUserFactory::class)]
class HasApiTokensUser extends User implements HasPassportScopeGrantsInterface
{
    use HasApiTokensTrait;
    use HasPassportScopeGrantsTrait;

    protected $table = 'users';
}
