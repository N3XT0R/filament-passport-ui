<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Passport\Token;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;

class TokenFactory extends Factory
{
    protected $model = Token::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'user_id' => null,
            'client_id' => Client::factory(),
            'name' => $this->faker->word,
            'scopes' => [],
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addDays(30),
        ];
    }

    public function withClient(?Client $client = null): self
    {
        return $this->state([
            'client_id' => $client?->getKey() ?? Client::factory(),
        ]);
    }

    public function withUserId(?int $userId = null): self
    {
        return $this->state([
            'user_id' => $userId,
        ]);
    }
}