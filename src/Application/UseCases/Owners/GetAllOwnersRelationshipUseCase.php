<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Owners;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class GetAllOwnersRelationshipUseCase extends GetAllOwnersUseCase
{
    public function execute(): Collection
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = config('filament-passport-ui.owner_model');
        $keyName = (new $modelClass)->getKeyName();

        return parent::execute()->pluck(
            config('filament-passport-ui.owner_label_attribute', 'name'),
            $keyName,
        );
    }
}
