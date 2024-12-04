<?php

namespace App\Panel\Resources\ItemResource\Pages;

use App\Models\Item;
use App\Panel\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Item $record
 */
class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return data_set($data, 'Users', $this->record->Users->pluck('id')->toArray());
    }

    protected function handleRecordUpdate(Model|Item $record, array $data): Model
    {
        $users = $data['Users'];
        unset($data['Users']);
        $record->Users()->sync($users);

        return parent::handleRecordUpdate($record, $data);
    }
}
