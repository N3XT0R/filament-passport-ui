<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\EditClientUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\UpsertGrantsForTokenableUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof Client) {
            throw new \RuntimeException('Record is not an instance of Client model.');
        }

        $actor = Filament::auth()->user();
        $isSelfService = FilamentPassportUiPlugin::get()->isSelfService();

        if ($isSelfService) {
            $data['owner'] = $actor?->getKey();
        }

        $userScopes = [];

        if (isset($data['client_scopes']) && is_array($data['client_scopes'])) {
            $data['scopes'] = $this->flattenScopes($data['client_scopes']);
            unset($data['client_scopes']);
        }

        if (isset($data['user_scopes']) && is_array($data['user_scopes'])) {
            $userScopes = $this->flattenScopes($data['user_scopes']);
            unset($data['user_scopes']);
        }

        $hasSubmittedScopes = (isset($data['scopes']) && is_array($data['scopes']) && $data['scopes'] !== [])
            || $userScopes !== [];

        if ($isSelfService && $hasSubmittedScopes) {
            $grantedScopes = $this->resolveActorGrantedScopes($actor);

            if (isset($data['scopes']) && is_array($data['scopes'])) {
                $data['scopes'] = array_values(array_intersect($data['scopes'], $grantedScopes));
            }

            $userScopes = array_values(array_intersect($userScopes, $grantedScopes));
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

    /**
     * Resolve the scopes the acting user is themselves granted, to be
     * used as an allow-list boundary for self-service scope submissions.
     * This is the actual security boundary: a UI bug or a tampered
     * request must never result in a scope grant beyond what the acting
     * user already has.
     * @param mixed $actor
     * @return array<int, string>
     */
    private function resolveActorGrantedScopes(mixed $actor): array
    {
        if (!$actor instanceof HasPassportScopeGrantsInterface) {
            return [];
        }

        return app(GrantService::class)->getTokenableGrantsAsScopes($actor)->all();
    }
}
