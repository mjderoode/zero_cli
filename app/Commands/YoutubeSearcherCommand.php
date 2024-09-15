<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class YoutubeSearcherCommand extends Command
{
    protected $signature = 'yt {search_query}';

    protected $description = 'YouTube Search Command';
    
    public function handle()
    {
        shell_exec("open 'https://www.youtube.com/results?search_query=" . urlencode($this->argument("search_query"))."'");
    }
}
