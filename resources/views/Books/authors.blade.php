@extends('layouts.app')

@section('title', 'Top 10 Most Famous Authors')

@section('content')
    <div class="max-w-5xl mx-auto px-6 py-12">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-white mb-2">📚 Top 10 Most Famous Authors</h1>
            <p class="text-gray-400 text-lg">Based on number of voters with rating greater than 5</p>
        </div>

        @if ($authors->isEmpty())
            <div class="text-center py-20 bg-base-200 rounded-lg">
                <p class="text-gray-500 text-xl">No authors found with high ratings yet.</p>
            </div>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-base-300">
                <table class="table w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">Author Name</th>
                            <th class="text-left">Total Voters</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($authors as $index => $author)
                            <tr class="hover:bg-gray-300 hover:cursor-pointer transition-all duration-200">
                                <td class="font-medium text-gray-800">{{ $index + 1 }}</td>
                                <td class="font-semibold text-gray-800">{{ $author->name }}</td>
                                <td class="font-bold text-blue-600">{{ number_format($author->voter_count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
