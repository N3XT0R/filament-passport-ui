<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories;

use App\Models\Token;
use Carbon\Carbon;
use N3XT0R\FilamentPassportUi\Repositories\TokenRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class TokenRepositoryTest extends DatabaseTestCase
{
    protected TokenRepository $tokenRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenRepository = $this->app->make(TokenRepository::class);
    }

    public function testCountReturnsTotalNumberOfTokens(): void
    {
        Token::factory()->count(3)->create();

        self::assertSame(
            3,
            $this->tokenRepository->count()
        );
    }

    public function testNotExpiredCountReturnsOnlyValidTokens(): void
    {
        Token::factory()->create([
            'expires_at' => Carbon::now()->addDay(),
        ]);

        Token::factory()->create([
            'expires_at' => Carbon::now()->addHours(2),
        ]);

        Token::factory()->create([
            'expires_at' => Carbon::now()->subMinute(),
        ]);

        self::assertSame(
            2,
            $this->tokenRepository->notExpiredCount()
        );
    }

    public function testNotExpiredCountReturnsZeroWhenNoValidTokensExist(): void
    {
        Token::factory()->create([
            'expires_at' => Carbon::now()->subDay(),
        ]);

        self::assertSame(
            0,
            $this->tokenRepository->notExpiredCount()
        );
    }

    public function testCountReturnsZeroWhenNoTokensExist(): void
    {
        self::assertSame(
            0,
            $this->tokenRepository->count()
        );
    }
}
