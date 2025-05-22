<?php

use App\Http\Controllers\BooksController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BooksController::class, 'index'])->name('books.index');

Route::get('/top-authors', [BooksController::class, 'topAuthors'])->name('books.authors');

Route::get('/ratings/create', [BooksController::class, 'createRating'])->name('books.ratings');
Route::post('/ratings/create', [BooksController::class, 'storeRating'])->name('books.ratings.store');
