<?php

namespace App\Panel\Resources;

use App\Models\UserMoney;
use App\Panel\Resources\UserMoneyResource\Pages;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserMoneyResource extends Resource
{
    protected static ?string $model = UserMoney::class;

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
                $query->whereIn('id', UserMoney::select(DB::raw('MAX(id) as id'))->groupBy('uid'));
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
                            ->content(fn(UserMoney $record) => $record->username),
                        TextInput::make('amount')->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(function (UserMoney $record, array $data) {
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
            'index' => Pages\ListUserMoney::route('/'),
            'create' => Pages\CreateUserMoney::route('/create'),
        ];
    }
}
