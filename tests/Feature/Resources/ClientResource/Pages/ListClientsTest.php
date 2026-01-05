<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Filament\Actions\CreateAction;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ListClients;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ListClientsTest extends DatabaseTestCase
{
    public function testListClientsPageShowsCreateAction(): void
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

        $component = Livewire::test(ListClients::class);

        $headerActions = $component->instance()->getHeaderActions();

        $this->assertCount(1, $headerActions);
        $this->assertInstanceOf(CreateAction::class, $headerActions[0]);
    }
}
