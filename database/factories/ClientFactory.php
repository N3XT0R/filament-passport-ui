<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => $this->faker->company,
            'secret' => $this->faker->sha256,
            'redirect_uris' => [$this->faker->url],
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}