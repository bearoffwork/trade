<?php

namespace Database\Factories\Database\Models;

use App\Database\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->words(asText: true),
            'act_desc' => $this->faker->randomElement([$this->faker->sentence(), null]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
