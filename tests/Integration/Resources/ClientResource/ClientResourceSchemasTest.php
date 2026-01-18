<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources\ClientResource;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component as LivewireComponent;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\ClientResourceForm;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\ClientWizardForm;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ClientResourceSchemasTest extends DatabaseTestCase
{
    public function testClientWizardFormProvidesSingleStepWithoutDatabaseScopes(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $livewire = new ClientResourceSchemasLivewireComponent();
        $schema = ClientWizardForm::configure(Schema::make($livewire));
        $livewire->schema = $schema;

        $components = $schema->getComponents();

        self::assertCount(1, $components);
        self::assertInstanceOf(Wizard::class, $components[0]);

        $steps = $components[0]->getChildSchema()->getComponents(withHidden: true);

        self::assertCount(1, $steps);
    }

    public function testClientWizardFormAddsUserPermissionStepWhenDatabaseScopesEnabled(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $livewire = new ClientResourceSchemasLivewireComponent();
        $schema = ClientWizardForm::configure(Schema::make($livewire));
        $livewire->schema = $schema;

        $wizard = $schema->getComponents()[0];
        $steps = $wizard->getChildSchema()->getComponents(withHidden: true);

        self::assertCount(2, $steps);
    }

    public function testClientResourceFormIncludesBaseAndAdditionalComponents(): void
    {
        $livewire = new ClientResourceSchemasLivewireComponent();

        $schema = ClientResourceForm::configure(
            Schema::make($livewire),
            [TextInput::make('extra_field')]
        );
        $livewire->schema = $schema;

        $names = collect($schema->getComponents(withHidden: true))
            ->map(fn (Component $component) => $component->getName())
            ->all();

        self::assertSame(
            ['id', 'name', 'owner', 'grant_type', 'secret', 'revoked', 'extra_field'],
            $names
        );
    }
}

final class ClientResourceSchemasLivewireComponent extends LivewireComponent implements HasSchemas
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
