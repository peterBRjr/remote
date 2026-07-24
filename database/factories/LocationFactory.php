<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $categories = ['cafe', 'coworking', 'library', 'hotel_lobby'];
        $noises = ['quiet', 'moderate', 'lively'];
        $outlets = ['scarce', 'moderate', 'abundant'];

        $sampleImages = [
            'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80',
        ];

        return [
            'name' => fake()->company() . ' Coffee & Work',
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'latitude' => fake()->latitude(-23.65, -23.50), // São Paulo area default
            'longitude' => fake()->longitude(-46.75, -46.60),
            'category' => fake()->randomElement($categories),
            'wifi_speed_mbps' => fake()->numberBetween(30, 300),
            'noise_level' => fake()->randomElement($noises),
            'outlet_density' => fake()->randomElement($outlets),
            'description' => fake()->paragraph(2),
            'image_url' => fake()->randomElement($sampleImages),
            'weather_summary' => 'Ensolarado',
            'weather_icon' => '01d',
            'weather_temp' => fake()->randomFloat(1, 20, 29),
        ];
    }
}
