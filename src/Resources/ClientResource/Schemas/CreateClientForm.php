<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
                        Grid::make()
                            ->schema([
                                NameInput::make(),
                                OwnerSelect::make(),
                            ]),
                        GrantTypeSelect::make(),
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