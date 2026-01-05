<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\Token;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\TokenResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class TokenResourceTest extends DatabaseTestCase
{
    public function testNavigationBadgeCountsNotExpiredTokens(): void
    {
        Passport::useTokenModel(Token::class);

        $client = Client::factory()->create();

        Token::factory()->withClient($client)->create([
            'expires_at' => now()->addDay(),
        ]);
        Token::factory()->withClient($client)->create([
            'expires_at' => now()->subDay(),
        ]);
        Token::factory()->withClient($client)->create([
            'expires_at' => now()->addDays(2),
        ]);

        $this->assertSame('2', TokenResource::getNavigationBadge());
    }

    public function testModelReturnsPassportTokenModel(): void
    {
        $this->assertSame(Passport::tokenModel(), TokenResource::getModel());
    }
}
