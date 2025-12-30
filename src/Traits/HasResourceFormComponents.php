<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use N3XT0R\FilamentPassportUi\Support\Builder\ScopeFormSectionBuilder;

trait HasResourceFormComponents
{
    public static function getResourceFormComponentsStatic(): array
    {
        return new static()->getResourceFormComponents();
    }

    public function getResourceFormComponents(): array
    {
        return [
            Section::make(__('filament-passport-ui:filament-passport-ui.common.scopes'))
                ->schema([
                    Grid::make()
                        ->schema(
                            app(ScopeFormSectionBuilder::class)->buildSections()
                        ),
                ])
                ->collapsible(),
        ];
    }
}
