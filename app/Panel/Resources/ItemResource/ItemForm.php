<?php

namespace App\Panel\Resources\ItemResource;

use App\Models\User;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;

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

                        Select::make('ItemType')
                            ->relationship('ItemType', 'id'),

                        ToggleButtons::make('Test')
                            ->extraAttributes([
                                'x-ref' => 'test',
                                '@change' => '',
                            ])
                            ->multiple()
                            ->inline()
                            ->options(User::pluck('name', 'id')),

                        TextInput::make('users')
                            ->placeholder('Paste Screenshot Here')
                            ->extraAttributes([
                                'x-data' => json_encode(['options' => User::pluck('id', 'name')]),
                                'x-bind' => 'NamePicInput',
                                'data-po-result-path' => 'Test',
                            ]),
                    ]),
            ]);
    }
}
