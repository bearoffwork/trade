<?php

namespace App\Panel\Pages;

use App\Database\Models\User;
use App\Database\Models\WalletRecord;
use App\Enums\WalletRecordCategory;
use App\Panel\Resources\WalletResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @property Form $form
 */
class Withdraw extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'panel.pages.withdraw';

    public ?array $data = [];

    public function getTitle(): string|Htmlable
    {
        return __('withdraw.title');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('records')
                    ->orderColumn(false)
                    ->columns()
                    ->schema([
                        Select::make('uid')
                            ->label(__('withdraw.records.User'))
                            ->required()
                            ->options(User::pluck('name', 'id')->toArray())
                            ->searchable(),
                        TextInput::make('amount')
                            ->required()
                            ->number(),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        foreach ($this->data['records'] as $states) {
            $states['amount'] = bcmul($states['amount'], '-1');

            /** @var User $user */
            $user = User::find($states['uid']);
            $record = $user->Balance?->replicate() ?? new WalletRecord(['balance' => 0]);
            $record->fill($states);
            $record->category = WalletRecordCategory::Withdraw;
            $record->balance = bcadd($record->balance, $record->amount);
            $record->save();
        }

        $this->redirect(WalletResource::getUrl());
    }
}
