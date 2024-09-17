<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use App\Services\GitlabService;

use function Termwind\{render};

class GitlabMainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitlab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gitlab test command';

    /**
     * Execute the console command.
     */
    public function handle(GitlabService $gitlabService): void
    {
        // render(<<<'HTML'
        //     <div class="py-1 ml-2">
        //         <div class="px-1 bg-blue-300 text-black">Laravel Zero</div>
        //         <em class="ml-1">
        //         Simplicity is the ultimate sophistication.
        //         </em>
        //     </div>
        // HTML);

        $this->info('--- GITLAB TEST COMMAND ---');

        dd($gitlabService->test());

        
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
