<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Laravel\Passport\Passport;
use Laravel\Passport\Token;

class TokenRepository
{

    public function count(): int
    {
        return $this->getTokenModel()::count();
    }

    public function notExpiredCount(): int
    {
        return $this->getTokenModel()::where('expires_at', '>', now())->count();
    }

    /**
     * @return class-string<Token>
     */
    private function getTokenModel(): string
    {
        return Passport::tokenModel();
    }
}
