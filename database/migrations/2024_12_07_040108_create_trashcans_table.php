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
        Schema::create('trash_cans', function (Blueprint $table) {
            $table->increments('id');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('nearest_building');
            $table->string('image_path');
            $table->json('trash_type')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trash_cans');
    }
};
