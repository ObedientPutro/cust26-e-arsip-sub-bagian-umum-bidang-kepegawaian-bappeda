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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate();
            $table->foreignId('category_id')
                ->references('id')
                ->on('categories')
                ->cascadeOnUpdate();
            $table->string('letter_number')->unique();
            $table->string('subject');
            $table->string('sender')->nullable();
            $table->date('letter_date');
            $table->string('type');
            $table->string('file_path');
            $table->boolean('is_disposed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
