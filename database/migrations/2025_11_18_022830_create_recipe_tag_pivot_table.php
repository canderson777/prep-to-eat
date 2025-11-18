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
        Schema::create('recipe_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saved_recipe_id')->constrained('saved_recipes')->onDelete('cascade');
            $table->foreignId('recipe_tag_id')->constrained('recipe_tags')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['saved_recipe_id', 'recipe_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_tag');
    }
};
