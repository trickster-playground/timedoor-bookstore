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
    Schema::table('books', function (Blueprint $table) {
      // Index  untuk join author_id
      $table->index('author_id');

      // Fulltext untuk title
      $table->fullText('title');
    });

    Schema::table('authors', function (Blueprint $table) {
      // Fulltext untuk nama author
      $table->fullText('name');
    });

    Schema::table('ratings', function (Blueprint $table) {
      // Index  untuk join book_id dan agregasi rating
      $table->index('book_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('books', function (Blueprint $table) {
      $table->dropIndex(['author_id']);
      $table->dropFullText(['title']);
    });

    Schema::table('authors', function (Blueprint $table) {
      $table->dropFullText(['name']);
    });

    Schema::table('ratings', function (Blueprint $table) {
      $table->dropIndex(['book_id']);
    });
  }
};
