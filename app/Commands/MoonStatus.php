<?php

namespace App\Commands;

use App\Services\MoonStatusService;
use LaravelZero\Framework\Commands\Command;

class MoonStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get moon illumination percentage';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('🌕 Moon Status 🌕');
        $this->newLine(); 

        $this->table(
            ['Type', 'Percentage'],
            [['Illumination', app(MoonStatusService::class)->getIllumination() . ' %']]
        );

        $this->newLine();
    }
}
