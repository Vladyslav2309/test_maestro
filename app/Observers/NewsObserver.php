<?php

namespace App\Observers;

use App\Models\News;

class NewsObserver
{
    public function saved(News $news): void
    {
        $this->updateAllLinks();
    }

    public function deleting(News $news): void
    {
        $this->updateAllLinks();
    }

    protected function updateAllLinks(): void
    {
        $allNews = News::with('tags')->get();


        foreach ($allNews as $newsItem) {
            $newsItem->content = preg_replace('/<a\b[^>]*>(.*?)<\/a>/iu', '$1', $newsItem->content);
            $newsItem->saveQuietly();
        }


        $tagMap = [];
        foreach ($allNews as $newsItem) {
            foreach ($newsItem->tags as $tag) {
                $tagMap[$tag->name][] = $newsItem;
            }
        }


        foreach ($allNews as $newsItem) {
            $text = $newsItem->content;

            foreach ($tagMap as $tagName => $newsList) {
                $text = preg_replace_callback(
                    '/(?<!\w)(' . preg_quote($tagName, '/') . ')(?!\w)/iu',
                    function ($matches) use ($newsList) {

                        return '<a href="' . url('/news/' . $newsList[0]->slug) . '">' . $matches[1] . '</a>';
                    },
                    $text
                );
            }

            if ($text !== $newsItem->content) {
                $newsItem->content = $text;
                $newsItem->saveQuietly();
            }
        }
    }
}
