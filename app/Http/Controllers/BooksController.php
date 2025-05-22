<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
  public function index(Request $request)
  {
    // input filter search dan jumlah data yang ingin ditampilkan
    $search = $request->input('search');
    $limit = $request->input('limit', 10); // default 10

    // Validasi limit input
    $allowedLimits = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
    if (!in_array((int)$limit, $allowedLimits)) {
      $limit = 10;
    }

    // Query untuk mendapatkan buku beserta rata-rata rating dan jumlah voter
    $books = Book::select(
      'books.*',
      DB::raw('COALESCE(AVG(ratings.rating), 0) as avg_rating'),
      DB::raw('COUNT(ratings.id) as voters_count')
    )
      ->leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
      ->leftJoin('authors', 'authors.id', '=', 'books.author_id')
      ->when($search, function ($query, $search) {
        $query->where('books.title', 'like', "%{$search}%")
          ->orWhere('authors.name', 'like', "%{$search}%");
      })
      ->groupBy('books.id')
      ->orderByDesc('avg_rating')
      ->limit($limit)
      ->get();

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
    $authors = Author::all();

    // Buat array booksByAuthor: key author_id, value koleksi buku author tsb
    $booksByAuthor = [];
    foreach ($authors as $author) {
      $booksByAuthor[$author->id] = $author->books()->select('id', 'title')->get();
    }

    return view('books.ratings', compact('authors', 'booksByAuthor'));
  }

  public function storeRating(Request $request)
  {
    $request->validate([
      'author_id' => 'required|exists:authors,id',
      'book_id' => 'required|exists:books,id',
      'rating' => 'required|integer|min:1|max:10',
    ]);

    // Validasi buku harus milik author yang dipilih
    $book = Book::findOrFail($request->book_id);
    if ($book->author_id != $request->author_id) {
      return back()->withErrors(['book_id' => 'The selected book does not belong to the selected author.'])->withInput();
    }

    // Simpan rating
    Rating::create([
      'book_id' => $request->book_id,
      'rating' => $request->rating,
    ]);

    // Redirect ke halaman list buku (index)
    return redirect()->route('books.index')->with('success', 'Rating submitted successfully!');
  }
}
