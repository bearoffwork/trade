<?php

namespace App\Panel\Resources\ItemResource\Pages;

use App\Panel\Resources\ItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;
}
