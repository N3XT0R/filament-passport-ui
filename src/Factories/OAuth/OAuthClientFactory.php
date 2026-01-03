<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\DTO\Client\OAuthClientData;
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
        OAuthClientData $data,
        ?Authenticatable $user = null,
    ): Client {
        return $this->createUsingStrategy($type, $data, $user);
    }

    private function createUsingStrategy(
        OAuthClientType $type,
        OAuthClientData $data,
        ?Authenticatable $user,
    ): Client {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy->create(
                    name: $data->name,
                    redirectUris: $data->redirectUris,
                    provider: $data->provider,
                    confidential: $data->confidential,
                    user: $user,
                    options: $data->options
                );
            }
        }

        throw UnsupportedOAuthClientTypeException::forType($type);
    }
}
