<?php

namespace App\Panel\Resources\FundResource\Pages;

use App\Panel\Resources\FundResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFund extends CreateRecord
{
    protected static string $resource = FundResource::class;
}
