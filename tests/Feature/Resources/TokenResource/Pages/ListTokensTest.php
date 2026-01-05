<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\TokenResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\TokenFactory;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Pages\ListTokens;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ListTokensTest extends DatabaseTestCase
{
    public function testListTokensPageLoadsWithTokens(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        TokenFactory::new()->count(2)->create();

        Livewire::test(ListTokens::class)
            ->assertSuccessful();
    }
}
