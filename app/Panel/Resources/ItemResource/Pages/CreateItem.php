<?php

namespace App\Panel\Resources\ItemResource\Pages;

use App\Database\Models\Item;
use App\Panel\Resources\ItemResource;
use App\Services\MoneyService;
use App\Settings\Defaults;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['create_uid'] = auth()->id();
        $data['tax_rate'] ??= app(Defaults::class)->tax_rate;

        return $data;
    }

    public function afterCreate(): void
    {
        if ($this->record instanceof Item && $this->record->pay_at !== null) {
            app(MoneyService::class)->doShare(item: $this->record);
        }
    }
}
