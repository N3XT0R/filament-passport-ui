<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\User;
use Laravel\Passport\Token;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\TokenFactory;
use N3XT0R\FilamentPassportUi\Resources\TokenResource;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Pages\ListTokens;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class TokenResourceTest extends DatabaseTestCase
{
    public function testNavigationBadgeCountsOnlyNonExpiredTokens(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        TokenFactory::new()->count(2)->create();
        TokenFactory::new()->state(['expires_at' => now()->subDay()])->create();

        Livewire::test(ListTokens::class);

        $this->assertSame('2', TokenResource::getNavigationBadge());
    }

    public function testGetModelReturnsPassportTokenModel(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(ListTokens::class);

        $this->assertSame(Token::class, TokenResource::getModel());
    }
}
