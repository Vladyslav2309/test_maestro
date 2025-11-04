<?php

namespace App\Observers;

use App\Models\News;

class NewsObserver
{
    public function saved(News $news): void
    {
        $this->updateAllLinks();
    }

    public function deleted(News $news): void
    {
        $this->updateAllLinks();
    }

    protected function updateAllLinks(): void
    {
        $allNews = News::with('tags')->get();

        $tagMap = [];
        foreach ($allNews as $newsItem) {
            foreach ($newsItem->tags as $tag) {

                if (!isset($tagMap[$tag->name])) {
                    $tagMap[$tag->name] = url('/news/' . $newsItem->slug);
                }
            }
        }


        foreach ($allNews as $newsItem) {
            $originalContent = $newsItem->content;

            $cleanContent = preg_replace('/<a\b[^>]*>(.*?)<\/a>/iu', '$1', $originalContent);


            foreach ($tagMap as $tagName => $link) {

                $cleanContent = preg_replace_callback(
                    '/(?<!\w)(' . preg_quote($tagName, '/') . ')(?!\w)/iu',
                    fn($matches) => '<a href="' . $link . '">' . $matches[1] . '</a>',
                    $cleanContent
                );
            }


            if ($cleanContent !== $originalContent) {
                $newsItem->updateQuietly(['content' => $cleanContent]);
            }
        }
    }
}
