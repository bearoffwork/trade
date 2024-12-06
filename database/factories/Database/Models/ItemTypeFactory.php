<?php

namespace Database\Factories\Database\Models;

use App\Database\Models\ItemType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ItemTypeFactory extends Factory
{
    protected $model = ItemType::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->words(asText: true),
            'type_desc' => $this->faker->randomElement([$this->faker->sentence(), null]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
