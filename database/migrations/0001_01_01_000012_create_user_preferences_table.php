<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('user_preferences', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
      $table->enum('theme', ['light', 'dark'])->default('light');
      $table->integer('font_size')->default(16);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('user_preferences');
  }
};
