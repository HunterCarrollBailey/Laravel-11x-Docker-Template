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
        Schema::create('learning_path_content', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_path_id')->constrained();
            $table->foreignId('course_id')->nullable()->constrained();
            $table->foreignId('module_id')->nullable()->constrained();
            $table->foreignId('parent')->nullable()->references('id')->on('learning_path_content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_path_content');
    }
};
