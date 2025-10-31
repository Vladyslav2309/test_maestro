@extends('layouts.app')

@section('title', $news->title)

@section('content')
    <nav class="text-gray-500 mb-4 text-sm">
        <a href="{{ route('news.index') }}" class="hover:underline">Головна</a> &raquo; {{ $news->title }}
    </nav>

    <article class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h1 class="text-4xl font-bold text-blue-900 mb-2">{{ $news->title }}</h1>
        <p class="text-gray-500 mb-4">{{ $news->created_at->format('d.m.Y H:i') }}</p>

        @if($news->getFirstMediaUrl('image', 'webp'))
            <img src="{{ $news->getFirstMediaUrl('image', 'webp') }}"
                 alt="{{ $news->title }}"
                 class="w-full max-h-96 object-cover rounded-lg mb-6">
        @endif

        <div class="prose max-w-none mb-6">
            {!! str_replace(
                '<a ',
                '<a class="text-blue-600 underline hover:text-blue-800 hover:decoration-wavy hover:decoration-blue-400" ',
                $news->content
            ) !!}
        </div>

        <div class="flex justify-between mt-8 text-blue-700 font-medium">
            @if($previous)
                <a href="{{ route('news.show', ['slug' => $previous->slug]) }}" class="hover:underline">
                    &laquo; {{ $previous->title }}
                </a>
            @else
                <span></span>
            @endif

            @if($next)
                <a href="{{ route('news.show', ['slug' => $next->slug]) }}" class="hover:underline">
                    {{ $next->title }} &raquo;
                </a>
            @else
                <span></span>
            @endif
        </div>
    </article>
@endsection
