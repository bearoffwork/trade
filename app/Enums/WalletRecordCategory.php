<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel as HasLabelContract;

enum WalletRecordCategory: int implements HasColor, HasLabelContract
{
    use HasLabel;

    /** 分潤 */
    case Share = 1;

    /** 提款 */
    case Withdraw = 2;

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Share => Color::Green,
            self::Withdraw => Color::Red,
        };
    }
}
