<?php 

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class RedditRetrievalService
{
    public function __invoke(string $subreddit, int $limit)
    {
        $baseurl = "https://www.reddit.com";

        $response = Http::baseUrl($baseurl)
            ->withHeaders([
                "User-Agent" => "LaravelZero/1.0",
            ])
            ->withQueryParameters([
                "limit" => $limit
            ])->get("/r/$subreddit/new.json");

        $data = collect(Arr::get($response->json(), 'data', null));

        if (blank($data)) {
            return [];
        } 

        return collect($data->get("children"))->map(function ($child) use ($baseurl) {
            return [
                date('Y-m-d H:i:s', $child['data']['created_utc']),
                $baseurl . $child['data']['permalink'],
                $child['data']["ups"],
                $child['data']["upvote_ratio"],
                $child['data']["num_comments"]
            ];
        });
    }
}