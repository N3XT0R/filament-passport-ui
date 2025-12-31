<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PassportScopeResource extends Model
{
    use HasFactory;

    protected $table = 'passport_scope_resources';

    protected $fillable = [
        'name',
        'description',
        'group',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(PassportScopeAction::class, 'passport_scope_resource_id');
    }
}
