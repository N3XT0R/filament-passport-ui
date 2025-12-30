<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Traits;

use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

trait HasResourceFormComponents
{
    public static function getResourceFormComponentsStatic(): array
    {
        return new static()->getResourceFormComponents();
    }

    public function getResourceFormComponents(): array
    {
        return [
            Section::make('client_resources')
                ->schema($this->getCheckBoxListForResourceFormSchema())
                ->collapsible(),
        ];
    }

    public function getCheckBoxListForResourceFormSchema(): array
    {
        return [
            CheckboxList::make('scopes')
                ->label(__('filament-passport-ui:filament-passport-ui.common.scopes'))
                ->options(function (): Collection {
                    return app(ScopeRegistryService::class)->all();
                })
                ->columns(3)
                ->required(),
        ];
    }
}
