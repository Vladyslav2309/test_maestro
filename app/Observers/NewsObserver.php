<?php

namespace App\Observers;

use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsObserver
{

    public function saved(News $news): void
    {
        $oldTags = DB::table('news_tag_histories')
            ->where('news_id', $news->id)
            ->pluck('tag_name')
            ->toArray();

        foreach (News::where('id', '!=', $news->id)->get() as $otherNews) {
            $text = $otherNews->content;


            foreach ($oldTags as $oldTag) {
                $pattern = '/<a\b[^>]*>' . preg_quote($oldTag, '/') . '<\/a>/iu';
                $text = preg_replace($pattern, $oldTag, $text);
            }

            foreach ($news->tags as $tag) {
                $patternWord = '/(?<!\w)(' . preg_quote($tag->name, '/') . ')(?!\w)/iu';
                $replacement = '<a href="' . url('/news/' . $news->slug) . '">' . $tag->name . '</a>';
                $text = preg_replace($patternWord, $replacement, $text);
            }

            if ($text !== $otherNews->content) {
                $otherNews->content = $text;
                $otherNews->saveQuietly();
            }
        }

        DB::table('news_tag_histories')->where('news_id', $news->id)->delete();
        foreach ($news->tags as $tag) {
            DB::table('news_tag_histories')->insert([
                'news_id' => $news->id,
                'tag_name' => $tag->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }


    public function deleting(News $news): void
    {
        foreach (News::where('id', '!=', $news->id)->get() as $otherNews) {
            $text = $otherNews->content;

            foreach ($news->tags as $tag) {
                $pattern = '/<a\b[^>]*>' . preg_quote($tag->name, '/') . '<\/a>/iu';
                $text = preg_replace($pattern, $tag->name, $text);
            }

            if ($text !== $otherNews->content) {
                $otherNews->content = $text;
                $otherNews->saveQuietly();
            }
        }
        DB::table('news_tag_histories')->where('news_id', $news->id)->delete();
    }
}
