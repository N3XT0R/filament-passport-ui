<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Traits;

use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component as LivewireComponent;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\FilamentPassportUi\Traits\HasResourceFormComponents;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

final class HasResourceFormComponentsTest extends DatabaseTestCase
{
    public function testIsResourceFormComponentsEnabledReflectsConfig(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        self::assertTrue(TestResourceFormComponent::isResourceFormComponentsEnabled());

        config()->set('passport-authorization-core.use_database_scopes', false);

        self::assertFalse(TestResourceFormComponent::isResourceFormComponentsEnabled());
    }

    public function testGetResourceFormComponentsBuildsGridWithGrantedScopes(): void
    {
        $resource = PassportScopeResource::query()->create([
            'name' => 'projects',
            'description' => 'Project data',
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'read',
            'description' => 'Read projects',
            'resource_id' => $resource->getKey(),
            'is_active' => true,
        ]);

        $user = User::factory()->create();
        $grantService = app(GrantService::class);
        $grantService->grantScopeToTokenable($user, 'projects', 'read');

        $components = TestResourceFormComponent::getResourceFormComponents($user);

        $livewire = new TraitSchemaLivewireComponent();
        $schema = Schema::make($livewire)->components($components);
        $livewire->schema = $schema;

        self::assertCount(1, $schema->getComponents());

        $rootSection = $schema->getComponents()[0];
        self::assertInstanceOf(Section::class, $rootSection);
        self::assertSame(
            __('filament-passport-ui::passport-ui.common.scopes'),
            $rootSection->getHeading()
        );

        $grid = $rootSection->getChildComponents()[0];
        self::assertInstanceOf(Grid::class, $grid);

        $gridSections = $grid->getChildComponents();
        self::assertCount(1, $gridSections);

        $scopeSection = $gridSections[0];
        self::assertInstanceOf(Section::class, $scopeSection);
        self::assertSame('projects', $scopeSection->getHeading());

        $checkboxList = $scopeSection->getChildComponents()[0];
        self::assertInstanceOf(CheckboxList::class, $checkboxList);

        $checkboxList->callAfterStateHydrated();

        self::assertSame(['projects:read'], data_get($livewire, 'scopes.projects'));
    }
}

final class TestResourceFormComponent
{
    use HasResourceFormComponents;
}

final class TraitSchemaLivewireComponent extends LivewireComponent implements HasSchemas
{
    public ?Schema $schema = null;

    public array $scopes = [];

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
