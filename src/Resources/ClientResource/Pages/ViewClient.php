<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Session;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $key = 'new_client_secret_' . $this->record->getKey();

        if ($secret = Session::get($key)) {
            $data['secret'] = $secret;
            Session::forget($key);
        }
        return $data;
    }

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }
}
