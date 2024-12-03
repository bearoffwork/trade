<?php

namespace App\Panel\Resources\ItemResource;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                        TextInput::make('users')
                            ->extraAttributes(['x-bind' => 'NamePicInput']),
                        TextArea::make('result')
                            ->extraAlpineAttributes(['data-result' => 'name']),
                    ])
            ]);
    }
}
