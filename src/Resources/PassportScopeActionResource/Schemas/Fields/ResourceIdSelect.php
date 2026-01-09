<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas\Fields;

use Filament\Forms\Components\Select;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\FieldInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ResourceRepository;

class ResourceIdSelect implements FieldInterface
{

    public static function make(string $name = 'resource_id'): Select
    {
        return Select::make($name)
            ->label(
                __(
                    'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.resource_id'
                )
            )
            ->placeholder(__('filament-passport-ui::passport-ui.common.none'))
            ->options(app(ResourceRepository::class)->active()->pluck('name', 'id'))
            ->default(null)
            ->nullable()
            ->helperText(
                __(
                    'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.resource_id_helper_text'
                )
            );
    }
}
