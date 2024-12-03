<?php

namespace App\Panel\Resources;

use App\Models\Item;
use App\Panel\Resources\ItemResource\ItemForm;
use App\Panel\Resources\ItemResource\ItemTable;
use App\Panel\Resources\ItemResource\Pages;
use Filament\Resources\Resource;

// use App\Panel\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    use ItemForm;
    use ItemTable;

    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
