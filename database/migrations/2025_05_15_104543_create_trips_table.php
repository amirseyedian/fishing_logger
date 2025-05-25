<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->date('date');
            $table->text('notes')->nullable();

            // Remove JSON weather_info and use structured fields instead
            // $table->json('weather_info')->nullable(); // REMOVE THIS LINE

            $table->float('precipitation')->nullable();
            $table->string('moon_phase')->nullable();
            $table->float('wind_speed')->nullable();
            $table->float('air_temp')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
