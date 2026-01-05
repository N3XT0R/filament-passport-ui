<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ClientResourceTest extends DatabaseTestCase
{
    public function testLabelTranslationsAreResolved(): void
    {
        $this->assertSame('OAuth Clients', ClientResource::getLabel());
        $this->assertSame('Clients', ClientResource::getPluralLabel());
    }

    public function testNavigationBadgeReturnsClientCount(): void
    {
        Client::factory()->count(2)->create();

        $this->assertSame('2', ClientResource::getNavigationBadge());
    }

    public function testNavigationGroupUsesConfiguredValue(): void
    {
        config()->set('passport-ui.navigation.group', 'Custom Group');

        $this->assertSame('Custom Group', ClientResource::getNavigationGroup());
    }

    public function testModelReturnsPassportClientModel(): void
    {
        $this->assertSame(Passport::clientModel(), ClientResource::getModel());
    }
}
