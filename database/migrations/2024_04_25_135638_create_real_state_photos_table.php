<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('real_state_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('real_state_id')->references('id')->on('real_state')->onDelete('cascade');
            
            $table->string('photo', 255);
            $table->boolean('is_thumb');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_state_photos');
    }
};
