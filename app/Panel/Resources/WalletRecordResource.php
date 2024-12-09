<?php

namespace App\Panel\Resources;

use App\Database\Models\Role;
use App\Database\Models\WalletRecord;
use App\Panel\Resources\UserMoneyResource\Pages;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class WalletRecordResource extends Resource
{
    protected static ?string $model = WalletRecord::class;

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
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query
                    ->when(
                        value: auth()->user()->hasRole(Role::ROLE_ADMIN),
                        callback: fn($query) => $query
                            ->whereIn('id', WalletRecord::select(DB::raw('MAX(id) as id'))->groupBy('uid')),
                        default: fn($query) => $query
                            ->where('uid', auth()->id())
                    );
            })
            ->columns([
                Tables\Columns\TextColumn::make('username'),
                Tables\Columns\TextColumn::make('balance'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Withdraw')
                    ->label('Withdraw')
                    ->form([
                        Placeholder::make('user')
                            ->content(fn(WalletRecord $record) => $record->username),
                        TextInput::make('amount')->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(function (WalletRecord $record, array $data) {
                        $newRecord = $record->replicate(['id']);
                        $newRecord->balance = $newRecord->balance - data_get($data, 'amount');
                        $newRecord->save();
                    }),
            ], position: Tables\Enums\ActionsPosition::BeforeCells)
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'create' => Pages\CreateWalletRecord::route('/create'),
        ];
    }
}
