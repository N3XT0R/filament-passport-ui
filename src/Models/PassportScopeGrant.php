<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PassportScopeGrant extends Model
{
    protected $table = 'passport_scope_grants';

    protected $fillable = [
        'resource_id',
        'action_id',
    ];

    /**
     * The Passport owner (User, ServiceAccount, etc.)
     * @return MorphTo
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The protected resource (e.g. videos)
     * @return BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(PassportScopeResource::class);
    }

    /**
     * The allowed action (e.g. read, write)
     * @return BelongsTo
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(PassportScopeAction::class);
    }

    /**
     * Normalized Passport scope string (videos.read)
     * @return string
     */
    public function toScopeString(): string
    {
        return "{$this->resource->name}.{$this->action->name}";
    }
}
