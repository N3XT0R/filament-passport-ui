<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\CreateClientUseCase;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    public function form(Schema $schema): Schema
    {
        return ClientResource\Schemas\CreateClientForm::configure(
            $schema,
            static::getResource()::getAdditionalFormComponents($schema)
        );
    }


    protected function handleRecordCreation(array $data): Model
    {
        //dd($data);
        if (isset($data['scopes']) && is_array($data['scopes'])) {
            $data['scopes'] = collect($data['scopes'])
                ->flatten()
                ->unique()
                ->values()
                ->all();
        }

        $result = app(CreateClientUseCase::class)->execute(
            data: $data,
            actor: Filament::auth()->user(),
        );

        /**
         * @note
         * Ugly implementation but Laravel Passport only shows the client secret
         * upon creation. So we store it in the session to show it in the view page
         * right after creation. Filament always redirects to the view page after creation.
         */
        Session::put('new_client_secret_' . $result->client->getKey(), $result->plainSecret);

        return $result->client;
    }
}
