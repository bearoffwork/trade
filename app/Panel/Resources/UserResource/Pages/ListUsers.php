<?php

namespace App\Panel\Resources\UserResource\Pages;

use App\Panel\Pages\Withdraw;
use App\Panel\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('withdraw')
                ->url(Withdraw::getUrl()),
        ];
    }
}
