<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ListClients;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;

class ClientResourceTest extends DatabaseTestCase
{
    public function testNavigationBadgeReturnsClientCount(): void
    {
        $this->markTestSkipped('Livewire error bag initialization issue under PHP 8.4.');

        config()->set('passport-ui.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

        session()->start();
        $errorBag = new ViewErrorBag();
        $errorBag->put('default', new MessageBag());
        session()->put('errors', $errorBag);
        session()->flash('errors', $errorBag);
        $_SESSION['errors'] = $errorBag;
        session()->save();

        ClientFactory::new()->count(2)->create();

        Livewire::test(ListClients::class);

        $this->assertSame('2', ClientResource::getNavigationBadge());
    }
}
