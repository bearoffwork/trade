<?php

namespace App\Panel\Resources\ItemResource;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

trait ItemTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->select('*')
                ->selectSub(
                    query: User::query()
                        ->select(User::getFrontendDisplayColumn())
                        ->whereColumn('id', 'items.buyer_uid'),
                    as: 'buyer'
                )
            )
            ->columns([
                Tables\Columns\TextColumn::make('item_name'),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('total_amt'),
                Tables\Columns\TextColumn::make('buyer'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
