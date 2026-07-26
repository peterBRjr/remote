<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('category', ['cafe', 'coworking', 'library', 'hotel_lobby'])->default('cafe');
            $table->integer('wifi_speed_mbps')->default(50); // Speed in Mbps
            $table->enum('noise_level', ['quiet', 'moderate', 'lively'])->default('moderate');
            $table->enum('outlet_density', ['scarce', 'moderate', 'abundant'])->default('abundant');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('weather_icon')->nullable();
            $table->decimal('weather_temp', 4, 1)->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
