<?php

namespace App\Console\Commands;

use App\Jobs\FastJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:fast_job')]
#[Description('Dispatch the fast queue job')]
class RunFastJobCommand extends Command
{
    public function handle(): int
    {
        FastJob::dispatch();

        $this->components->info('Fast job dispatched.');

        return self::SUCCESS;
    }
}
