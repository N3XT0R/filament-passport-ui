<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

class ResourceResource extends BaseManagementResource
{
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
}
