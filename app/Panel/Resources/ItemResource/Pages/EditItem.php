<?php

namespace App\Panel\Resources\ItemResource\Pages;

use App\Database\Models\Item;
use App\Panel\Resources\ItemResource;
use App\Services\MoneyService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Item $record
 */
class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function handleRecordUpdate(Model|Item $record, array $data): Model
    {
        $isPaid = $record->pay_at !== null;
        /** @var Item $item */
        $item = parent::handleRecordUpdate($record, $data);
        // 第一次結帳
        if (!$isPaid && data_get($data, 'pay_at') !== null) {
            info('checkout', ['item' => $item->id]);
            app(MoneyService::class)->doShare(item: $item);
        }

        return $item;
    }
}
