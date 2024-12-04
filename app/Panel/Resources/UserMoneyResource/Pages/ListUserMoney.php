<?php

namespace App\Panel\Resources\UserMoneyResource\Pages;

use App\Panel\Resources\UserMoneyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserMoney extends ListRecords
{
    protected static string $resource = UserMoneyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
