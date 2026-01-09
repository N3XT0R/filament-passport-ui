<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Models\Traits;

use App\Models\PassportScopeGrantUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use LogicException;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Traits\HasPassportScopeGrantsTrait;

final class HasPassportScopeGrantsTraitTest extends DatabaseTestCase
{
    public function testPassportScopeGrantsReturnsMorphManyRelation(): void
    {
        $user = PassportScopeGrantUser::factory()->create();

        $resource = PassportScopeResource::factory()->create([
            'name' => 'videos',
        ]);

        $action = PassportScopeAction::factory()
            ->withResource($resource)
            ->create([
                'name' => 'read',
            ]);

        $grant = PassportScopeGrant::factory()
            ->withTokenable($user)
            ->create([
                'resource_id' => $resource->getKey(),
                'action_id' => $action->getKey(),
            ]);

        $relation = $user->passportScopeGrants();

        self::assertInstanceOf(MorphMany::class, $relation);
        self::assertTrue($relation->get()->contains($grant));
        self::assertSame($user->getMorphClass(), $grant->tokenable_type);
        self::assertTrue($grant->tokenable->is($user));
    }

    public function testPassportScopeGrantsThrowsLogicExceptionWhenInterfaceMissing(): void
    {
        $model = new class extends Model {
            use HasPassportScopeGrantsTrait;
        };

        $this->expectException(LogicException::class);

        $model->passportScopeGrants();
    }
}
