<?php

namespace App\Panel\Resources\ItemResource\Pages;

use App\Database\Models\Item;
use App\Panel\Resources\ItemResource;
use App\Services\MoneyService;
use Filament\Resources\Pages\EditRecord;

/**
 * @property Item $record
 */
class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    public function afterSave(): void
    {
        if ($this->record instanceof Item && $this->record->pay_at !== null) {
            app(MoneyService::class)->doShare(item: $this->record);
        }
    }

    protected function getRedirectUrl(): ?string
    {
        return $this::$resource::getUrl();
    }
}
