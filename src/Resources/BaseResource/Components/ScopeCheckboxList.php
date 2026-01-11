<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Components;

use Filament\Schemas\Components\Section;
use N3XT0R\FilamentPassportUi\Support\Builder\ScopeFormSectionBuilder;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;

class ScopeCheckboxList
{
    public static function make(string $name = 'scopes', ?HasPassportScopeGrantsInterface $record = null): Section
    {
        return Section::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.scopes'))
            ->schema(app(ScopeFormSectionBuilder::class)->buildSections($record))
            ->columnSpanFull()
            ->collapsible();
    }

}