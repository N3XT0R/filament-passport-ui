<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\Traits\HasPassportScopeGrantsTrait;

#[UseFactory(\Database\Factories\PassportScopeGrantUserFactory::class)]
class PassportScopeGrantUser extends User implements HasPassportScopeGrantsInterface
{
    protected $table = 'users';
    use HasPassportScopeGrantsTrait;
}
