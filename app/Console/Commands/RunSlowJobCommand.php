<?php

namespace App\Console\Commands;

use App\Jobs\SlowJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:slow_job')]
#[Description('Dispatch the slow queue job')]
class RunSlowJobCommand extends Command
{
    public function handle(): int
    {
        SlowJob::dispatch();

        $this->components->info('Slow job dispatched.');

        return self::SUCCESS;
    }
}
