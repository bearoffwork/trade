<?php

namespace App\Panel\Resources;

use App\Database\Models\FundRecord;
use App\Database\Models\Item;
use App\Panel\Resources\FundResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;

class FundResource extends Resource
{
    protected static ?string $model = FundRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(static function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('fundable_type')
                    ->label(__('wallet_records.table.fundable_type'))
                    ->color(fn(FundRecord $record) => match ($record->fundable_type) {
                        Item::class => Color::Green,
                        default => null,
                    })
                    ->formatStateUsing(fn(FundRecord $record) => match ($record->fundable_type) {
                        Item::class => 'Share',
                        default => null,
                    })
                    ->description(fn(FundRecord $record) => match ($record->fundable_type) {
                        Item::class => $record->Fundable->act_id.' - '.$record->Fundable->item_name,
                        default => null,
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('wallet_records.table.amount'))
                    ->formatStateUsing(fn($state) => sprintf('%+d', $state))
                    ->color(fn($state) => match (bccomp($state, '0')) {
                        1 => Color::Green,
                        -1 => Color::Red,
                        default => Color::Gray,
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('balance')
                    ->label(__('wallet_records.table.balance')),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFunds::route('/'),
            'create' => Pages\CreateFund::route('/create'),
        ];
    }
}
