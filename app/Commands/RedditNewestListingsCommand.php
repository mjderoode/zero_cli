<?php

namespace App\Commands;

use App\Services\RedditRetrievalService;
use LaravelZero\Framework\Commands\Command;

class RedditNewestListingsCommand extends Command
{
    protected $signature = 'reddit {--limit=5} {--sort=new} {--S|subreddit=laravel}';

    protected $description = 'Command description';

    public function handle()
    { 
        $subreddit = $this->option("subreddit");

        if (blank($subreddit)) {
            $subreddit = $this->choice(
                'Choose a /r/subreddit?',
                ['laravel', 'statamic', 'Stocks'],
                0
            );
        }

        $data = app()->call(RedditRetrievalService::class, [
            "subreddit" => $subreddit, 
            "limit" => $this->option("limit")
        ]);

        if (filled($data)) {
            $this->alert("Five newest posts from /r/{$subreddit}");

            $this->table(
                ['Date', 'URL', 'Ups', 'Ups Ratio', 'Comments'],
                $data
            );
        } else {
            $this->error("No data found for /r/{$subreddit}");
        }

        $this->newLine();
    }
}
