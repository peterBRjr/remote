<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'location_id' => Location::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'comment' => fake()->realText(120),
            'wifi_rating' => fake()->numberBetween(3, 5),
            'comfort_rating' => fake()->numberBetween(3, 5),
        ];
    }
}
