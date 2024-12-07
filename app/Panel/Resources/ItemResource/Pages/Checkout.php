<?php

namespace App\Panel\Resources\ItemResource\Pages;

use App\Panel\Resources\ItemResource;
use Filament\Resources\Pages\EditRecord;

class Checkout extends EditRecord
{
    protected static string $resource = ItemResource::class;
}
