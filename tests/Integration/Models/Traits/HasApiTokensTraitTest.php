<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Models\Traits;

use App\Models\HasApiTokensUser;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeActionFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeGrantFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\FilamentPassportUi\Services\GrantService;
use Laravel\Passport\AccessToken;

class HasApiTokensTraitTest extends DatabaseTestCase
{
    private PassportScopeResource $resource;

    private PassportScopeAction $action;

    private string $scope;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = PassportScopeResourceFactory::new()->create([
            'name' => 'users',
        ]);
        $this->action = PassportScopeActionFactory::new()->create([
            'name' => 'update',
        ]);

        $this->scope = $this->resource->getAttribute('name') . ':' . $this->action->getAttribute('name');
    }

    public function testTokenCanReturnsFalseWhenTokenLacksGrant(): void
    {
        $user = HasApiTokensUser::factory()->create();

        $accessToken = new AccessToken([
            'oauth_scopes' => [$this->scope],
        ]);

        $user->withAccessToken($accessToken);

        $this->assertFalse(app(GrantService::class)->tokenableHasGrantToScope($user, $this->scope));
        $this->assertFalse($user->tokenCan($this->scope));
    }

    public function testTokenCanReturnsTrueWhenTokenHasGrant(): void
    {
        $user = HasApiTokensUser::factory()->create();

        PassportScopeGrantFactory::new()
            ->withTokenable($user)
            ->create([
                'resource_id' => $this->resource->getKey(),
                'action_id' => $this->action->getKey(),
            ]);

        $accessToken = new AccessToken([
            'oauth_scopes' => [$this->scope],
        ]);

        $user->withAccessToken($accessToken);

        $this->assertTrue($user->tokenCan($this->scope));
    }

    public function testTokenCanReturnsFalseWhenScopeMissingOnToken(): void
    {
        $user = HasApiTokensUser::factory()->create();

        PassportScopeGrantFactory::new()
            ->withTokenable($user)
            ->create([
                'resource_id' => $this->resource->getKey(),
                'action_id' => $this->action->getKey(),
            ]);

        $accessToken = new AccessToken([
            'oauth_scopes' => [],
        ]);

        $user->withAccessToken($accessToken);

        $this->assertFalse($user->tokenCan($this->scope));
    }
}
