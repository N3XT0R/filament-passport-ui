<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Panel;
use Laravel\Passport\Token;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\TokenFactory;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
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

    public function testGetEloquentQueryScopesToCurrentUserWhenSelfServiceEnabled(): void
    {
        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();

        $ownToken = TokenFactory::new()->withUserId($owner->getKey())->create();
        TokenFactory::new()->withUserId($otherOwner->getKey())->create();

        $panel = Panel::make()->id('token-self-service-test');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $tokens = TokenResource::getEloquentQuery()->get();

        $this->assertCount(1, $tokens);
        $this->assertSame($ownToken->getKey(), $tokens->first()->getKey());
    }

    public function testGetEloquentQueryReturnsAllTokensWhenSelfServiceDisabled(): void
    {
        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();

        TokenFactory::new()->withUserId($owner->getKey())->create();
        TokenFactory::new()->withUserId($otherOwner->getKey())->create();

        $panel = Panel::make()->id('token-admin-mode-test');
        $panel->plugin(FilamentPassportUiPlugin::make());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $this->assertCount(2, TokenResource::getEloquentQuery()->get());
    }

    public function testNavigationBadgeScopesToOwnerCountWhenSelfServiceEnabled(): void
    {
        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();

        TokenFactory::new()->withUserId($owner->getKey())->create();
        TokenFactory::new()->withUserId($otherOwner->getKey())->create();
        TokenFactory::new()->withUserId($otherOwner->getKey())->create();

        $panel = Panel::make()->id('token-self-service-badge-test');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $this->assertSame('1', TokenResource::getNavigationBadge());
    }

    public function testNavigationBadgeReturnsGlobalNonExpiredCountWhenSelfServiceDisabled(): void
    {
        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();

        TokenFactory::new()->withUserId($owner->getKey())->create();
        TokenFactory::new()->withUserId($otherOwner->getKey())->create();

        $panel = Panel::make()->id('token-admin-badge-test');
        $panel->plugin(FilamentPassportUiPlugin::make());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $this->assertSame('2', TokenResource::getNavigationBadge());
    }
}
