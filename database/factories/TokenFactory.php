<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Passport\Token;

class TokenFactory extends Factory
{
    protected $model = Token::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'client_id' => null,
            'name' => $this->faker->word,
            'scopes' => [],
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addDays(30),
        ];
    }
}