<?php

namespace App\Panel\Resources;

use App\Database\Models\Item;
use App\Database\Models\User;
use App\Panel\Resources\ItemResource\Pages;
use App\Services\MoneyService;
use App\Settings\Defaults;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// use App\Panel\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $defaults = app(Defaults::class);

        return $form
            ->preventSubmitOnEnter()
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
                        Forms\Components\CheckboxList::make('Users')
                            ->columns(3)
                            ->searchable()
                            ->relationship(name: 'Users', titleAttribute: User::getFrontendDisplayColumn()),
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
                        TextInput::make('fund_rate')
                            ->percentage()
                            ->default($defaults->fund_rate)
                            ->required(),
                        TextInput::make('total_amt')
                            ->number()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set, MoneyService $svc) => $set(
                                path: 'posted_amt',
                                state: $svc->getPostedAmt($get('total_amt'))
                            ))
                            ->requiredIf('is_paid', '1')
                            ->validationMessages([
                                'required_if' => ':attribute is required on Checkout',
                            ]),
                        TextInput::make('posted_amt')
                            ->number()
                            ->hint('Tax rate '.bcmul($defaults->tax_rate, '100').'%')
                            ->formatStateUsing(fn($state, Get $get, MoneyService $svc) => $state ?? $svc->getPostedAmt($get('total_amt')))
                            ->requiredIf('is_paid', '1')
                            ->validationMessages([
                                'required_if' => ':attribute is required on Checkout',
                            ]),
                        Select::make('buyer_uid')
                            ->relationship(name: 'Buyer', titleAttribute: User::getFrontendDisplayColumn())
                            ->searchable(['name'])
                            ->preload()
                            ->requiredIf('is_paid', '1')
                            ->validationMessages([
                                'required_if' => ':attribute is required on Checkout',
                            ]),
                        Forms\Components\ToggleButtons::make('is_paid')
                            ->dehydrated(false)
                            ->inline()
                            ->boolean()
                            ->formatStateUsing(fn(?Item $record) => $record?->pay_at !== null),
                        Forms\Components\DateTimePicker::make('pay_at')
                            ->required()
                            ->dehydrated(fn(Get $get) => $get('is_paid'))
                            ->extraFieldWrapperAttributes(['x-show' => '$wire.data.is_paid == 1'])
                            ->formatStateUsing(fn($state) => $state ?? now()),
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
                Tables\Columns\TextColumn::make('item_name')
                    ->label(__('items.table.item_name')),
                Tables\Columns\TextColumn::make('item_type')
                    ->label(__('items.table.item_type')),
                Tables\Columns\TextColumn::make('qty')
                    ->label(__('items.table.qty')),
                Tables\Columns\TextColumn::make('total_amt')
                    ->label(__('items.table.total_amt')),
                Tables\Columns\TextColumn::make('buyer')
                    ->label(__('items.table.buyer_uid')),
                Tables\Columns\TextColumn::make('pay_at')
                    ->label(__('items.table.pay_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //                    ->hidden(fn(Item $record) => $record->pay_at !== null || $record->buyer_uid === null)
                //                    ->label('Checkout')
                //                    ->requiresConfirmation()
                //                    ->action(function (Item $record, MoneyService $svc) {
                //                        if ($record->pay_at !== null) {
                //                            return;
                //                        }
                //                        $record->pay_at = now();
                //                        $record->save();
                //                        $svc->doShare(item: $record);
                //                    }),
            ])
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
            'view' => Pages\ViewItem::route('/{record}/view'),
        ];
    }

    public static function canEdit(Model|Item $record): bool
    {
        return $record->pay_at === null && parent::canEdit($record);
    }
}
