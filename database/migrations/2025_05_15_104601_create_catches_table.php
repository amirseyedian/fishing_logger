<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->string('species');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('length', 5, 2)->nullable();
            $table->unsignedInteger('quantity')->default(1); // ✅ Added line
            $table->string('bait')->nullable();
            $table->string('depth')->nullable();
            $table->time('time_caught')->nullable();
            $table->boolean('is_released')->default(false);
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catches');
    }
};