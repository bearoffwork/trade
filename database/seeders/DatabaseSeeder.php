<?php

namespace Database\Seeders;

use App\Enums\MoneyAct;
use App\Models\Activity;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ItemType::factory(6)
            ->create();

        Activity::factory(6)
            ->create();

        Item::factory(10)
            ->withRandomUsers()
            ->create();
    }
}
