<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources\ClientResource;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component as LivewireComponent;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ListClients;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\RelationManagers\ClientTokensRelationManager;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use Laravel\Passport\Passport;

final class ClientResourceTest extends DatabaseTestCase
{
    public function testGetModelLabelReturnsTranslation(): void
    {
        self::assertSame(
            __('filament-passport-ui::passport-ui.client_resource.model_label'),
            ClientResource::getModelLabel()
        );
    }

    public function testGetPluralLabelReturnsTranslation(): void
    {
        self::assertSame(
            __('filament-passport-ui::passport-ui.client_resource.plural_model_label'),
            ClientResource::getPluralLabel()
        );
    }

    public function testFormReturnsWizardSchema(): void
    {
        $livewire = new ClientResourceSchemaLivewireComponent();
        $schema = ClientResource::form(Schema::make($livewire));
        $livewire->schema = $schema;

        $components = $schema->getComponents();

        self::assertCount(1, $components);
        self::assertInstanceOf(Wizard::class, $components[0]);
    }

    public function testTableColumnsAreConfigured(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(ListClients::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('owner.name')
            ->assertTableColumnExists('grant_types')
            ->assertTableColumnExists('last_login')
            ->assertTableColumnExists('revoked')
            ->assertTableColumnExists('created_at')
            ->assertTableColumnExists('updated_at');
    }

    public function testGetModelReturnsPassportClientModel(): void
    {
        self::assertSame(Passport::clientModel(), ClientResource::getModel());
    }

    public function testGetRelationsReturnsClientTokensRelationManager(): void
    {
        self::assertSame(
            [ClientTokensRelationManager::class],
            ClientResource::getRelations()
        );
    }

    public function testGetPagesReturnsClientResourcePages(): void
    {
        $pages = ClientResource::getPages();

        self::assertArrayHasKey('index', $pages);
        self::assertArrayHasKey('edit', $pages);
        self::assertArrayHasKey('create', $pages);
        self::assertArrayHasKey('view', $pages);
    }
}

final class ClientResourceSchemaLivewireComponent extends LivewireComponent implements HasSchemas
{
    public ?Schema $schema = null;

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }

    public function getOldSchemaState(string $statePath): mixed
    {
        return null;
    }

    public function getSchemaComponent(
        string $key,
        bool $withHidden = false,
        array $skipComponentsChildContainersWhileSearching = []
    ): Component | Action | ActionGroup | null {
        return $this->schema?->getComponent(
            $key,
            withHidden: $withHidden,
            skipComponentsChildContainersWhileSearching: $skipComponentsChildContainersWhileSearching
        );
    }

    public function getSchema(string $name): ?Schema
    {
        return $name === 'test' ? $this->schema : null;
    }

    public function currentlyValidatingSchema(?Schema $schema): void
    {
    }

    public function getDefaultTestingSchemaName(): ?string
    {
        return 'test';
    }

    public function render(): string
    {
        return '';
    }
}
