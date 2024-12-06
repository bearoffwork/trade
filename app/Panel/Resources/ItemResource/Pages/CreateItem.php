<?php

namespace App\Panel\Resources\ItemResource\Pages;

use App\Database\Models\Item;
use App\Panel\Resources\ItemResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $users = data_get($data, 'Users');
        unset($data['Users']);

        $data['create_uid'] = auth()->id();

        /** @var Item $model */
        $model = parent::handleRecordCreation($data);
        $model->Users()->sync($users);

        return $model;
    }
}
