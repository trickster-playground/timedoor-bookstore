@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6 text-center">📚 List of Books</h1>

        <form method="GET" action="{{ route('books.index') }}" class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-4 sm:space-y-0">
                <input type="text" name="search" placeholder="Search by book or author" value="{{ request('search') }}"
                    class="input input-bordered w-full sm:max-w-xs" />

                <select name="limit" class="select select-bordered w-full sm:w-32">
                    @foreach ([10, 20, 30, 40, 50, 60, 70, 80, 90, 100] as $val)
                        <option value="{{ $val }}" {{ $val == request('limit', 10) ? 'selected' : '' }}>
                            Show {{ $val }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary w-full sm:w-auto">Search</button>
            </div>
        </form>

        <div class="overflow-x-auto shadow rounded-lg">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200 text-base font-semibold">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Avg Rating</th>
                        <th>Voters</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td class="font-medium">{{ $book->title }}</td>
                            <td>{{ $book->category->name }}</td>
                            <td>{{ $book->author->name ?? 'Unknown' }}</td>
                            <td>{{ number_format($book->avg_rating, 2) }}</td>
                            <td>{{ $book->voters_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">No books found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
