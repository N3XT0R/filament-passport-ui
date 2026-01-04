<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;

class PassportScopeGrantFactory extends Factory
{
    protected $model = PassportScopeGrant::class;

    public function definition(): array
    {
        return [
            'resource_id' => PassportScopeResource::factory(),
            'action_id' => PassportScopeAction::factory(),
        ];
    }

    public function withTokenable(Model $model): static
    {
        return $this->state(function (array $attributes) use ($model) {
            return [
                'tokenable_type' => get_class($model),
                'tokenable_id' => $model->getKey(),
            ];
        });
    }
}