<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns;

use Carbon\CarbonInterface;
use Filament\Tables\Columns\TextColumn;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\ColumnInterface;

class LastLoginColumn implements ColumnInterface
{

    public static function make(string $name = 'last_login'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.client_resource.column.last_login'))
            ->dateTime()
            ->getStateUsing(function (Client $record): ?CarbonInterface {
                return app(ClientRepository::class)->getLastLoginAtForClient($record);
            });
    }
}
