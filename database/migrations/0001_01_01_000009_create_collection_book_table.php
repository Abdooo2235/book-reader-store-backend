<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('collection_book', function (Blueprint $table) {
      $table->id();
      $table->foreignId('collection_id')->constrained()->onDelete('cascade');
      $table->foreignId('book_id')->constrained()->onDelete('cascade');
      $table->timestamp('added_at')->useCurrent();

      $table->unique(['collection_id', 'book_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('collection_book');
  }
};
