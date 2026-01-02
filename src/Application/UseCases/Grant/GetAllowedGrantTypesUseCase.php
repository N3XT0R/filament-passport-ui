<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Grant;

class GetAllowedGrantTypesUseCase
{
    /**
     * Get allowed grant types from config
     * @return array
     */
    public function execute(): array
    {
        return config('passport-ui.allowed_grant_types', [
            'authorization_code',
            'client_credentials',
            'password',
            'personal_access',
            'refresh_token',
        ]);
    }
}
