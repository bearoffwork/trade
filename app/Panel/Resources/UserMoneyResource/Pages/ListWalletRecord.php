<?php

namespace App\Panel\Resources\UserMoneyResource\Pages;

use App\Database\Models\WalletRecord;
use App\Panel\Pages\Withdraw;
use App\Panel\Resources\WalletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWalletRecord extends ListRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Actions\Action::make('Withdraw')
                ->label(__('withdraw.title'))
                ->url(Withdraw::getUrl())
                ->hidden(!auth()->user()->can('create', WalletRecord::class)),
        ];
    }
}
