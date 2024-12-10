<?php

namespace App\Panel\Resources\FundResource\Pages;

use App\Panel\Resources\FundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFunds extends ListRecords
{
    protected static string $resource = FundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
