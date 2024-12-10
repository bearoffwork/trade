<?php

namespace Database\Factories\Database\Models;

use App\Database\Models\Activity;
use App\Database\Models\Item;
use App\Database\Models\ItemType;
use App\Database\Models\User;
use App\Settings\Defaults;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        $defaults = app(Defaults::class);

        return [
            'item_name' => $this->faker->words(asText: true),
            'qty' => 1,
            'act_id' => Activity::factory(),
            'tax_rate' => $defaults->tax_rate,
            'fund_rate' => $defaults->fund_rate,
            'buyer_uid' => $buyer = $this->faker->randomElement([fn() => User::inRandomOrder()->value('id'), null]),
            'total_amt' => $buyer !== null ? $this->faker->numberBetween(1, 500) * 350 : null,
            'drop_at' => Carbon::now(),
            'create_uid' => User::inRandomOrder()->value('id'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'item_type' => ItemType::factory(),
        ];
    }

    public function withRandomUsers(): self
    {
        return $this->afterCreating(function (Item $item) {
            // Attach random users
            $users = User::inRandomOrder()->take(fake()->numberBetween(3, 6))->get();
            $item->Participants()->attach($users);
        });
    }
}
