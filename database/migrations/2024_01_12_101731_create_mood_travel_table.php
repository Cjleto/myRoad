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
        Schema::create('mood_travel', function (Blueprint $table) {
            $table->uuid('moodId')->references('id')->on('moods');
            $table->uuid('travelId')->references('id')->on('travels');
            $table->integer('value')->default('0');
            $table->timestamps();

            $table->primary(['moodId', 'travelId']);
            $table->foreign('moodId')->references('id')->on('moods');
            $table->foreign('travelId')->references('id')->on('travels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_travel');
    }
};
