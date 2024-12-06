<?php

namespace App\Panel\Resources;

use App\Database\Models\Item;
use App\Database\Models\User;
use App\Panel\Resources\ItemResource\Pages;
use App\Services\MoneyService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

// use App\Panel\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->extraAttributes([
                        'x-data' => '{result:null}',
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('item_name')
                            ->required(),
                        Forms\Components\Select::make('item_type')
                            ->required()
                            ->relationship('ItemType', 'id')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('id')
                                    ->label('Name')
                                    ->required(),
                                Forms\Components\TextInput::make('type_desc')
                                    ->label('Description'),
                            ]),
                        Forms\Components\ToggleButtons::make('Users')
                            ->required()
                            ->multiple()
                            ->inline()
                            ->options(User::pluck(User::getFrontendDisplayColumn(), 'id')),
                        Forms\Components\TextInput::make('ocr')
                            ->dehydrated(false)
                            ->placeholder('Paste Screenshot Here')
                            ->extraAttributes([
                                'x-data' => json_encode(['options' => User::pluck('id', 'name')]),
                                'x-bind' => 'NamePicInput',
                                'data-po-result-path' => 'Users',
                            ]),
                        Forms\Components\Select::make('act_id')
                            ->required()
                            ->relationship('Activity', 'id')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('id'),
                                Forms\Components\TextInput::make('act_desc'),
                            ]),
                        Forms\Components\DateTimePicker::make('drop_at')
                            ->required()
                            ->default(now()),
                        Forms\Components\DateTimePicker::make('close_at')
                            ->default(now()->endOfWeek())
                            ->hint(fn($state) => Carbon::parse($state)->diffForHumans(now()))
                            ->live(),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        // TODO display OCR scan bounding boxes
                    ]),
                Forms\Components\Fieldset::make('Buying')
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('total_amt')
                            ->numeric(),
                        Forms\Components\Select::make('buyer_uid')
                            ->relationship('Buyer', User::getFrontendDisplayColumn()),
                    ]),
            ]);
    }

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
                    ->action(function (Item $record, MoneyService $svc) {
                        if ($record->pay_at !== null) {
                            return;
                        }
                        $record->pay_at = now();
                        $record->save();
                        $svc->doShare(item: $record);
                    }),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model|Item $record): bool
    {
        return $record->pay_at === null && parent::canEdit($record);
    }
}
