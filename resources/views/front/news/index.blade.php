@extends('layouts.app')

@section('title', 'Новини')

@section('content')
    <h1 class="text-4xl font-bold mb-8 text-blue-900">Новини</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
        @foreach($newsList as $news)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300">
                @if($news->getFirstMediaUrl('image', 'webp'))
                    <img src="{{ $news->getFirstMediaUrl('image', 'webp') }}"
                         alt="{{ $news->title }}"
                         class="w-full h-48 object-cover">
                @endif

                <div class="p-6">
                    <h2 class="text-xl font-semibold text-blue-900 mb-2">
                        <a href="{{ route('news.show', ['slug' => $news->slug]) }}" class="hover:text-blue-700 transition">
                            {{ $news->title }}
                        </a>
                    </h2>
                    <p class="text-gray-500 text-sm mb-4">{{ $news->created_at->format('d.m.Y H:i') }}</p>
                    <a href="{{ route('news.show', ['slug' => $news->slug]) }}" class="text-blue-700 font-medium hover:underline">
                        Читати далі →
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Кастомная пагинация --}}
    <div class="mt-10 flex justify-center">
        @if ($newsList->hasPages())
            <nav class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                {{-- Previous Page Link --}}
                @if ($newsList->onFirstPage())
                    <span class="px-3 py-2 ml-0 text-gray-400 bg-white border border-gray-300 rounded-l-md cursor-not-allowed">&laquo;</span>
                @else
                    <a href="{{ $newsList->previousPageUrl() }}" class="px-3 py-2 ml-0 text-blue-700 bg-white border border-gray-300 rounded-l-md hover:bg-blue-50 hover:text-blue-900">&laquo;</a>
                @endif

                {{-- Pagination Numbers --}}
                @foreach ($newsList->getUrlRange(1, $newsList->lastPage()) as $page => $url)
                    @if ($page == $newsList->currentPage())
                        <span class="px-3 py-2 text-white bg-blue-700 border border-gray-300">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-blue-700 bg-white border border-gray-300 hover:bg-blue-50 hover:text-blue-900">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($newsList->hasMorePages())
                    <a href="{{ $newsList->nextPageUrl() }}" class="px-3 py-2 text-blue-700 bg-white border border-gray-300 rounded-r-md hover:bg-blue-50 hover:text-blue-900">&raquo;</a>
                @else
                    <span class="px-3 py-2 text-gray-400 bg-white border border-gray-300 rounded-r-md cursor-not-allowed">&raquo;</span>
                @endif
            </nav>
        @endif
    </div>
@endsection
