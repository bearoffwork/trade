<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ItemType;
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
        // INSERT INTO items (item_name, item_type, create_uid, drop_at, act_id)
        // VALUES ('t', 'at', 1, CURRENT_TIMESTAMP, 'test')
        Activity::create(['id' => 'test_act']);
    }
}
