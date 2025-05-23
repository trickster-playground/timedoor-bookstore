<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeDataSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::disableQueryLog(); // mencegah memory overflow

    // // 1. Tambah Author sampai 1000
    $targetAuthors = 1000;
    $currentAuthors = Author::count();
    $missingAuthors = $targetAuthors - $currentAuthors;

    if ($missingAuthors > 0) {
      $this->batchInsert(Author::class, $missingAuthors, 1000);
    }

    // // 2. Tambah Category sampai 3000
    $targetCategories = 3000;
    $currentCategories = Category::count();
    $missingCategories = $targetCategories - $currentCategories;

    if ($missingCategories > 0) {
      $this->batchInsert(Category::class, $missingCategories, 1000);
    }


    // // 3. Tambah Book sampai 100.000
    $targetBooks = 100000;
    $currentBooks = Book::count();
    $missingBooks = $targetBooks - $currentBooks;

    if ($missingBooks > 0) {
      $this->batchInsert(Book::class, $missingBooks, 2000); // Batasi 2000 per batch
    }

    // 4. Tambah Rating sampai 500.000
    $targetRatings = 500000;
    $currentRatings = Rating::count();
    $missingRatings = $targetRatings - $currentRatings;

    if ($missingRatings > 0) {
      $this->batchInsert(Rating::class, $missingRatings, 5000);
    }
  }

  private function batchInsert(string $modelClass, int $total, int $batchSize)
  {
    $batches = (int) ceil($total / $batchSize);

    for ($i = 0; $i < $batches; $i++) {
      $count = min($batchSize, $total - $i * $batchSize);
      $modelClass::factory($count)->create();
      $this->command->info("Seeded {$count} to {$modelClass}");
    }
  }
}
