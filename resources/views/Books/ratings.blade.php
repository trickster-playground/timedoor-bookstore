@extends('layouts.app')

@section('title', 'Input Rating')

@section('content')
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md mt-10 ">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-500">Input Rating</h1>

        <form action="{{ route('books.ratings.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Author Select --}}
            <div>
                <label for="author_id" class="block mb-2 font-semibold text-gray-700">Select Author</label>
                <select name="author_id" id="author_id" required class="select select-bordered w-full"
                    onchange="filterBooks(this.value)">
                    <option value="" disabled selected>-- Choose Author --</option>
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}</option>
                    @endforeach
                </select>
                @error('author_id')
                    <p class="text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Book Select --}}
            <div>
                <label for="book_id" class="block mb-2 font-semibold text-gray-700">Select Book</label>
                <select name="book_id" id="book_id" required class="select select-bordered w-full"
                    {{ old('author_id') ? '' : 'disabled' }}>
                    <option value="" disabled selected>-- Select an author first --</option>
                    @foreach ($booksByAuthor as $authorId => $books)
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}" data-author="{{ $authorId }}"
                                class="{{ old('book_id') == $book->id ? '' : 'hidden' }}"
                                {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                @error('book_id')
                    <p class="text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rating Select --}}
            <div>
                <label for="rating" class="block mb-2 font-semibold text-gray-700">Rating (1-10)</label>
                <select name="rating" id="rating" required class="select select-bordered w-full">
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                            {{ $i }}</option>
                    @endfor
                </select>
                @error('rating')
                    <p class="text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">Submit Rating</button>
        </form>
    </div>

    <script>
      // Data buku per author, disederhanakan hanya id dan title
      const booksByAuthor = @json($booksByAuthor->map(fn($books) => $books->map(fn($book) => [
          'id' => $book->id,
          'title' => $book->title
      ])));
  
      const bookSelect = document.getElementById('book_id');
  
      function filterBooks(authorId) {
          bookSelect.innerHTML = ''; // Kosongkan 
  
          if (!authorId || !booksByAuthor[authorId]) {
              bookSelect.disabled = true;
              const option = document.createElement('option');
              option.text = '-- Select an author first --';
              option.disabled = true;
              option.selected = true;
              bookSelect.appendChild(option);
              return;
          }
  
          // Tambahkan option default kosong supaya user harus pilih buku
          const defaultOption = document.createElement('option');
          defaultOption.text = '-- Choose Book --';
          defaultOption.disabled = true;
          defaultOption.selected = true;
          bookSelect.appendChild(defaultOption);
  
          // Render buku sesuai author
          booksByAuthor[authorId].forEach(book => {
              const option = document.createElement('option');
              option.value = book.id;
              option.text = book.title;
  
              // Jika old('book_id') cocok, pilih option tersebut
              if ("{{ old('book_id') }}" == book.id) {
                  option.selected = true;
              }
  
              bookSelect.appendChild(option);
          });
  
          bookSelect.disabled = false;
      }
  
      // Jika ada old author_id dari validasi gagal, jalankan filter agar select buku muncul
      document.addEventListener('DOMContentLoaded', () => {
          const oldAuthor = "{{ old('author_id') }}";
          if (oldAuthor) {
              filterBooks(oldAuthor);
          } else {
              // Pas pertama load, set disabled dan default option
              filterBooks(null);
          }
      });
  </script>
@endsection
