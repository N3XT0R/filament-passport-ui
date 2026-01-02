<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionsResource;

class CreateAction extends CreateRecord
{
    protected static string $resource = PassportScopeActionsResource::class;
}
