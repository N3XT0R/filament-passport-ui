<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources\ClientResource;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as LivewireComponent;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\GrantTypeSelect;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\IdHidden;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\NameInput;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\OwnerSelect;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\RevokeToggle;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\SecretInput;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class ClientResourceFieldsTest extends DatabaseTestCase
{
    public function testIdHiddenUsesUniqueRule(): void
    {
        $livewire = new ClientResourceFieldsLivewireComponent();
        $hidden = IdHidden::make();

        $schema = Schema::make($livewire)->components([$hidden]);
        $livewire->schema = $schema;
        $schema->getComponents(withHidden: true);

        $rules = $hidden->getValidationRules();

        $uniqueRule = collect($rules)->first(fn ($rule) => $rule instanceof Unique);

        self::assertNotNull($uniqueRule);
        self::assertStringContainsString('unique:oauth_clients,id', (string) $uniqueRule);
    }

    public function testNameInputIsRequiredAndLimited(): void
    {
        $input = NameInput::make();

        self::assertTrue($input->isRequired());
        self::assertSame(255, $input->getMaxLength());
    }

    public function testOwnerSelectShowsOptionsAndFormatsOwnerState(): void
    {
        $user = User::factory()->create(['name' => 'Ada Lovelace']);

        /** @var Client $client */
        $client = ClientFactory::new()->create([
            'owner_id' => $user->getKey(),
            'owner_type' => User::class,
        ]);

        $livewire = new ClientResourceFieldsLivewireComponent();
        $select = OwnerSelect::make();

        $schema = Schema::make($livewire)
            ->model($client)
            ->components([$select]);
        $livewire->schema = $schema;
        $schema->getComponents(withHidden: true);

        $options = $select->getOptions();

        self::assertSame(
            __('filament-passport-ui::passport-ui.client_resource.column.owner'),
            $select->getLabel()
        );
        self::assertSame(
            __('filament-passport-ui::passport-ui.common.none'),
            $select->getPlaceholder()
        );
        self::assertArrayHasKey($user->getKey(), $options);
        self::assertSame('Ada Lovelace', $options[$user->getKey()]);

        $select->state('ignored');
        $select->callAfterStateHydrated();

        self::assertSame($user->getKey(), $select->getState());
    }

    public function testGrantTypeSelectIsRequiredAndFormatsRecordState(): void
    {
        $livewire = new ClientResourceFieldsLivewireComponent();
        $select = GrantTypeSelect::make();

        $schema = Schema::make($livewire)->components([$select]);
        $livewire->schema = $schema;
        $schema->getComponents(withHidden: true);

        $options = $select->getOptions();

        self::assertTrue($select->isRequired());
        self::assertFalse($select->isDisabled());
        self::assertArrayHasKey('authorization_code', $options);

        /** @var Client $client */
        $client = ClientFactory::new()->create([
            'grant_types' => ['client_credentials', 'authorization_code'],
        ]);

        $selectWithRecord = GrantTypeSelect::make();
        $schemaWithRecord = Schema::make($livewire)
            ->model($client)
            ->components([$selectWithRecord]);
        $livewire->schema = $schemaWithRecord;
        $schemaWithRecord->getComponents(withHidden: true);

        $selectWithRecord->state('password');
        $selectWithRecord->callAfterStateHydrated();

        self::assertTrue($selectWithRecord->isDisabled());
        self::assertSame('client_credentials', $selectWithRecord->getState());
    }

    public function testSecretInputIsCopyableAndDisabled(): void
    {
        $livewire = new ClientResourceFieldsLivewireComponent();
        $input = SecretInput::make();

        $schema = Schema::make($livewire)->components([$input]);
        $livewire->schema = $schema;
        $schema->getComponents(withHidden: true);

        self::assertSame(
            __('filament-passport-ui::passport-ui.client_resource.form.secret_label'),
            $input->getLabel()
        );
        self::assertTrue($input->isCopyable());
        self::assertTrue($input->isDisabled());
    }

    public function testRevokeToggleIsVisibleForExistingRecord(): void
    {
        $livewire = new ClientResourceFieldsLivewireComponent();
        $toggle = RevokeToggle::make();

        $schema = Schema::make($livewire)->components([$toggle]);
        $livewire->schema = $schema;
        $schema->getComponents(withHidden: true);

        self::assertFalse($toggle->isVisible());

        /** @var Client $client */
        $client = ClientFactory::new()->create();

        $toggleWithRecord = RevokeToggle::make();
        $schemaWithRecord = Schema::make($livewire)
            ->model($client)
            ->components([$toggleWithRecord]);
        $livewire->schema = $schemaWithRecord;
        $schemaWithRecord->getComponents(withHidden: true);

        self::assertTrue($toggleWithRecord->isVisible());
    }
}

final class ClientResourceFieldsLivewireComponent extends LivewireComponent implements HasSchemas
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
