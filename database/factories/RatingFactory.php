<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    static $bookIds = null;

    // Ambil semua ID buku sekali
    if ($bookIds === null) {
      $bookIds = Book::pluck('id')->all();
    }

    return [
      'book_id' => fake()->randomElement($bookIds),
      'rating' => $this->faker->numberBetween(1, 10),
    ];
  }
}
