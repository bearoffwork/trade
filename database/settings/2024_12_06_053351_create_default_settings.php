<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('defaults.fund_rate', '0.10');
        $this->migrator->add('defaults.tax_rate', '0.09');
    }
};
