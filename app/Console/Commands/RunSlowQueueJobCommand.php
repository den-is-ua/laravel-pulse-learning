<?php

namespace App\Console\Commands;

use App\Jobs\SlowQueueJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:slow_queue_job')]
#[Description('Dispatch the slow queue job')]
class RunSlowQueueJobCommand extends Command
{
    public function handle(): int
    {
        SlowQueueJob::dispatch();

        $this->components->info('Slow queue job dispatched.');

        return self::SUCCESS;
    }
}
