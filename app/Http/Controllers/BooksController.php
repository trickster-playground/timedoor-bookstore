<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{

  const ALLOWED_LIMITS = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];

  public function index(Request $request)
  {
    // input filter search dan jumlah data yang ingin ditampilkan
    $search = $request->input('search');
    $limit = (int) $request->input('limit', 10); // default 10

    // Validasi limit input
    if (!in_array($limit, self::ALLOWED_LIMITS)) {
      $limit = 10;
    }

    // Query untuk mendapatkan buku beserta rata-rata rating dan jumlah voter  
    $books = Book::with('author') // eager load author
      ->select('books.*')
      ->leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
      ->leftJoin('authors', 'authors.id', '=', 'books.author_id')
      ->when($search, function ($query, $search) {
        $query->whereRaw("MATCH(books.title) AGAINST(? IN BOOLEAN MODE)", [$search])
          ->orWhereRaw("MATCH(authors.name) AGAINST(? IN BOOLEAN MODE)", [$search]);
      })
      ->selectRaw('COALESCE(AVG(ratings.rating), 0) as avg_rating')
      ->selectRaw('COUNT(ratings.id) as voters_count')
      ->groupBy('books.id')
      ->orderByDesc('avg_rating')
      ->paginate($limit);

    return view('books.index', compact('books', 'search', 'limit'));
  }

  public function topAuthors()
  {
    $authors = Author::withCount(['books as voter_count' => function ($query) {
      $query->join('ratings', 'books.id', '=', 'ratings.book_id')
        ->where('ratings.rating', '>', 5);
    }])
      ->orderByDesc('voter_count')
      ->take(10)
      ->get();

    return view('books.authors', compact('authors'));
  }

  public function createRating()
  {
    $authors = Author::with('books:id,title,author_id')->get();

    $booksByAuthor = $authors->mapWithKeys(fn($author) => [
      $author->id => $author->books
    ]);

    return view('books.ratings', compact('authors', 'booksByAuthor'));
  }

  public function storeRating(Request $request)
  {
    $request->validate([
      'author_id' => 'required|exists:authors,id',
      'book_id' => 'required|exists:books,id',
      'rating' => 'required|integer|min:1|max:10',
    ]);

    $book = Book::findOrFail($request->book_id);
    if ($book->author_id != $request->author_id) {
      return back()->withErrors([
        'book_id' => 'The selected book does not belong to the selected author.'
      ])->withInput();
    }

    Rating::create([
      'book_id' => $request->book_id,
      'rating' => $request->rating,
    ]);

    return redirect()->route('books.index')
      ->with('success', 'Rating submitted successfully!');
  }
}
