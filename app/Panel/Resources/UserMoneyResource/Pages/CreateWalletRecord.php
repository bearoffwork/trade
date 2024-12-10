<?php

namespace App\Panel\Resources\UserMoneyResource\Pages;

use App\Panel\Resources\WalletResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWalletRecord extends CreateRecord
{
    protected static string $resource = WalletResource::class;

    protected bool $hasActionsModalRendered = true;
}
