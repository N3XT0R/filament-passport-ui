<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassportScopeAction extends Model
{
    use HasFactory;

    protected $table = 'passport_scope_actions';

    protected $fillable = [
        'name',
        'description',
        'resource_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(PassportScopeResource::class, 'resource_id');
    }
}
