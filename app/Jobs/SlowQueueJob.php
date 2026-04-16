<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SlowQueueJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        sleep(2);
    }

    public function handle(): void
    {
        Log::info('Slow queue job finished.');
    }
}
