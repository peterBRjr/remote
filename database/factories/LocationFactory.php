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

        $spotNames = [
            'Coffee Lab & Work',
            'Paulista Hub Coworking',
            'Urban Roasters & Lounge',
            'Pinheiros Tech Cafe',
            'Vila Madalena Workstation',
            'Itaim Founders Club',
            'Faria Lima Workspace',
            'Moema Roastery & Code',
            'Jardins Creative Lounge',
        ];

        $neighborhoods = [
            ['address' => 'R. Fradique Coutinho - Pinheiros, São Paulo - SP', 'lat' => -23.5582, 'lng' => -46.6890],
            ['address' => 'Av. Paulista, 1374 - Bela Vista, São Paulo - SP', 'lat' => -23.5615, 'lng' => -46.6559],
            ['address' => 'R. Aspicuelta, 288 - Vila Madalena, São Paulo - SP', 'lat' => -23.5539, 'lng' => -46.6881],
            ['address' => 'Av. Brigadeiro Faria Lima - Itaim Bibi, São Paulo - SP', 'lat' => -23.5866, 'lng' => -46.6823],
            ['address' => 'Alameda Santos - Cerqueira César, São Paulo - SP', 'lat' => -23.5658, 'lng' => -46.6511],
            ['address' => 'R. Oscar Freire - Jardins, São Paulo - SP', 'lat' => -23.5621, 'lng' => -46.6698],
        ];

        $sampleImages = [
            'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80',
        ];

        $locationInfo = fake()->randomElement($neighborhoods);

        return [
            'name' => fake()->randomElement($spotNames) . ' (' . fake()->numberBetween(1, 99) . ')',
            'address' => $locationInfo['address'],
            'latitude' => $locationInfo['lat'] + (rand(-50, 50) / 10000),
            'longitude' => $locationInfo['lng'] + (rand(-50, 50) / 10000),
            'category' => fake()->randomElement($categories),
            'wifi_speed_mbps' => fake()->numberBetween(80, 500),
            'noise_level' => fake()->randomElement($noises),
            'outlet_density' => fake()->randomElement($outlets),
            'description' => fake()->paragraph(2),
            'image_url' => fake()->randomElement($sampleImages),
            'weather_icon' => '01d',
            'weather_temp' => fake()->randomFloat(1, 20, 29),
        ];
    }
}
