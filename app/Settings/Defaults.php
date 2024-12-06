<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class Defaults extends Settings
{
    public string $fund_share;

    public static function group(): string
    {
        return 'defaults';
    }
}
