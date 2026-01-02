<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionsResource;

class ResourceActionManager extends RelationManager
{
    protected static string $relationship = 'actions';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        $form = PassportScopeActionsResource::form($schema);
        $form->getComponent('resource_id')?->default(
            $this->ownerRecord->getKey()
        )->disabled();
        return $form;
    }

    public function table(Table $table): Table
    {
        $table = PassportScopeActionsResource::table($table);
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orWhere('resource_id', null))
            ->headerActions([
                CreateAction::make()
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.header_action.create'
                        )
                    ),
            ]);
    }
}
