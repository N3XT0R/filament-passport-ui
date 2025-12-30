<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;

class PassportScopeResourceFactory extends Factory
{
    protected $model = PassportScopeResource::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->slug(1),
            'description' => $this->faker->optional()->sentence(),
            'group' => $this->faker->optional()->word(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state([
            'is_active' => false,
        ]);
    }
}
