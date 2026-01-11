<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\GrantType\NeedsUserPermissionState;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Components\ScopeCheckboxList;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\FormInterface;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\GrantTypeSelect;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\NameInput;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\OwnerSelect;

class CreateClientForm implements FormInterface
{
    public function __construct()
    {
    }


    public static function configure(Schema $schema): Schema
    {
        return app(static::class)->configureComponents($schema);
    }

    public function configureComponents(Schema $schema): Schema
    {
        return $schema->components($this->getComponents());
    }

    public function getComponents(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('client')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.form.wizard.steps.client.label'))
                    ->icon(Heroicon::OutlinedKey)
                    ->description(
                        __('filament-passport-ui::passport-ui.client_resource.form.wizard.steps.client.description')
                    )
                    ->schema($this->getClientComponents()),
                Wizard\Step::make('user_permission')
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
                    ->schema($this->getUserPermissionComponents()),
            ])->persistStepInQueryString()
                ->columnSpanFull(),
        ];
    }

    public function getClientComponents(): array
    {
        return [
            GrantTypeSelect::make('grant_type')
                ->live(),
            Grid::make()
                ->schema([
                    NameInput::make()
                        ->unique('oauth_clients', 'name'),
                    OwnerSelect::make()
                        ->required(function (Get $get): bool {
                            $grantType = $get('grant_type');

                            if ($grantType === null) {
                                return false;
                            }

                            return app(NeedsUserPermissionState::class)
                                ->execute($grantType);
                        }),
                    Grid::make()
                        ->schema([
                            ScopeCheckboxList::make(
                                context: 'client',
                                name: 'client_scopes',
                                statePath: 'client_scopes',
                            )
                        ])
                        ->columnSpanFull()
                ]),
        ];
    }

    public function getUserPermissionComponents(): array
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
                        statePath: 'user_scopes',
                        allowed: collect($get('client_scopes') ?? [])
                            ->flatten()
                            ->filter()
                            ->values()
                    ),
                ])
                ->columnSpanFull()
        ];
    }
}