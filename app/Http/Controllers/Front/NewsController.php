<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::where('is_status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        return view('front.news.index', compact('newsList'));
    }
    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->where('is_status', 1)
            ->firstOrFail();


        $previous = News::where('is_status', 1)
            ->where('created_at', '<', $news->created_at)
            ->orderBy('created_at', 'desc')
            ->first();


        $next = News::where('is_status', 1)
            ->where('created_at', '>', $news->created_at)
            ->orderBy('created_at', 'asc')
            ->first();

        return view('front.news.show', compact('news', 'previous', 'next'));
    }




}
