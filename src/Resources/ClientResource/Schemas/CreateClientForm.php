<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\GrantType\NeedsUserPermissionState;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\FormInterface;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\GrantTypeSelect;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\NameInput;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\OwnerSelect;

class CreateClientForm implements FormInterface
{
    public static function configure(Schema $schema, array $additionalComponents = []): Schema
    {
        $components = [
            Wizard::make([
                Wizard\Step::make('client')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.form.wizard.steps.client.label'))
                    ->icon(Heroicon::OutlinedKey)
                    ->description(
                        __('filament-passport-ui::passport-ui.client_resource.form.wizard.steps.client.description')
                    )
                    ->schema([
                        GrantTypeSelect::make('grant_type')
                            ->live(),
                        Grid::make()
                            ->schema([
                                NameInput::make(),
                                OwnerSelect::make()
                                    ->required(function (Get $get): bool {
                                        $grantType = $get('grant_type');

                                        if ($grantType === null) {
                                            return false;
                                        }

                                        return app(NeedsUserPermissionState::class)
                                            ->execute($grantType);
                                    }),
                            ]),
                    ]),
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
                    ->schema([

                    ]),
            ])->persistStepInQueryString()
                ->columnSpanFull(),
        ];

        return $schema->components(
            array_merge(
                $components,
                $additionalComponents
            )
        );
    }
}