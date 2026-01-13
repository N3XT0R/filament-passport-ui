<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Support\Builder;

use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Components\Component;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component as LivewireComponent;
use N3XT0R\FilamentPassportUi\Support\Builder\ScopeFormSectionBuilder;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;
use N3XT0R\LaravelPassportAuthorizationCore\Services\Scopes\ScopeRegistryService;

final class ScopeFormSectionBuilderTest extends DatabaseTestCase
{
    public function testBuildSectionsReturnsEmptyWhenNoScopes(): void
    {
        $builder = new ScopeFormSectionBuilder(
            app(ScopeRegistryService::class),
            app(GrantService::class),
        );

        $sections = $builder->buildSections();

        self::assertSame([], $sections);
    }

    public function testBuildSectionsCreatesCheckboxListsWithDescriptionsAndDefaults(): void
    {
        PassportScopeResource::query()->create([
            'name' => 'projects',
            'description' => 'Project data',
            'is_active' => true,
        ]);

        PassportScopeResource::query()->create([
            'name' => 'clients',
            'description' => 'Client data',
            'is_active' => true,
        ]);

        $projectResource = PassportScopeResource::query()->where('name', 'projects')->firstOrFail();
        $clientResource = PassportScopeResource::query()->where('name', 'clients')->firstOrFail();

        PassportScopeAction::query()->create([
            'name' => 'read',
            'description' => 'Read projects',
            'resource_id' => $projectResource->getKey(),
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'write',
            'description' => null,
            'resource_id' => $projectResource->getKey(),
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'manage',
            'description' => 'Manage clients',
            'resource_id' => $clientResource->getKey(),
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'disabled',
            'description' => 'Disabled action',
            'resource_id' => $projectResource->getKey(),
            'is_active' => false,
        ]);

        $builder = new ScopeFormSectionBuilder(
            app(ScopeRegistryService::class),
            app(GrantService::class),
        );

        $sections = $builder->buildSections();

        self::assertCount(2, $sections);

        $livewire = new TestSchemaLivewireComponent();
        $schema = Schema::make($livewire)->components($sections);
        $livewire->schema = $schema;

        $sectionsByHeading = collect($schema->getComponents())
            ->keyBy(fn(Section $section) => $section->getHeading());

        $projectSection = $sectionsByHeading->get('projects');
        self::assertInstanceOf(Section::class, $projectSection);
        self::assertTrue($projectSection->isCollapsible());

        $projectCheckboxList = $projectSection->getChildComponents()[0];
        self::assertInstanceOf(CheckboxList::class, $projectCheckboxList);

        self::assertSame(
            [
                'projects:read' => 'projects:read',
                'projects:write' => 'projects:write',
            ],
            $projectCheckboxList->getOptions()
        );

        self::assertSame(
            ['projects:read' => 'Read projects'],
            $projectCheckboxList->getDescriptions()
        );

        self::assertSame([], $projectCheckboxList->getDefaultState());

        $projectCheckboxList->callAfterStateHydrated();

        self::assertSame([], data_get($livewire, 'scopes.projects'));
    }

    public function testBuildSectionsHydratesGrantedScopesForRecord(): void
    {
        $projectResource = PassportScopeResource::query()->create([
            'name' => 'projects',
            'description' => 'Project data',
            'is_active' => true,
        ]);

        $clientResource = PassportScopeResource::query()->create([
            'name' => 'clients',
            'description' => 'Client data',
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'read',
            'description' => 'Read projects',
            'resource_id' => $projectResource->getKey(),
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'write',
            'description' => 'Write projects',
            'resource_id' => $projectResource->getKey(),
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'manage',
            'description' => 'Manage clients',
            'resource_id' => $clientResource->getKey(),
            'is_active' => true,
        ]);

        $user = User::factory()->create();
        $grantService = app(GrantService::class);

        $grantService->grantScopeToTokenable($user, 'projects', 'read');
        $grantService->grantScopeToTokenable($user, 'clients', 'manage');

        $builder = new ScopeFormSectionBuilder(
            app(ScopeRegistryService::class),
            $grantService,
        );

        $sections = $builder->buildSections($user);

        $livewire = new TestSchemaLivewireComponent();
        $schema = Schema::make($livewire)->components($sections);
        $livewire->schema = $schema;

        $sectionsByHeading = collect($schema->getComponents())
            ->keyBy(fn(Section $section) => $section->getHeading());

        $projectSection = $sectionsByHeading->get('projects');
        self::assertInstanceOf(Section::class, $projectSection);

        $projectCheckboxList = $projectSection->getChildComponents()[0];
        self::assertInstanceOf(CheckboxList::class, $projectCheckboxList);

        $projectCheckboxList->callAfterStateHydrated();

        self::assertSame(['projects:read'], data_get($livewire, 'scopes.projects'));
    }

    public function testBuildSectionsUsesOwnerScopesWhenPresent(): void
    {
        $projectResource = PassportScopeResource::query()->create([
            'name' => 'projects',
            'description' => 'Project data',
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'read',
            'description' => 'Read projects',
            'resource_id' => $projectResource->getKey(),
            'is_active' => true,
        ]);

        PassportScopeAction::query()->create([
            'name' => 'write',
            'description' => 'Write projects',
            'resource_id' => $projectResource->getKey(),
            'is_active' => true,
        ]);

        $owner = User::factory()->create();
        $record = User::factory()->create();

        $record->setAttribute('owner', $owner);

        $grantService = app(GrantService::class);

        $grantService->grantScopeToTokenable($record, 'projects', 'read');
        $grantService->grantScopeToTokenable($owner, 'projects', 'write');

        $builder = new ScopeFormSectionBuilder(
            app(ScopeRegistryService::class),
            $grantService,
        );

        $sections = $builder->buildSections($record);

        $livewire = new TestSchemaLivewireComponent();
        $schema = Schema::make($livewire)->components($sections);
        $livewire->schema = $schema;

        $sectionsByHeading = collect($schema->getComponents())
            ->keyBy(fn(Section $section) => $section->getHeading());

        $projectSection = $sectionsByHeading->get('projects');
        self::assertInstanceOf(Section::class, $projectSection);

        $projectCheckboxList = $projectSection->getChildComponents()[0];
        self::assertInstanceOf(CheckboxList::class, $projectCheckboxList);

        $projectCheckboxList->callAfterStateHydrated();

        self::assertSame(['projects:write'], data_get($livewire, 'scopes.projects'));
    }
}

final class TestSchemaLivewireComponent extends LivewireComponent implements HasSchemas
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
