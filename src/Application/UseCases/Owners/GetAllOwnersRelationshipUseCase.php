<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Owners;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class GetAllOwnersRelationshipUseCase extends GetAllOwnersUseCase
{
    /**
     * Get All Owners as relationship options
     * @return Collection
     */
    public function execute(): Collection
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = config('passport-ui.owner_model', 'App\\Models\\User');
        $keyName = new $modelClass()->getKeyName();

        return parent::execute()->pluck(
            config('passport-ui.owner_label_attribute', 'name'),
            $keyName,
        );
    }
}
