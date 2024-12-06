<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class Defaults extends Settings
{
    public string $fund_rate;

    public string $tax_rate;

    public static function group(): string
    {
        return 'defaults';
    }
}
