<?php

namespace App\Panel\Resources\UserMoneyResource\Pages;

use App\Panel\Resources\UserMoneyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserMoney extends CreateRecord
{
    protected static string $resource = UserMoneyResource::class;

    protected bool $hasActionsModalRendered = true;
}
