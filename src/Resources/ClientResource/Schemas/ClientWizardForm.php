<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas;

use Filament\Facades\Filament;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\GrantType\NeedsUserPermissionState;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Token\GetOwnerState;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Components\ScopeCheckboxList;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\FormInterface;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\GrantTypeSelect;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\NameInput;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\OwnerSelect;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

class ClientWizardForm implements FormInterface
{
    public function __construct(protected ConfigRepository $configRepository)
    {
    }

    public static function getInstance(): static
    {
        return app(static::class);
    }


    public static function configure(
        Schema $schema,
        ?Client $client = null
    ): Schema {
        return static::getInstance()->configureComponents($schema);
    }

    public function configureComponents(Schema $schema): Schema
    {
        return $schema->components($this->getComponents());
    }

    public function getComponents(
        ?Client $client = null
    ): array {
        $dbScopesRequired = $this->configRepository->isUsingDatabaseScopes();
        $steps = [
            Wizard\Step::make('client')
                ->label(__('filament-passport-ui::passport-ui.client_resource.form.wizard.steps.client.label'))
                ->icon(Heroicon::OutlinedKey)
                ->description(
                    __('filament-passport-ui::passport-ui.client_resource.form.wizard.steps.client.description')
                )
                ->schema($this->getClientComponents($client))
        ];

        if ($dbScopesRequired) {
            $steps[] = Wizard\Step::make('user_permission')
                ->visible(fn(Get $get) => app(NeedsUserPermissionState::class)->execute($get('grant_type')))
                ->label(
                    __('filament-passport-ui::passport-ui.client_resource.form.wizard.steps.user_permission.label')
                )
                ->icon(Heroicon::OutlinedUser)
                ->description(
                    __(
                        'filament-passport-ui::passport-ui.client_resource.form.wizard.steps.user_permission.description'
                    )
                )
                ->schema($this->getUserPermissionComponents($client));
        }


        return [
            Wizard::make()
                ->steps($steps)
                ->columnSpanFull()
                ->persistStepInQueryString(),
        ];
    }

    public function getClientComponents(?Client $client = null): array
    {
        $dbScopesRequired = $this->configRepository->isUsingDatabaseScopes();
        $components = [
            NameInput::make()
                ->unique('oauth_clients', 'name'),
            OwnerSelect::make()
                ->disabled(fn(): bool => FilamentPassportUiPlugin::get()->isSelfService())
                ->default(
                    fn(): int|string|null => FilamentPassportUiPlugin::get()->isSelfService()
                        ? Filament::auth()->id()
                        : null
                )
                ->required(function (Get $get): bool {
                    $grantType = $get('grant_type');

                    if ($grantType === null) {
                        return false;
                    }

                    return app(NeedsUserPermissionState::class)
                        ->execute($grantType);
                }),
        ];

        if ($dbScopesRequired) {
            $components[] = Grid::make()
                ->schema(fn(Get $get) => [
                    ScopeCheckboxList::make(
                        context: 'client',
                        name: 'client_scopes',
                        record: $this->resolveClient($client, $get),
                        statePath: 'client_scopes',
                        contextClient: $this->resolveClient($client, $get),
                        allowed: $this->resolveClientScopesAllowedForActor(),
                    )
                ])
                ->key('client_scopes')
                ->columnSpanFull();
        }


        return [
            GrantTypeSelect::make('grant_type')
                ->live(),
            Grid::make()
                ->schema($components),
        ];
    }

    /**
     * In self-service mode, the client-step scope checkbox list must only
     * offer scopes the acting user already holds themselves, so a
     * self-service user cannot declare a client capability beyond their
     * own grants. In admin (non-self-service) mode, no restriction is
     * applied and admins can define a client's declared capability freely.
     * @return Collection<int, string>|null
     */
    private function resolveClientScopesAllowedForActor(): ?Collection
    {
        if (!FilamentPassportUiPlugin::get()->isSelfService()) {
            return null;
        }

        $actingUser = Filament::auth()->user();

        if (!$actingUser instanceof HasPassportScopeGrantsInterface) {
            return collect();
        }

        return app(GrantService::class)->getTokenableGrantsAsScopes($actingUser);
    }

    /**
     * The user-step scope checkbox list is meant to be restricted to the
     * scopes chosen on the preceding client step. When no client scopes
     * have been selected yet (or the field is simply empty), there is no
     * restriction basis, so this returns null ("no restriction") rather
     * than an empty collection ("restrict to nothing") — the two carry
     * different meaning throughout the ScopeCheckboxList call chain.
     * @return Collection<int, string>|null
     */
    private function resolveUserScopesAllowedByClientStep(Get $get): ?Collection
    {
        $clientScopes = collect($get('client_scopes') ?? [])
            ->flatten()
            ->filter()
            ->values();

        return $clientScopes->isEmpty() ? null : $clientScopes;
    }

    private function getUserPermissionComponents(?Client $client = null): array
    {
        return [
            OwnerSelect::make()
                ->live()
                ->default(function (Set $set, Get $get) {
                    $set('owner_select', $get('owner'));
                })
                ->disabled()
                ->dehydrated(false),
            Grid::make()
                ->live()
                ->schema(fn(Get $get) => [
                    ScopeCheckboxList::make(
                        context: 'user',
                        name: 'user_scopes',
                        record: $this->resolveOwner($client, $get),
                        statePath: 'user_scopes',
                        contextClient: $this->resolveClient($client, $get),
                        allowed: $this->resolveUserScopesAllowedByClientStep($get),
                    ),
                ])
                ->key('user_scopes')
                ->columnSpanFull()
        ];
    }

    private function resolveOwner(
        ?Client $client,
        Get $get
    ): ?Model {
        if ($ownerId = $get('owner')) {
            return app(GetOwnerState::class)->execute($ownerId);
        }

        if ($ownerId = $get('owner_select')) {
            return app(GetOwnerState::class)->execute($ownerId);
        }

        if ($userId = $client?->getAttribute('owner_id')) {
            return app(GetOwnerState::class)->execute($userId);
        }

        return null;
    }

    private function resolveClient(
        ?Client $client,
        Get $get
    ): ?Client {
        if ($id = $get('id')) {
            return Client::find($id);
        }

        return $client;
    }
}