<?php

namespace App\Panel\Resources\UserMoneyResource\Pages;

use App\Panel\Resources\WalletRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListWalletRecord extends ListRecords
{
    protected static string $resource = WalletRecordResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
