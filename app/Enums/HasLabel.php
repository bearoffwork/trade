<?php

namespace App\Enums;

/**
 * @see \Filament\Support\Contracts\HasLabel
 */
trait HasLabel
{
    public function getLabel(): ?string
    {
        return __('enums.'.self::class.'.'.$this->value);
    }
}
