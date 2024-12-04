<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'item_name' => $this->faker->words(asText: true),
            'qty' => 1,
            'act_id' => Activity::factory(),
            'buyer_uid' => $buyer = $this->faker->randomElement([fn() => User::inRandomOrder()->value('id'), null]),
            'total_amt' => $buyer !== null ? $this->faker->numberBetween(1, 500) * 350 : null,
            'drop_at' => Carbon::now(),
            'close_at' => Carbon::now(),
            'pay_at' => $buyer !== null ? $this->faker->randomElement([now(), null]) : null,
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
            $item->Users()->attach($users);
        });
    }
}
