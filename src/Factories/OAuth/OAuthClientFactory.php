<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Exceptions\UnsupportedOAuthClientTypeException;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\OAuthClientCreationStrategyInterface;

readonly class OAuthClientFactory implements OAuthClientFactoryInterface
{
    /** @param iterable<OAuthClientCreationStrategyInterface> $strategies */
    public function __construct(
        private iterable $strategies
    ) {
    }

    public function __invoke(
        OAuthClientType $type,
        string $name,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        array $options = []
    ): Client {
        return $this->createUsingStrategy(
            $type,
            $name,
            $redirectUris,
            $provider,
            $confidential,
            $user,
            $options
        );
    }

    private function createUsingStrategy(
        OAuthClientType $type,
        string $name,
        array $redirectUris,
        ?string $provider,
        bool $confidential,
        ?Authenticatable $user,
        array $options
    ): Client {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy->create(
                    $name,
                    $redirectUris,
                    $provider,
                    $confidential,
                    $user,
                    $options
                );
            }
        }

        throw UnsupportedOAuthClientTypeException::forType($type);
    }
}
