<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Grant;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;

readonly class GetAllowedGrantTypeOptions
{
    public function __construct(private ConfigRepository $configRepository)
    {
    }

    public function execute(): Collection
    {
        $values = collect();
        foreach ($this->configRepository->getAllowedGrantTypes() as $grantType) {
            $values->put(
                $grantType->value,
                ucfirst(str_replace('_', ' ', $grantType->value))
            );
        }
        return $values;
    }
}
