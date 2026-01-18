<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources\ClientResource;

use Carbon\CarbonImmutable;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\TokenFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ListClients;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\GrantTypesColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\LastLoginColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\NameColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\OwnerNameColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\RevokeColumn;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class ClientResourceColumnsTest extends DatabaseTestCase
{
    public function testNameColumnFormatsHeadlineAndIsNotSortable(): void
    {
        $column = NameColumn::make();

        self::assertFalse($column->isSortable());
        self::assertSame('Test Client', $column->formatState('test client'));
    }

    public function testOwnerNameColumnIsSearchableAndLabeled(): void
    {
        $column = OwnerNameColumn::make();

        self::assertTrue($column->isSearchable());
        self::assertSame(
            __('filament-passport-ui::passport-ui.client_resource.column.owner'),
            $column->getLabel()
        );
    }

    public function testGrantTypesColumnShowsLineBreaksAndSearchable(): void
    {
        $column = GrantTypesColumn::make();

        self::assertTrue($column->isListWithLineBreaks());
        self::assertTrue($column->isSearchable());
        self::assertSame(
            __('filament-passport-ui::passport-ui.client_resource.column.grant_type'),
            $column->getLabel()
        );
    }

    public function testLastLoginColumnUsesLatestTokenTimestamp(): void
    {
        /** @var Client $client */
        $client = ClientFactory::new()->create();
        $lastLogin = CarbonImmutable::now()->subDay();

        TokenFactory::new()->withClient($client)->create([
            'updated_at' => $lastLogin,
        ]);

        $component = Livewire::test(ListClients::class);
        $table = $component->instance()->getTable();
        $columns = $table->getColumns();

        /** @var LastLoginColumn $column */
        $column = $columns['last_login'];
        $column->record($client);

        $state = $column->getState();

        self::assertNotNull($state);
        self::assertSame($lastLogin->toDateTimeString(), $state->toDateTimeString());
    }

    public function testRevokeColumnIsToggleableAndLabeled(): void
    {
        $column = RevokeColumn::make();

        self::assertTrue($column->isToggleable());
        self::assertSame(
            __('filament-passport-ui::passport-ui.client_resource.column.revoked'),
            $column->getLabel()
        );
    }
}
