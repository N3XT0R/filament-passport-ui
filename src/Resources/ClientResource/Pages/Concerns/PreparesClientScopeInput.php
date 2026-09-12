<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Support\Scopes\SelfServiceScopes;

/**
 * Shared scope handling for the create and edit client pages.
 *
 * Both take the same wizard input (a client step and a user-permission step)
 * and have to normalise it the same way before handing it to their use case:
 * pin the owner in self-service mode, flatten the per-resource checkbox groups
 * into flat scope lists, and narrow both lists to what the acting user is
 * actually allowed to grant.
 *
 * The narrowing is the security boundary, not a UI convenience: a tampered
 * request must never produce a scope beyond the application's allow-list.
 */
trait PreparesClientScopeInput
{
    /**
     * @param  array<string, mixed>  $data  raw form state
     * @return array{0: array<string, mixed>, 1: list<string>} normalised data and the user scopes
     */
    protected function prepareScopeInput(array $data, ?Authenticatable $actor): array
    {
        if (FilamentPassportUiPlugin::get()->isSelfService()) {
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

        $allowed = SelfServiceScopes::allowedFor($actor)?->all();

        if ($allowed !== null) {
            if (isset($data['scopes']) && is_array($data['scopes'])) {
                $data['scopes'] = array_values(array_intersect($data['scopes'], $allowed));
            }

            $userScopes = array_values(array_intersect($userScopes, $allowed));
        }

        return [$data, $userScopes];
    }

    /**
     * @param  array<array-key, mixed>  $scopes
     * @return list<string>
     */
    private function flattenScopes(array $scopes): array
    {
        return collect($scopes)
            ->flatten()
            ->unique()
            ->values()
            ->all();
    }
}
