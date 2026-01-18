<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\EditClientUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\UpsertGrantsForTokenableUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof Client) {
            throw new \RuntimeException('Record is not an instance of Client model.');
        }

        $actor = Filament::auth()->user();
        $userScopes = [];

        if (isset($data['client_scopes']) && is_array($data['client_scopes'])) {
            $data['scopes'] = $this->flattenScopes($data['client_scopes']);
            unset($data['client_scopes']);
        }

        if (isset($data['user_scopes']) && is_array($data['user_scopes'])) {
            $userScopes = $this->flattenScopes($data['user_scopes']);
            unset($data['user_scopes']);
        }


        $result = app(EditClientUseCase::class)->execute(
            client: $record,
            data: $data,
            actor: $actor,
        );

        if (!empty($data['owner'] ?? null) && !empty($userScopes)) {
            app(UpsertGrantsForTokenableUseCase::class)->execute(
                ownerId: $data['owner'],
                contextClientId: $record->getKey(),
                scopes: $userScopes,
                actor: $actor,
            );
        }


        return $result;
    }

    private function flattenScopes(array $scopes): array
    {
        return collect($scopes)
            ->flatten()
            ->unique()
            ->values()
            ->all();
    }
}
