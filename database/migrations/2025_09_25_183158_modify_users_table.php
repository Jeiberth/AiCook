<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->integer('age')->nullable();
            $table->string('goal')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('dietary_preferences')->nullable();
            $table->json('allergies')->nullable();
            $table->json('kitchen_equipment')->nullable();
            $table->integer('recipe_tokens')->default(10);
            $table->integer('question_tokens')->default(10);
            $table->string('language')->default('en');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'weight', 'height', 'age', 'goal', 'ingredients',
                'dietary_preferences', 'allergies', 'kitchen_equipment',
                'recipe_tokens', 'question_tokens', 'language'
            ]);
        });
    }
};
