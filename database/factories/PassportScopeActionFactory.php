<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;

class PassportScopeActionFactory extends Factory
{
    protected $model = PassportScopeAction::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->optional()->sentence(),
            'resource_id' => null,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state([
            'is_active' => false,
        ]);
    }

    public function withResource(?PassportScopeResource $resource = null): static
    {
        return $this->state(function (array $attributes) use ($resource) {
            return [
                'resource_id' => $resource ? $resource->getKey() : PassportScopeResource::factory(),
            ];
        });
    }
}
