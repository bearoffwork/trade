<?php

namespace App\Panel\Resources;

use App\Database\Models\WalletRecord;
use App\Enums\WalletRecordCategory;
use App\Panel\Pages\Withdraw;
use App\Panel\Resources\UserMoneyResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WalletResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = WalletRecord::class;

    public static function getModelLabel(): string
    {
        return __('wallet_records.model_label');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textinput::make('uid'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $canViewAll = auth()->user()->can('viewAll', WalletRecord::class);

        return $table
            ->modifyQueryUsing(static function (Builder $query) use ($canViewAll) {
                $query
                    ->when(
                        value: !$canViewAll,
                        callback: fn(Builder $query) => $query->where('uid', auth()->id())
                    )
                    ->orderBy('id', 'desc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->label(__('wallet_records.table.username'))
                    ->hidden(!$canViewAll),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('wallet_records.table.category'))
                    ->description(function (WalletRecord $record) {
                        return match ($record->category) {
                            WalletRecordCategory::Share => $record->Item->act_id.' - '.$record->Item->item_name,
                            default => null,
                        };
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
            'index' => Pages\ListWalletRecord::route('/'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_all',
            ...config('filament-shield.permission_prefixes.resource'),
        ];
    }
}
