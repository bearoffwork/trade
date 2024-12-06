<?php

namespace Database\Seeders;

use App\Database\Models\Activity;
use App\Database\Models\Item;
use App\Database\Models\ItemType;
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
