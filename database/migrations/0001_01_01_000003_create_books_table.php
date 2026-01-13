<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('books', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('author');
      $table->date('release_date')->nullable();
      $table->text('description')->nullable();
      $table->foreignId('category_id')->constrained()->onDelete('cascade');
      $table->string('cover_image')->nullable();
      $table->enum('cover_type', ['upload', 'url'])->default('upload');
      $table->enum('file_type', ['pdf', 'epub'])->default('pdf');
      $table->string('file_url')->nullable();
      $table->integer('number_of_pages')->default(0);
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->string('rejection_reason')->nullable();
      $table->decimal('average_rating', 3, 2)->default(0.00);
      $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('books');
  }
};
