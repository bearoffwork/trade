<?php

namespace App\Panel\Resources\ItemResource;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;

trait ItemForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->extraAttributes([
                        'x-data' => '{result:null}',
                    ])
                    ->schema([
                        TextInput::make('item_name')
                            ->required(),
                        Select::make('item_type')
                            ->required()
                            ->relationship('ItemType', 'id')
                            ->createOptionForm([
                                TextInput::make('id')
                                    ->label('Name')
                                    ->required(),
                                TextInput::make('type_desc')
                                    ->label('Description'),
                            ]),
                        ToggleButtons::make('Users')
                            ->required()
                            ->multiple()
                            ->inline()
                            ->options(User::pluck(User::getFrontendDisplayColumn(), 'id')),
                        TextInput::make('ocr')
                            ->dehydrated(false)
                            ->placeholder('Paste Screenshot Here')
                            ->extraAttributes([
                                'x-data' => json_encode(['options' => User::pluck('id', 'name')]),
                                'x-bind' => 'NamePicInput',
                                'data-po-result-path' => 'Users',
                            ]),
                        Select::make('act_id')
                            ->required()
                            ->relationship('Activity', 'id')
                            ->createOptionForm([
                                TextInput::make('id'),
                                TextInput::make('act_desc'),
                            ]),
                        DateTimePicker::make('drop_at')
                            ->required()
                            ->default(now()),
                        DateTimePicker::make('close_at')
                            ->default(now()->endOfWeek())
                            ->hint(fn($state) => Carbon::parse($state)->diffForHumans(now()))
                            ->live(),
                    ]),
                Group::make()
                    ->schema([
                        // TODO display OCR scan bounding boxes
                    ]),
                Fieldset::make('Buying')
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        TextInput::make('amount')
                            ->numeric(),
                        Select::make('buyer_uid')
                            ->relationship('Buyer', User::getFrontendDisplayColumn()),
                    ]),
            ]);
    }
}
