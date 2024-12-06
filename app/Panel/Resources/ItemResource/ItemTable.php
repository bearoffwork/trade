<?php

namespace App\Panel\Resources\ItemResource;

use App\Models\Item;
use App\Models\User;
use App\Models\WalletRecord;
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
                Tables\Columns\TextColumn::make('item_type'),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('total_amt'),
                Tables\Columns\TextColumn::make('buyer'),
                Tables\Columns\TextColumn::make('pay_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('checkout')
                    ->hidden(fn(Item $record) => $record->pay_at !== null || $record->buyer_uid === null)
                    ->label('Checkout')
                    ->requiresConfirmation()
                    ->action(function (Item $record) {
                        if ($record->pay_at !== null) {
                            return;
                        }
                        $record->pay_at = now();
                        $record->save();
                        $divCount = $record->Users()->count();
                        $divAmount = bcdiv($record->total_amt, $divCount, 2);
                        // 取整數
                        $divAmount = explode('.', $divAmount)[0];
                        // TODO 除不盡餘額
                        $modAmount = bcmod($record->total_amt, $divCount);

                        $record->Users->each(static function (User $user) use ($divAmount) {
                            /** @var WalletRecord $money */
                            $money = $user->Balance?->replicate(except: ['id']) ?? new WalletRecord(['balance' => 0]);
                            $money->amount = $divAmount;
                            $money->balance = bcadd($money->balance, $divAmount);
                            $user->Money()->save($money);
                        });
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
