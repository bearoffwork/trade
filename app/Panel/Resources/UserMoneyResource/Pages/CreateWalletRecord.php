<?php

namespace App\Panel\Resources\UserMoneyResource\Pages;

use App\Panel\Resources\WalletRecordResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWalletRecord extends CreateRecord
{
    protected static string $resource = WalletRecordResource::class;

    protected bool $hasActionsModalRendered = true;
}
