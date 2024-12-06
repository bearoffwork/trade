<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MoneyAct: int implements HasLabel
{
    case Fund = 0;
    case Income = 1;
    case Buy = 2;
    case Withdrawal = 3;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
